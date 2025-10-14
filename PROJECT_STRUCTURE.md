# Project Structure

Struktur lengkap project Docker PHP setelah reorganisasi.

## 📁 Directory Tree

```
docker-3/
│
├── 📄 README.md                              # Dokumentasi utama - START HERE
├── 📄 DOCS_INDEX.md                          # Index navigasi dokumentasi
├── 📄 ENV_SETUP.md                           # Panduan setup environment
├── 📄 MIGRATION_GUIDE.md                     # Panduan migrasi dari config lama
├── 📄 SECURITY.md                            # Security best practices
├── 📄 IMPROVEMENTS.md                        # Analisis improvements
├── 📄 CHANGELOG.md                           # Changelog & breaking changes
├── 📄 PROJECT_STRUCTURE.md                   # File ini
│
├── 🔧 .dockerignore                          # Docker build optimization
├── 🔧 .gitignore                             # Git ignore rules
│
├── 🔐 .env                                   # Your environment file (gitignored)
├── 📋 .env.laravel.dev.example               # Laravel development template
├── 📋 .env.laravel.prod.example              # Laravel production template
├── 📋 .env.codeigniter3.dev.example          # CodeIgniter 3 development template
├── 📋 .env.codeigniter3.prod.example         # CodeIgniter 3 production template
│
├── ⚙️ Makefile.laravel                       # Laravel commands
├── ⚙️ Makefile.codeigniter3                  # CodeIgniter 3 commands
│
├── 🐳 docker-compose.laravel.dev.yml         # Laravel development compose
├── 🐳 docker-compose.laravel.prod.yml        # Laravel production compose
├── 🐳 docker-compose.codeigniter3.dev.yml    # CodeIgniter 3 development compose
├── 🐳 docker-compose.codeigniter3.prod.yml   # CodeIgniter 3 production compose
│
└── 📂 docker/
    │
    ├── 📂 laravel/                           # Laravel configurations
    │   ├── 🐳 Dockerfile                     # Multi-stage Dockerfile
    │   ├── 🔍 healthcheck.sh                 # Health check script
    │   │
    │   ├── 📂 nginx/                         # Nginx configurations
    │   │   ├── dev.conf                      # Development config
    │   │   └── prod.conf                     # Production config
    │   │
    │   ├── 📂 php/                           # PHP configurations
    │   │   ├── 📂 dev/                       # Development
    │   │   │   ├── php.ini                   # PHP settings
    │   │   │   └── opcache.ini               # OPcache settings
    │   │   └── 📂 prod/                      # Production
    │   │       ├── php.ini                   # PHP settings
    │   │       └── opcache.ini               # OPcache settings
    │   │
    │   └── 📂 php-fpm/                       # PHP-FPM configurations
    │       ├── 📂 dev/                       # Development
    │       │   └── www.conf                  # FPM pool config
    │       └── 📂 prod/                      # Production
    │           └── www.conf                  # FPM pool config
    │
    ├── 📂 codeigniter3/                      # CodeIgniter 3 configurations
    │   ├── 🐳 Dockerfile                     # Multi-stage Dockerfile
    │   ├── 🔍 healthcheck.sh                 # Health check script
    │   │
    │   ├── 📂 nginx/                         # Nginx configurations
    │   │   ├── dev.conf                      # Development config
    │   │   └── prod.conf                     # Production config
    │   │
    │   ├── 📂 php/                           # PHP configurations
    │   │   ├── 📂 dev/                       # Development
    │   │   │   ├── php.ini                   # PHP settings
    │   │   │   └── opcache.ini               # OPcache settings
    │   │   └── 📂 prod/                      # Production
    │   │       ├── php.ini                   # PHP settings
    │   │       └── opcache.ini               # OPcache settings
    │   │
    │   └── 📂 php-fpm/                       # PHP-FPM configurations
    │       ├── 📂 dev/                       # Development
    │       │   └── www.conf                  # FPM pool config
    │       └── 📂 prod/                      # Production
    │           └── www.conf                  # FPM pool config
    │
    └── 📂 mysql/                             # MySQL configurations
        ├── dev.cnf                           # Development config
        └── prod.cnf                          # Production config
```

