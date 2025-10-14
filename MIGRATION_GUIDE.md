# Migration Guide

Panduan untuk migrasi dari konfigurasi lama ke konfigurasi baru yang lebih aman dan optimal.

## 📊 Perbandingan Konfigurasi

### Struktur File

#### Konfigurasi Lama
```
.
├── Dockerfile (single file untuk semua)
├── docker-compose.dev.yml (hanya dev)
├── docker/
│   ├── nginx/default.conf
│   ├── php/conf.d/custom.ini
│   └── php-fpm/laravel.conf
└── .env.example
```

#### Konfigurasi Baru
```
.
├── docker/
│   ├── laravel/
│   │   ├── Dockerfile (multi-stage)
│   │   ├── nginx/dev.conf & prod.conf
│   │   ├── php/dev/ & prod/
│   │   └── php-fpm/dev/ & prod/
│   ├── codeigniter3/
│   │   └── (struktur sama)
│   └── mysql/dev.cnf & prod.cnf
├── docker-compose.laravel.dev.yml
├── docker-compose.laravel.prod.yml
├── docker-compose.codeigniter3.dev.yml
├── docker-compose.codeigniter3.prod.yml
└── .env.*.example (4 files)
```

## 🔄 Langkah Migrasi

### Step 1: Backup Data

```bash
# Backup database
docker compose -f docker-compose.dev.yml exec db \
  mysqldump -u root -p laravel > backup.sql

# Backup volumes
docker run --rm -v docker-3_db_data:/data -v $(pwd):/backup \
  alpine tar czf /backup/db_data_backup.tar.gz /data

# Backup .env
cp .env .env.backup
```

### Step 2: Stop Old Containers

```bash
# Stop semua containers
docker compose -f docker-compose.dev.yml down

# Optional: Remove volumes (jika ingin clean start)
# docker compose -f docker-compose.dev.yml down -v
```

### Step 3: Pilih Framework

Tentukan framework yang Anda gunakan:

#### Untuk Laravel:
```bash
# Copy environment file
cp .env.laravel.dev.example .env

# Update dengan nilai dari .env.backup
# Sesuaikan nama variabel jika ada yang berbeda
```

#### Untuk CodeIgniter 3:
```bash
# Copy environment file
cp .env.codeigniter3.dev.example .env

# Update dengan nilai dari .env.backup
```

### Step 4: Update Environment Variables

Mapping variabel lama ke baru:

| Lama | Baru | Notes |
|------|------|-------|
| `APP_FRAMEWORK` | Tidak digunakan | Pilih compose file yang sesuai |
| `APPNAME` | `APP_NAME` | Nama aplikasi |
| `APP_FWD_PORT` | `APP_PORT` | Port aplikasi |
| `DB_FWD_PORT` | `DB_PORT` | Port database |
| `REDIS_FWD_PORT` | `REDIS_PORT` | Port Redis |
| - | `REDIS_PASSWORD` | **BARU**: Password untuk Redis |

### Step 5: Build New Images

#### Untuk Laravel:
```bash
# Build development images
make -f Makefile.laravel build-dev

# Atau manual:
docker compose -f docker-compose.laravel.dev.yml build
```

#### Untuk CodeIgniter 3:
```bash
# Build development images
make -f Makefile.codeigniter3 build-dev

# Atau manual:
docker compose -f docker-compose.codeigniter3.dev.yml build
```

### Step 6: Start New Environment

#### Untuk Laravel:
```bash
# Start containers
make -f Makefile.laravel dev-up

# Atau manual:
docker compose -f docker-compose.laravel.dev.yml up -d
```

#### Untuk CodeIgniter 3:
```bash
# Start containers
make -f Makefile.codeigniter3 dev-up

# Atau manual:
docker compose -f docker-compose.codeigniter3.dev.yml up -d
```

### Step 7: Restore Data

```bash
# Restore database (Laravel)
docker compose -f docker-compose.laravel.dev.yml exec -T db \
  mysql -u laravel -p laravel < backup.sql

# Restore database (CodeIgniter 3)
docker compose -f docker-compose.codeigniter3.dev.yml exec -T db \
  mysql -u ci3_user -p ci3_db < backup.sql
```

### Step 8: Verify

```bash
# Check container status
docker ps

# Check health status
docker ps --format "table {{.Names}}\t{{.Status}}"

# Test application
curl http://localhost:8080

# Check logs
make -f Makefile.laravel logs
```

## 🔧 Troubleshooting Migration Issues

### Issue 1: Port Conflicts

**Problem:** Port sudah digunakan oleh container lama

**Solution:**
```bash
# Stop semua containers
docker ps -a | grep APPNAME | awk '{print $1}' | xargs docker rm -f

# Atau ubah port di .env
APP_PORT=8081
```

