# PHP Docker Environment - Production Ready

Konfigurasi Docker yang aman, optimal, dan mengikuti best practices untuk aplikasi PHP (Laravel & CodeIgniter 3).

> 📑 **Dokumentasi Lengkap**: Lihat [DOCS_INDEX.md](DOCS_INDEX.md) untuk navigasi semua dokumentasi
> 
> 🔄 **Upgrade dari config lama?** Baca [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) dan [ENV_SETUP.md](ENV_SETUP.md)

## 🎯 Fitur Utama

### ✅ Security Features
- **Multi-stage builds** untuk image production yang minimal
- **Non-root user** untuk menjalankan aplikasi
- **Read-only filesystem** di production
- **Security headers** lengkap di Nginx
- **Rate limiting** untuk mencegah abuse
- **Disabled dangerous PHP functions**
- **Health checks** untuk semua services
- **Strong password requirements** di production

### ⚡ Performance Features
- **OPcache** fully optimized untuk production
- **PHP-FPM** tuning untuk high traffic
- **Nginx caching** untuk static files
- **Resource limits** untuk mencegah resource exhaustion
- **Connection pooling** dan buffer optimization

### 🏗️ Best Practices
- Separate configurations untuk **development** dan **production**
- Framework-specific configs untuk **Laravel** dan **CodeIgniter 3**
- **Health checks** untuk monitoring
- **Proper logging** configuration
- **.dockerignore** untuk build optimization
- **Composer cache** untuk faster builds

## 📁 Struktur Project

```
.
├── docker/
│   ├── laravel/
│   │   ├── Dockerfile                    # Multi-stage Dockerfile untuk Laravel
│   │   ├── healthcheck.sh                # Health check script
│   │   ├── nginx/
│   │   │   ├── dev.conf                  # Nginx config development
│   │   │   └── prod.conf                 # Nginx config production
│   │   ├── php/
│   │   │   ├── dev/
│   │   │   │   ├── php.ini               # PHP config development
│   │   │   │   └── opcache.ini           # OPcache config development
│   │   │   └── prod/
│   │   │       ├── php.ini               # PHP config production
│   │   │       └── opcache.ini           # OPcache config production
│   │   └── php-fpm/
│   │       ├── dev/
│   │       │   └── www.conf              # PHP-FPM pool config development
│   │       └── prod/
│   │           └── www.conf              # PHP-FPM pool config production
│   ├── codeigniter3/
│   │   ├── Dockerfile                    # Multi-stage Dockerfile untuk CI3
│   │   ├── healthcheck.sh
│   │   ├── nginx/
│   │   │   ├── dev.conf
│   │   │   └── prod.conf
│   │   ├── php/
│   │   │   ├── dev/
│   │   │   │   ├── php.ini
│   │   │   │   └── opcache.ini
│   │   │   └── prod/
│   │   │       ├── php.ini
│   │   │       └── opcache.ini
│   │   └── php-fpm/
│   │       ├── dev/
│   │       │   └── www.conf
│   │       └── prod/
│   │           └── www.conf
│   └── mysql/
│       ├── dev.cnf                       # MySQL config development
│       └── prod.cnf                      # MySQL config production
├── docker-compose.laravel.dev.yml        # Docker Compose Laravel development
├── docker-compose.laravel.prod.yml       # Docker Compose Laravel production
├── docker-compose.codeigniter3.dev.yml   # Docker Compose CI3 development
├── docker-compose.codeigniter3.prod.yml  # Docker Compose CI3 production
├── .env.laravel.dev.example
├── .env.laravel.prod.example
├── .env.codeigniter3.dev.example
├── .env.codeigniter3.prod.example
├── .dockerignore
├── Makefile.laravel
├── Makefile.codeigniter3
└── README.md
```

## 🚀 Quick Start

### Untuk Laravel

#### Development
```bash
# 1. Copy environment file
cp .env.laravel.dev.example .env

# 2. Update .env dengan konfigurasi Anda

# 3. Build dan start containers
make -f Makefile.laravel dev-up

# 4. Install dependencies
make -f Makefile.laravel composer CMD="install"

# 5. Generate application key
make -f Makefile.laravel artisan CMD="key:generate"

# 6. Run migrations
make -f Makefile.laravel migrate

# Akses aplikasi di: http://localhost:8080
```

#### Production
```bash
# 1. Copy environment file
cp .env.laravel.prod.example .env

# 2. Update .env dengan STRONG PASSWORDS!

# 3. Build production images
make -f Makefile.laravel build-prod

# 4. Start production environment
make -f Makefile.laravel prod-up
```

### Untuk CodeIgniter 3

#### Development
```bash
# 1. Copy environment file
cp .env.codeigniter3.dev.example .env

# 2. Update .env dengan konfigurasi Anda

# 3. Build dan start containers
make -f Makefile.codeigniter3 dev-up

# Akses aplikasi di: http://localhost:8080
```

#### Production
```bash
# 1. Copy environment file
cp .env.codeigniter3.prod.example .env

# 2. Update .env dengan STRONG PASSWORDS!

# 3. Build production images
make -f Makefile.codeigniter3 build-prod

# 4. Start production environment
make -f Makefile.codeigniter3 prod-up
```

## 📋 Available Commands