## 📊 File Statistics

### By Category

| Category | Count | Description |
|----------|-------|-------------|
| 📄 Documentation | 7 | Markdown documentation files |
| 🐳 Docker Compose | 4 | Compose files for different frameworks & environments |
| 🐳 Dockerfiles | 2 | Multi-stage Dockerfiles (Laravel & CI3) |
| 📋 Environment Templates | 4 | .env templates for different setups |
| ⚙️ Makefiles | 2 | Command shortcuts for each framework |
| 🌐 Nginx Configs | 4 | Web server configurations |
| 🐘 PHP Configs | 8 | PHP settings (php.ini) |
| ⚡ OPcache Configs | 8 | OPcache optimization settings |
| 🔧 PHP-FPM Configs | 4 | PHP-FPM pool configurations |
| 🗄️ MySQL Configs | 2 | Database configurations |
| 🔍 Health Checks | 2 | Container health check scripts |
| 🔧 Other | 2 | .dockerignore, .gitignore |
| **Total** | **49** | **Total configuration files** |

### By Framework

| Framework | Files | Description |
|-----------|-------|-------------|
| Laravel | 10 | Dockerfile + configs |
| CodeIgniter 3 | 10 | Dockerfile + configs |
| Shared | 6 | MySQL configs + documentation |
| **Total** | **26** | **Framework-specific files** |

### By Environment

| Environment | Files | Description |
|-------------|-------|-------------|
| Development | 14 | Dev-specific configurations |
| Production | 14 | Prod-specific configurations |
| Both | 21 | Shared files |
| **Total** | **49** | **All configuration files** |

## 🎯 File Purposes

### Root Level Files

#### Documentation
- **README.md** - Main documentation, quick start guide
- **DOCS_INDEX.md** - Navigation index for all docs
- **ENV_SETUP.md** - Environment variable setup guide
- **MIGRATION_GUIDE.md** - Migration from old config
- **SECURITY.md** - Security best practices & checklist
- **IMPROVEMENTS.md** - Technical analysis & benchmarks
- **CHANGELOG.md** - Version history & breaking changes
- **PROJECT_STRUCTURE.md** - This file

#### Configuration
- **.dockerignore** - Exclude files from Docker build
- **.gitignore** - Exclude files from Git
- **.env** - Your environment variables (gitignored)

#### Templates
- **.env.laravel.dev.example** - Laravel dev template
- **.env.laravel.prod.example** - Laravel prod template
- **.env.codeigniter3.dev.example** - CI3 dev template
- **.env.codeigniter3.prod.example** - CI3 prod template

#### Commands
- **Makefile.laravel** - Laravel shortcuts
- **Makefile.codeigniter3** - CodeIgniter 3 shortcuts

#### Orchestration
- **docker-compose.laravel.dev.yml** - Laravel dev stack
- **docker-compose.laravel.prod.yml** - Laravel prod stack
- **docker-compose.codeigniter3.dev.yml** - CI3 dev stack
- **docker-compose.codeigniter3.prod.yml** - CI3 prod stack

### Docker Directory

#### Laravel Directory (`docker/laravel/`)
- **Dockerfile** - Multi-stage build (dev + prod)
- **healthcheck.sh** - Container health monitoring
- **nginx/dev.conf** - Web server for development
- **nginx/prod.conf** - Web server for production (optimized)
- **php/dev/php.ini** - PHP settings for development
- **php/dev/opcache.ini** - OPcache disabled for dev
- **php/prod/php.ini** - PHP settings for production (hardened)
- **php/prod/opcache.ini** - OPcache fully optimized
- **php-fpm/dev/www.conf** - FPM pool for development
- **php-fpm/prod/www.conf** - FPM pool for production (tuned)

