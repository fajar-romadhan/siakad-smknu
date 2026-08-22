# SIAKAD SMK NU Muara Sugihan

Sistem Informasi Akademik berbasis Native PHP untuk SMK NU Muara Sugihan.

## Fitur
- **3 Role**: Admin (Desktop), Guru (Mobile), Siswa (Mobile)
- **Modul Admin**: Dashboard, Data Guru, Data Siswa, Mata Pelajaran, Kelas, Jadwal, Tahun Ajaran, Absensi, Pengumuman, Kelola Pengguna, Profil
- **Modul Guru**: Dashboard, Absensi Guru (self), Absensi Siswa, Input Nilai, Catatan, Jadwal, Pengumuman, Profil
- **Modul Siswa**: Dashboard, Nilai, Catatan, Jadwal, Pengumuman, Profil

## Instalasi

1. **Copy folder** `siakad-smknu` ke dalam `htdocs` (XAMPP) atau `www` (Laragon)
2. **Import database**:
   - Untuk instalasi baru: Buka phpMyAdmin → Import → pilih file `database/siakad_smknu.sql`
   - Untuk sinkronisasi database lama: Buka phpMyAdmin → pilih database `siakad_smknu` → Import → pilih file `database/migrasi_lengkap_sinkronisasi.sql`
3. **Akses**: Buka browser → `http://localhost/siakad-smknu/public/`

## Login Default
- **Admin**: username `admin` / password `admin123`
- **Guru**: username = NIS guru / password = namaguru+tahunmasuk (contoh: `budisantoso2024`)
- **Siswa**: username = NISN siswa / password = namasiswa+tahunmasuk

## Konfigurasi Database
Edit file `config/database.php` jika perlu ubah kredensial:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'siakad_smknu');
define('DB_USER', 'root');
define('DB_PASS', '');
```

## Struktur Folder
```
siakad-smknu/
├── config/         # Konfigurasi database & app
├── core/           # Auth & base Model
├── controllers/    # Logic controller
├── models/         # Data model
├── views/          # Tampilan (admin/guru/siswa)
├── public/         # Entry point, CSS, JS, images
│   ├── css/
│   ├── js/
│   ├── img/
│   └── index.php   # Main router
└── database/       # SQL schema
```

## Teknologi
- PHP 7.4+ (Native, tanpa framework)
- MySQL 5.7+ / MariaDB
- HTML5, CSS3, JavaScript (Vanilla)
- MVC Architecture
- PDO untuk database
