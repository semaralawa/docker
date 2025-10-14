# Environment Setup Guide

Panduan untuk setup environment variables.

## 📝 File .env Anda Saat Ini

File `.env` Anda saat ini menggunakan format lama. Silakan update sesuai framework yang Anda gunakan.

## 🔄 Cara Update .env

### Untuk Laravel

```bash
# Backup .env lama
cp .env .env.backup

# Copy template baru
cp .env.laravel.dev.example .env

# Edit dan sesuaikan dengan kebutuhan Anda
nano .env  # atau gunakan editor favorit Anda
```

### Untuk CodeIgniter 3

```bash
# Backup .env lama
cp .env .env.backup

# Copy template baru
cp .env.codeigniter3.dev.example .env

# Edit dan sesuaikan dengan kebutuhan Anda
nano .env  # atau gunakan editor favorit Anda
```

## 📋 Mapping Variabel Lama ke Baru

| Variabel Lama | Variabel Baru | Keterangan |
|---------------|---------------|------------|
| `APP_FRAMEWORK` | Tidak digunakan | Pilih compose file yang sesuai |
| `APPNAME` | `APP_NAME` | Nama aplikasi |
| `APP_FWD_PORT` | `APP_PORT` | Port aplikasi |
| `DB_FWD_PORT` | `DB_PORT` | Port database |
| `REDIS_FWD_PORT` | `REDIS_PORT` | Port Redis |
| - | `REDIS_PASSWORD` | **BARU**: Password Redis |
| - | `MAILHOG_PORT` | **BARU**: Port Mailhog (dev only) |
| - | `MAILHOG_SMTP_PORT` | **BARU**: SMTP port Mailhog (dev only) |

## ✅ Verifikasi Setup

Setelah update `.env`, verifikasi dengan:

```bash
# Untuk Laravel
make -f Makefile.laravel dev-up

# Untuk CodeIgniter 3
make -f Makefile.codeigniter3 dev-up
```

## ⚠️ Important Notes

1. **Jangan commit file .env** - File ini sudah ada di `.gitignore`
2. **Gunakan strong passwords** untuk production
3. **Backup .env lama** sebelum update
4. **Review semua variabel** sebelum start containers

## 🔒 Production Environment

Untuk production, gunakan template production:

```bash
# Laravel Production
cp .env.laravel.prod.example .env

# CodeIgniter 3 Production
cp .env.codeigniter3.prod.example .env
```

**PENTING**: Ganti semua password dengan strong passwords!
