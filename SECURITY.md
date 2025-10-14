# Security Guide

Panduan keamanan untuk deployment Docker PHP applications.

## 🔐 Security Features Implemented

### 1. Container Security

#### Non-Root User
- Semua containers berjalan sebagai non-root user (`appuser`)
- UID/GID dapat dikustomisasi sesuai host system
- Mencegah privilege escalation attacks

#### Read-Only Filesystem (Production)
```yaml
read_only: true
tmpfs:
  - /tmp
  - /var/run
```
- Filesystem read-only mencegah malicious code injection
- Hanya `/tmp` dan `/var/run` yang writable

#### Security Options
```yaml
security_opt:
  - no-new-privileges:true
```
- Mencegah processes dari gaining additional privileges

### 2. PHP Security

#### Disabled Functions
Development:
```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
```

Production (lebih strict):
```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source,phpinfo
```

#### Session Security
```ini
session.cookie_httponly = 1      # Prevent XSS attacks
session.cookie_secure = 1        # HTTPS only (production)
session.use_strict_mode = 1      # Prevent session fixation
session.cookie_samesite = "Strict" # CSRF protection
```

#### Other PHP Settings
```ini
expose_php = Off                 # Hide PHP version
allow_url_include = Off          # Prevent remote file inclusion
```

### 3. Nginx Security

#### Security Headers (Production)
```nginx
add_header X-Frame-Options "DENY" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
```

#### Rate Limiting
```nginx
limit_req_zone $binary_remote_addr zone=general:10m rate=10r/s;
limit_req_zone $binary_remote_addr zone=api:10m rate=30r/s;
limit_conn_zone $binary_remote_addr zone=conn_limit:10m;
```

#### Hidden Server Tokens
```nginx
server_tokens off;  # Hide Nginx version
```

#### Protected Directories
Laravel:
```nginx
location ~ ^/(storage|vendor|bootstrap/cache) {
    deny all;
}
```

CodeIgniter 3:
```nginx
location ~ ^/system/ {
    deny all;
}
location ~ ^/application/ {
    deny all;
}
```

### 4. Database Security

#### Authentication
- Separate root and application users
- Strong password requirements in production
- No default passwords

#### Network Isolation
- Database tidak exposed ke internet di production
- Hanya accessible dari internal network

#### Configuration
```cnf
local_infile = 0           # Disable LOAD DATA LOCAL INFILE
skip_name_resolve = 1      # Disable DNS lookups
```

### 5. Redis Security

#### Authentication
```bash
--requirepass ${REDIS_PASSWORD}
```

#### Memory Limits
```bash
--maxmemory 512mb
--maxmemory-policy allkeys-lru
```

## 🚨 Security Checklist

### Pre-Production

- [ ] **Change all default passwords**
  - Database root password
  - Database user password
  - Redis password
  
- [ ] **Review exposed ports**
  - Only web port (80/443) should be exposed
  - Database and Redis should NOT be exposed to internet
  