### Laravel Commands
```bash
make -f Makefile.laravel help              # Show all available commands
make -f Makefile.laravel dev-up            # Start development environment
make -f Makefile.laravel dev-down          # Stop development environment
make -f Makefile.laravel build-dev         # Build development images
make -f Makefile.laravel prod-up           # Start production environment
make -f Makefile.laravel prod-down         # Stop production environment
make -f Makefile.laravel build-prod        # Build production images
make -f Makefile.laravel shell             # Open shell in app container
make -f Makefile.laravel composer CMD=     # Run composer command
make -f Makefile.laravel artisan CMD=      # Run artisan command
make -f Makefile.laravel migrate           # Run migrations
make -f Makefile.laravel test              # Run tests
make -f Makefile.laravel logs SERVICE=     # View logs
make -f Makefile.laravel clean             # Clean all containers and volumes
```

### CodeIgniter 3 Commands
```bash
make -f Makefile.codeigniter3 help         # Show all available commands
make -f Makefile.codeigniter3 dev-up       # Start development environment
make -f Makefile.codeigniter3 dev-down     # Stop development environment
make -f Makefile.codeigniter3 build-dev    # Build development images
make -f Makefile.codeigniter3 prod-up      # Start production environment
make -f Makefile.codeigniter3 prod-down    # Stop production environment
make -f Makefile.codeigniter3 build-prod   # Build production images
make -f Makefile.codeigniter3 shell        # Open shell in app container
make -f Makefile.codeigniter3 logs SERVICE= # View logs
make -f Makefile.codeigniter3 clean        # Clean all containers and volumes
```

## 🔒 Security Considerations

### Development vs Production

| Feature | Development | Production |
|---------|-------------|------------|
| Error Display | ON | OFF |
| OPcache | Disabled | Enabled & Optimized |
| Debug Mode | ON | OFF |
| Exposed Ports | DB, Redis exposed | Only web port exposed |
| SSL/HTTPS | HTTP only | Should use HTTPS (via reverse proxy) |
| Passwords | Simple | Strong & complex |
| Resource Limits | None | Strict limits |
| Read-only FS | No | Yes |

### Production Checklist

- [ ] Update semua passwords di `.env` dengan strong passwords
- [ ] Set `APP_DEBUG=false` di Laravel
- [ ] Set `CI_ENV=production` di CodeIgniter
- [ ] Enable HTTPS via reverse proxy (Nginx/Traefik/Caddy)
- [ ] Configure firewall rules
- [ ] Setup monitoring dan logging
- [ ] Configure automated backups
- [ ] Review dan adjust resource limits
- [ ] Setup SSL certificates
- [ ] Configure rate limiting sesuai kebutuhan

## 🔧 Customization

### Mengubah PHP Version
Edit file `.env`:
```bash
PHP_VERSION=8.2  # untuk Laravel
PHP_VERSION=7.4  # untuk CodeIgniter 3
```

### Mengubah Port
Edit file `.env`:
```bash
APP_PORT=8080    # Web port
DB_PORT=3306     # Database port
REDIS_PORT=6379  # Redis port
```

### Mengubah Resource Limits
Edit `docker-compose.*.prod.yml` di section `deploy.resources`:
```yaml
deploy:
  resources:
    limits:
      cpus: '2'
      memory: 1G
```

### Mengubah PHP-FPM Settings
Edit file `docker/*/php-fpm/*/www.conf`:
```ini
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 15
```

## 📊 Monitoring

### Health Checks
Semua services memiliki health checks:
```bash
docker ps  # Lihat status health
```

### Logs
```bash
# View all logs
make -f Makefile.laravel logs

# View specific service logs
make -f Makefile.laravel logs SERVICE=app
make -f Makefile.laravel logs SERVICE=web
make -f Makefile.laravel logs SERVICE=db
```

### Database Backup
```bash
make -f Makefile.laravel db-dump
```

## 🐛 Troubleshooting

### Permission Issues
```bash
# Fix permissions
sudo chown -R $USER:$USER .
```

### Port Already in Use
Edit `.env` dan ubah port yang conflict:
```bash
APP_PORT=8081
DB_PORT=3307
```

### Container Won't Start
```bash
# Check logs
make -f Makefile.laravel logs SERVICE=app

# Rebuild images
make -f Makefile.laravel build-dev
```

## 📚 Dokumentasi Lengkap

### 📖 Panduan Utama
- **[DOCS_INDEX.md](DOCS_INDEX.md)** - 📑 Index navigasi semua dokumentasi
- **[ENV_SETUP.md](ENV_SETUP.md)** - ⚙️ Setup environment variables
- **[MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** - 🔄 Panduan migrasi dari config lama
- **[SECURITY.md](SECURITY.md)** - 🔒 Security best practices & checklist
- **[IMPROVEMENTS.md](IMPROVEMENTS.md)** - 📊 Analisis detail & improvements

### 🔗 External Resources
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)
- [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.configuration.php)
- [Nginx Configuration](https://nginx.org/en/docs/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [CodeIgniter 3 Documentation](https://codeigniter.com/userguide3/)

## ⚠️ Upgrade dari Konfigurasi Lama?

Jika Anda upgrade dari konfigurasi Docker lama, **wajib baca**:
1. **[MIGRATION_GUIDE.md](MIGRATION_GUIDE.md)** - Step-by-step migration guide
2. **[ENV_SETUP.md](ENV_SETUP.md)** - Update environment variables

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This project is open-sourced software licensed under the MIT license.
