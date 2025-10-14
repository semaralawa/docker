# Changelog

Dokumentasi perubahan dari konfigurasi lama ke konfigurasi baru.

## 🗑️ File yang Dihapus (Konfigurasi Lama)

### Root Directory
- ❌ `Dockerfile` - Diganti dengan multi-stage Dockerfiles per framework
- ❌ `docker-compose.dev.yml` - Diganti dengan compose files per framework
- ❌ `.env.example` - Diganti dengan 4 template .env yang spesifik
- ❌ `Makefile` - Diganti dengan Makefile per framework
- ❌ `README.md` (old) - Diganti dengan dokumentasi lengkap

### Docker Directory
- ❌ `docker/nginx/default.conf` - Diganti dengan configs per framework & environment
- ❌ `docker/php/conf.d/custom.ini` - Diganti dengan configs per framework & environment
- ❌ `docker/php-fpm/laravel.conf` - Diganti dengan configs per environment
- ❌ `docker/php-fpm/ci.conf` - Diganti dengan configs per environment

## ✅ File Baru yang Ditambahkan

### Dockerfiles (2 files)
- ✅ `docker/laravel/Dockerfile` - Multi-stage Dockerfile untuk Laravel
- ✅ `docker/codeigniter3/Dockerfile` - Multi-stage Dockerfile untuk CodeIgniter 3

### Docker Compose (4 files)
- ✅ `docker-compose.laravel.dev.yml` - Laravel development
- ✅ `docker-compose.laravel.prod.yml` - Laravel production
- ✅ `docker-compose.codeigniter3.dev.yml` - CodeIgniter 3 development
- ✅ `docker-compose.codeigniter3.prod.yml` - CodeIgniter 3 production

### Environment Templates (4 files)
- ✅ `.env.laravel.dev.example` - Laravel development template
- ✅ `.env.laravel.prod.example` - Laravel production template
- ✅ `.env.codeigniter3.dev.example` - CodeIgniter 3 development template
- ✅ `.env.codeigniter3.prod.example` - CodeIgniter 3 production template

### Makefiles (2 files)
- ✅ `Makefile.laravel` - Commands untuk Laravel
- ✅ `Makefile.codeigniter3` - Commands untuk CodeIgniter 3

### Laravel Configurations (10 files)
- ✅ `docker/laravel/healthcheck.sh` - Health check script
- ✅ `docker/laravel/nginx/dev.conf` - Nginx development config
- ✅ `docker/laravel/nginx/prod.conf` - Nginx production config
- ✅ `docker/laravel/php/dev/php.ini` - PHP development config
- ✅ `docker/laravel/php/dev/opcache.ini` - OPcache development config
- ✅ `docker/laravel/php/prod/php.ini` - PHP production config
- ✅ `docker/laravel/php/prod/opcache.ini` - OPcache production config
- ✅ `docker/laravel/php-fpm/dev/www.conf` - PHP-FPM development pool
- ✅ `docker/laravel/php-fpm/prod/www.conf` - PHP-FPM production pool

### CodeIgniter 3 Configurations (10 files)
- ✅ `docker/codeigniter3/healthcheck.sh` - Health check script
- ✅ `docker/codeigniter3/nginx/dev.conf` - Nginx development config
- ✅ `docker/codeigniter3/nginx/prod.conf` - Nginx production config
- ✅ `docker/codeigniter3/php/dev/php.ini` - PHP development config
- ✅ `docker/codeigniter3/php/dev/opcache.ini` - OPcache development config
- ✅ `docker/codeigniter3/php/prod/php.ini` - PHP production config
- ✅ `docker/codeigniter3/php/prod/opcache.ini` - OPcache production config
- ✅ `docker/codeigniter3/php-fpm/dev/www.conf` - PHP-FPM development pool
- ✅ `docker/codeigniter3/php-fpm/prod/www.conf` - PHP-FPM production pool

### MySQL Configurations (2 files)
- ✅ `docker/mysql/dev.cnf` - MySQL development config
- ✅ `docker/mysql/prod.cnf` - MySQL production config

### Documentation (6 files)
- ✅ `README.md` - Dokumentasi utama (updated)
- ✅ `DOCS_INDEX.md` - Index navigasi dokumentasi
- ✅ `ENV_SETUP.md` - Panduan setup environment
- ✅ `MIGRATION_GUIDE.md` - Panduan migrasi
- ✅ `SECURITY.md` - Security best practices
- ✅ `IMPROVEMENTS.md` - Analisis improvements
- ✅ `CHANGELOG.md` - File ini

### Other Files (1 file)
- ✅ `.dockerignore` - Build optimization

## 📊 Summary