### Issue 2: Volume Conflicts

**Problem:** Volume name conflicts

**Solution:**
```bash
# List volumes
docker volume ls | grep APPNAME

# Remove old volumes (HATI-HATI: Data akan hilang!)
docker volume rm APPNAME_db_data

# Atau rename di docker-compose.yml
```

### Issue 3: Permission Issues

**Problem:** Permission denied pada files

**Solution:**
```bash
# Update UID/GID di .env sesuai user Anda
LOCAL_UID=$(id -u)
LOCAL_GID=$(id -g)

# Rebuild images
make -f Makefile.laravel build-dev

# Fix permissions
sudo chown -R $USER:$USER .
```

### Issue 4: Database Connection Failed

**Problem:** Aplikasi tidak bisa connect ke database

**Solution:**
```bash
# Verify database is running
docker ps | grep db

# Check database logs
make -f Makefile.laravel logs SERVICE=db

# Verify credentials di .env
DB_HOST=db  # BUKAN localhost!
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### Issue 5: Redis Connection Failed

**Problem:** Aplikasi tidak bisa connect ke Redis

**Solution:**
```bash
# Update Redis configuration di .env
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=redissecret

# Update aplikasi untuk menggunakan password
# Laravel: config/database.php
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    'default' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
    ],
],
```

## 📝 Perubahan Penting

### 1. Multi-Stage Builds

**Sebelum:**
- Single Dockerfile untuk semua environment
- Development tools included di production

**Sekarang:**
- Separate stages untuk development dan production
- Production image lebih kecil dan aman
- Build target: `development` atau `production`

### 2. Configuration Separation

**Sebelum:**
- Satu config untuk dev dan prod
- Menggunakan conditional atau manual changes

**Sekarang:**
- Separate configs untuk dev dan prod
- Optimized untuk masing-masing environment
- Tidak perlu manual changes

### 3. Security Improvements

**Ditambahkan:**
- Health checks untuk semua services
- Rate limiting di Nginx
- Read-only filesystem di production
- Security headers lengkap
- Redis authentication
- Resource limits
- Disabled dangerous functions

### 4. Framework Separation

**Sebelum:**
- Satu config untuk semua framework
- Menggunakan `APP_FRAMEWORK` variable

**Sekarang:**
- Separate configs untuk Laravel dan CodeIgniter 3
- Optimized untuk masing-masing framework
- Lebih maintainable

## 🎯 Post-Migration Checklist

- [ ] Semua containers running dengan status "healthy"
- [ ] Aplikasi accessible di browser
- [ ] Database connection working
- [ ] Redis connection working
- [ ] Logs tidak menunjukkan errors
- [ ] File permissions correct
- [ ] Development workflow berfungsi (hot reload, dll)
- [ ] Backup strategy implemented
- [ ] Documentation updated

## 🚀 Next Steps

### Untuk Development
1. Setup Git hooks untuk linting
2. Configure IDE untuk Docker development
3. Setup debugging dengan Xdebug
4. Configure CI/CD pipeline

### Untuk Production
1. Review SECURITY.md
2. Setup HTTPS dengan reverse proxy
3. Configure monitoring dan alerting
4. Setup automated backups
5. Configure log aggregation
6. Implement deployment strategy (blue-green, canary, dll)

## 📚 Additional Resources

- [README.md](README.new.md) - Dokumentasi lengkap
- [SECURITY.md](SECURITY.md) - Security best practices
- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)

## 💡 Tips

### Rollback ke Konfigurasi Lama

Jika ada masalah dan perlu rollback:

```bash
# Stop new containers
docker compose -f docker-compose.laravel.dev.yml down

# Restore .env
cp .env.backup .env

# Start old containers
docker compose -f docker-compose.dev.yml up -d

# Restore database jika perlu
docker compose -f docker-compose.dev.yml exec -T db \
  mysql -u root -p laravel < backup.sql
```

### Gradual Migration

Anda bisa migrate secara bertahap:

1. **Phase 1:** Gunakan new structure untuk development
2. **Phase 2:** Test thoroughly
3. **Phase 3:** Migrate production setelah confident

### Keep Both Configurations

Selama masa transisi, Anda bisa keep both:

```bash
# Old configuration
docker-compose.dev.yml (port 8080)

# New configuration  
docker-compose.laravel.dev.yml (port 8081)

# Update .env untuk new config
APP_PORT=8081
```

## 🤝 Need Help?

Jika mengalami kesulitan dalam migrasi:

1. Check logs: `make -f Makefile.laravel logs`
2. Verify configuration: Review .env file
3. Check documentation: README.md dan SECURITY.md
4. Search for similar issues
5. Create an issue dengan detail lengkap