#### CodeIgniter 3 Directory (`docker/codeigniter3/`)
- Same structure as Laravel
- CI3-specific configurations
- Framework-specific optimizations

#### MySQL Directory (`docker/mysql/`)
- **dev.cnf** - MySQL for development (verbose logging)
- **prod.cnf** - MySQL for production (optimized)

## 🔍 Configuration Hierarchy

### Development Environment

```
Laravel Development:
├── docker-compose.laravel.dev.yml
├── .env (from .env.laravel.dev.example)
├── Makefile.laravel
└── docker/laravel/
    ├── Dockerfile (target: development)
    ├── nginx/dev.conf
    ├── php/dev/php.ini
    ├── php/dev/opcache.ini
    └── php-fpm/dev/www.conf

CodeIgniter 3 Development:
├── docker-compose.codeigniter3.dev.yml
├── .env (from .env.codeigniter3.dev.example)
├── Makefile.codeigniter3
└── docker/codeigniter3/
    ├── Dockerfile (target: development)
    ├── nginx/dev.conf
    ├── php/dev/php.ini
    ├── php/dev/opcache.ini
    └── php-fpm/dev/www.conf
```

### Production Environment

```
Laravel Production:
├── docker-compose.laravel.prod.yml
├── .env (from .env.laravel.prod.example)
├── Makefile.laravel
└── docker/laravel/
    ├── Dockerfile (target: production)
    ├── nginx/prod.conf
    ├── php/prod/php.ini
    ├── php/prod/opcache.ini
    └── php-fpm/prod/www.conf

CodeIgniter 3 Production:
├── docker-compose.codeigniter3.prod.yml
├── .env (from .env.codeigniter3.prod.example)
├── Makefile.codeigniter3
└── docker/codeigniter3/
    ├── Dockerfile (target: production)
    ├── nginx/prod.conf
    ├── php/prod/php.ini
    ├── php/prod/opcache.ini
    └── php-fpm/prod/www.conf
```

## 📝 File Naming Conventions

### Patterns
- **Framework-specific**: `{framework}/` directory
- **Environment-specific**: `dev/` or `prod/` subdirectory
- **Template files**: `.example` suffix
- **Documentation**: `.md` extension
- **Configuration**: `.conf`, `.ini`, `.cnf` extensions
- **Compose files**: `docker-compose.{framework}.{env}.yml`
- **Makefiles**: `Makefile.{framework}`

### Examples
```
✅ Good:
- docker-compose.laravel.dev.yml
- .env.laravel.prod.example
- docker/laravel/php/dev/php.ini
- Makefile.laravel

❌ Avoid:
- docker-compose.yml (ambiguous)
- .env (should be .env.example)
- config.ini (not specific)
```

## 🚀 Quick Reference

### For Laravel Developers
```bash
# Files you'll use most:
- .env.laravel.dev.example → .env
- docker-compose.laravel.dev.yml
- Makefile.laravel
- README.md
```

### For CodeIgniter 3 Developers
```bash
# Files you'll use most:
- .env.codeigniter3.dev.example → .env
- docker-compose.codeigniter3.dev.yml
- Makefile.codeigniter3
- README.md
```

### For DevOps/Production
```bash
# Files you'll need:
- .env.{framework}.prod.example → .env
- docker-compose.{framework}.prod.yml
- SECURITY.md
- docker/{framework}/Dockerfile (target: production)
```

### For Migration
```bash
# Files to read:
- MIGRATION_GUIDE.md
- ENV_SETUP.md
- CHANGELOG.md
```

## 📚 Related Documentation

- [README.md](README.md) - Start here
- [DOCS_INDEX.md](DOCS_INDEX.md) - Documentation navigation
- [CHANGELOG.md](CHANGELOG.md) - What changed
- [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) - How to migrate