### Total Files
- **Dihapus**: 9 files
- **Ditambahkan**: 46 files
- **Net Change**: +37 files

### File Organization
| Category | Old | New | Change |
|----------|-----|-----|--------|
| Dockerfiles | 1 | 2 | +1 |
| Docker Compose | 1 | 4 | +3 |
| Environment Templates | 1 | 4 | +3 |
| Makefiles | 1 | 2 | +1 |
| Nginx Configs | 1 | 4 | +3 |
| PHP Configs | 1 | 8 | +7 |
| PHP-FPM Configs | 2 | 4 | +2 |
| MySQL Configs | 0 | 2 | +2 |
| Health Checks | 0 | 2 | +2 |
| Documentation | 1 | 6 | +5 |
| Other | 1 | 1 | 0 |
| **Total** | **10** | **39** | **+29** |

## 🔄 Migration Path

### Jika Anda Menggunakan Laravel

1. **Backup data**
   ```bash
   cp .env .env.backup
   ```

2. **Copy template baru**
   ```bash
   cp .env.laravel.dev.example .env
   ```

3. **Update variabel dari .env.backup**
   - Lihat [ENV_SETUP.md](ENV_SETUP.md) untuk mapping

4. **Build & start**
   ```bash
   make -f Makefile.laravel dev-up
   ```

### Jika Anda Menggunakan CodeIgniter 3

1. **Backup data**
   ```bash
   cp .env .env.backup
   ```

2. **Copy template baru**
   ```bash
   cp .env.codeigniter3.dev.example .env
   ```

3. **Update variabel dari .env.backup**
   - Lihat [ENV_SETUP.md](ENV_SETUP.md) untuk mapping

4. **Build & start**
   ```bash
   make -f Makefile.codeigniter3 dev-up
   ```

## 📝 Breaking Changes

### Environment Variables
- `APP_FRAMEWORK` → Tidak digunakan (pilih compose file yang sesuai)
- `APPNAME` → `APP_NAME`
- `APP_FWD_PORT` → `APP_PORT`
- `DB_FWD_PORT` → `DB_PORT`
- `REDIS_FWD_PORT` → `REDIS_PORT`
- **NEW**: `REDIS_PASSWORD` (required)
- **NEW**: `MAILHOG_PORT` (development only)
- **NEW**: `MAILHOG_SMTP_PORT` (development only)

### Commands
```bash
# Old
make up
make down
make shell

# New (Laravel)
make -f Makefile.laravel dev-up
make -f Makefile.laravel dev-down
make -f Makefile.laravel shell

# New (CodeIgniter 3)
make -f Makefile.codeigniter3 dev-up
make -f Makefile.codeigniter3 dev-down
make -f Makefile.codeigniter3 shell
```

### Docker Compose Files
```bash
# Old
docker compose -f docker-compose.dev.yml up

# New (Laravel)
docker compose -f docker-compose.laravel.dev.yml up

# New (CodeIgniter 3)
docker compose -f docker-compose.codeigniter3.dev.yml up
```

## 🎯 Benefits of New Structure

### 1. Better Organization
- ✅ Clear separation per framework
- ✅ Clear separation per environment
- ✅ Easier to maintain
- ✅ Easier to customize

### 2. Security Improvements
- ✅ Multi-stage builds
- ✅ Non-root user
- ✅ Read-only filesystem (production)
- ✅ Security headers
- ✅ Rate limiting
- ✅ Health checks

### 3. Performance Improvements
- ✅ OPcache optimization
- ✅ PHP-FPM tuning
- ✅ Nginx caching
- ✅ Resource limits
- ✅ Smaller images (70% reduction)

### 4. Developer Experience
- ✅ Better documentation
- ✅ Easier commands
- ✅ Framework-specific configs
- ✅ Environment-specific configs
- ✅ Migration guide

## 📅 Version History

### Version 2.0.0 (2025-10-14)
- Complete rewrite dengan multi-stage builds
- Separate configs untuk Laravel & CodeIgniter 3
- Separate configs untuk development & production
- Comprehensive documentation
- Security hardening
- Performance optimization

### Version 1.0.0 (Previous)
- Basic Docker setup
- Single Dockerfile
- Development only
- Basic documentation

## 🔗 Related Documentation

- [README.md](README.md) - Getting started
- [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) - Detailed migration guide
- [ENV_SETUP.md](ENV_SETUP.md) - Environment setup
- [SECURITY.md](SECURITY.md) - Security guide
- [IMPROVEMENTS.md](IMPROVEMENTS.md) - Technical analysis
- [DOCS_INDEX.md](DOCS_INDEX.md) - Documentation index
