# IMPORTANT

pastikan edit config.php di bagian session:
```
$config['sess_save_path'] = sys_get_temp_dir();
```

# Aplikasi CRUD Kontak

Aplikasi CRUD sederhana menggunakan CodeIgniter 3 dengan fitur manajemen kontak.

## Fitur

- ✅ Create (Tambah kontak baru)
- ✅ Read (Lihat daftar kontak)
- ✅ Update (Edit kontak)
- ✅ Delete (Hapus kontak)
- ✅ Upload foto profil
- ✅ Desain responsif dengan Bootstrap 5

## Field Input

- **Nama** (required)
- **Email** (required, valid email)
- **Nomor Telepon** (required)
- **Foto** (optional, format: JPG, JPEG, PNG, GIF, max 2MB)

## Cara Menjalankan

### 1. Start Docker Containers

```bash
docker-compose -f docker-compose.dev.yml up -d
```

### 2. Jalankan Migrasi Database

Akses URL berikut di browser untuk membuat tabel database:

```
http://localhost:8080/migrate
```

Atau jalankan via command line:

```bash
docker exec -it test_ci_app php index.php migrate
```

### 3. Akses Aplikasi

Buka browser dan akses:

```
http://localhost:8080
```

## Struktur File

```
application/
├── controllers/
│   ├── Contacts.php      # Controller utama CRUD
│   └── Migrate.php       # Controller untuk migrasi
├── models/
│   └── Contact_model.php # Model untuk tabel contacts
├── views/
│   └── contacts/
│       ├── index.php     # Halaman daftar kontak
│       ├── create.php    # Form tambah kontak
│       └── edit.php      # Form edit kontak
└── migrations/
    └── 001_create_contacts_table.php  # Migrasi tabel contacts

uploads/                  # Direktori untuk menyimpan foto
```

## Database

**Tabel: contacts**

| Field          | Type         | Description           |
|----------------|--------------|------------------------|
| id             | INT(11)      | Primary key           |
| nama           | VARCHAR(255) | Nama kontak           |
| email          | VARCHAR(255) | Email kontak          |
| nomor_telepon  | VARCHAR(20)  | Nomor telepon         |
| foto           | VARCHAR(255) | Nama file foto        |
| created_at     | DATETIME     | Tanggal dibuat        |
| updated_at     | DATETIME     | Tanggal diupdate      |

## Teknologi

- **Framework**: CodeIgniter 3
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap 5 + Bootstrap Icons
- **Container**: Docker + Docker Compose
- **Web Server**: Nginx
- **PHP**: 7.4

## Catatan

- Pastikan direktori `uploads/` memiliki permission write (777 atau 755)
- File foto akan disimpan dengan nama terenkripsi untuk keamanan
- Foto lama akan otomatis terhapus saat update dengan foto baru
- Validasi email menggunakan built-in CodeIgniter form validation