- [ ] **Enable HTTPS**
  - Use reverse proxy (Nginx, Traefik, Caddy)
  - Configure SSL certificates (Let's Encrypt)
  - Force HTTPS redirects
  
- [ ] **Update environment variables**
  - Set `APP_DEBUG=false` (Laravel)
  - Set `CI_ENV=production` (CodeIgniter)
  - Use strong encryption keys
  
- [ ] **Review file permissions**
  - Writable directories only where necessary
  - No world-writable files
  
- [ ] **Configure firewall**
  - Allow only necessary ports
  - Restrict access by IP if possible
  
- [ ] **Setup monitoring**
  - Log aggregation
  - Error monitoring
  - Performance monitoring
  
- [ ] **Enable automated backups**
  - Database backups
  - Application files backups
  - Backup encryption

### Post-Production

- [ ] **Regular updates**
  - Update Docker images regularly
  - Update PHP version
  - Update dependencies
  
- [ ] **Security scanning**
  - Scan images for vulnerabilities
  - Dependency vulnerability scanning
  
- [ ] **Log monitoring**
  - Monitor for suspicious activities
  - Setup alerts for errors
  
- [ ] **Performance monitoring**
  - Monitor resource usage
  - Setup alerts for anomalies

## 🔍 Security Scanning

### Scan Docker Images
```bash
# Using Trivy
docker run --rm -v /var/run/docker.sock:/var/run/docker.sock \
  aquasec/trivy image laravel_app:8.2-prod

# Using Docker Scout
docker scout cves laravel_app:8.2-prod
```

### Scan Dependencies
```bash
# PHP dependencies
docker compose -f docker-compose.laravel.dev.yml exec app \
  composer audit

# Check for outdated packages
docker compose -f docker-compose.laravel.dev.yml exec app \
  composer outdated
```

## 🛡️ Best Practices

### 1. Secrets Management

**❌ JANGAN:**
```yaml
environment:
  - DB_PASSWORD=mysecretpassword  # Hardcoded password
```

**✅ LAKUKAN:**
```yaml
environment:
  - DB_PASSWORD=${DB_PASSWORD}    # From .env file
```

Atau gunakan Docker Secrets:
```yaml
secrets:
  - db_password

secrets:
  db_password:
    external: true
```

### 2. Image Management

**Multi-stage builds:**
- Separate build dan runtime stages
- Minimal production images
- No development tools in production

**Image tagging:**
```bash
# ❌ JANGAN gunakan :latest di production
image: myapp:latest

# ✅ GUNAKAN specific versions
image: myapp:1.2.3
image: myapp:8.2-prod-20241014
```

### 3. Network Security

**Isolate networks:**
```yaml
networks:
  frontend:
    driver: bridge
  backend:
    driver: bridge
    internal: true  # No external access
```

**Use internal DNS:**
```yaml
# ✅ Use service names
DB_HOST=db

# ❌ Don't use IP addresses
DB_HOST=172.20.0.5
```

### 4. Resource Limits

**Always set limits in production:**
```yaml
deploy:
  resources:
    limits:
      cpus: '2'
      memory: 1G
    reservations:
      cpus: '0.5'
      memory: 512M
```

### 5. Logging

**Centralized logging:**
```yaml
logging:
  driver: "json-file"
  options:
    max-size: "10m"
    max-file: "3"
```

**Log to stdout/stderr:**
- Semua logs dikirim ke stdout/stderr
- Mudah di-aggregate dengan logging drivers
- Tidak perlu volume untuk logs

## 🚀 HTTPS Setup Example

### Using Traefik

```yaml
version: '3.8'

services:
  traefik:
    image: traefik:v2.10
    command:
      - "--providers.docker=true"
      - "--entrypoints.web.address=:80"
      - "--entrypoints.websecure.address=:443"
      - "--certificatesresolvers.myresolver.acme.tlschallenge=true"
      - "--certificatesresolvers.myresolver.acme.email=your@email.com"
      - "--certificatesresolvers.myresolver.acme.storage=/letsencrypt/acme.json"
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock:ro
      - ./letsencrypt:/letsencrypt

  web:
    image: nginx:1.25-alpine
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.web.rule=Host(`yourdomain.com`)"
      - "traefik.http.routers.web.entrypoints=websecure"
      - "traefik.http.routers.web.tls.certresolver=myresolver"
```

## 📞 Security Incident Response

### If Compromised:

1. **Isolate the system**
   ```bash
   docker compose down
   ```

2. **Preserve evidence**
   ```bash
   docker logs app > incident_logs.txt
   ```

3. **Analyze logs**
   - Check access logs
   - Check error logs
   - Check system logs

4. **Rebuild from scratch**
   ```bash
   docker compose down -v --rmi all
   docker compose build --no-cache
   ```

5. **Update all secrets**
   - Change all passwords
   - Rotate encryption keys
   - Update API tokens

## 📚 Additional Resources

- [OWASP Docker Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Docker_Security_Cheat_Sheet.html)
- [CIS Docker Benchmark](https://www.cisecurity.org/benchmark/docker)
- [Docker Security Best Practices](https://docs.docker.com/engine/security/)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)
