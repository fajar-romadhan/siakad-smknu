---
name: arenhost-deployment
description: Practical knowledge, pitfalls, and step-by-step automated deployment workflows for hosting PHP applications (Native PHP & Laravel) on ArenHost cPanel shared hosting.
---

# ArenHost Deployment Skill & Knowledge Base

## Profil Server ArenHost
- **OS / Web Server**: LiteSpeed Web Server / CloudLinux
- **Control Panel**: cPanel Jupiter Theme (`cpanel.domain` atau port `:2083`)
- **PHP Version Support**: EA-PHP 7.4 / 8.0 / 8.1 / 8.2 / 8.3
- **Database Engine**: MariaDB / MySQL 5.7+ (menggunakan `/usr/bin/mariadb`)
- **Document Root**: `/home/<user>/public_html`

---

## Aturan Kritis & Jebakan Nyata di ArenHost

### 1. Password MySQL Jangan Mengandung Backslash (`\`)
- **Masalah**: ArenHost cPanel password generator terkadang menghasilkan simbol `\` (backslash) yang menyebabkan string escape error di PHP / `.env` / PDO connection.
- **Solusi**: Gunakan kombinasi alfanumerik + simbol sederhana (contoh: `@`, `!`, `#`, `_`).

### 2. File & Directory Permissions
- Semua folder wajib memiliki permission `755` (`drwxr-xr-x`).
- Semua file PHP & statis wajib memiliki permission `644` (`-rw-r--r--`).
- Folder upload/session wajib writable `775` atau `755` oleh webserver user.

### 3. Konfigurasi .htaccess untuk Native PHP MVC
Jika file aplikasi ditaruh di `public_html` dan entry point ada di `public_html/public/`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

Options -Indexes

<FilesMatch "\.(sql|md|log|env|json|lock)$">
    Require all denied
</FilesMatch>
```

### 4. Setup Database di cPanel
1. Buat Database: `cPanel -> MySQL Databases -> Create New Database`
2. Buat User: `cPanel -> MySQL Databases -> Add New User`
3. Hubungkan User & Database: `Add User To Database` -> Centang **ALL PRIVILEGES** -> `Make Changes` (Langkah ini wajib, jika lupa web akan 500 error / Access Denied).

### 5. Propagasi DNS Domain `.my.id`
- Domain baru butuh waktu propagasi DNS (15 menit - 24 jam).
- Cek propagasi di: `https://dnschecker.org/#A/siakad-smknu.my.id`
- Jika belum terhubung dan ingin langsung cek, gunakan hosts file di Windows:
  `C:\Windows\System32\drivers\etc\hosts`
  `195.88.211.130  siakad-smknu.my.id`

---

## Panduan Deployment PHP Native (SIAKAD SMK NU)
1. **Upload File**: Upload zip ke `public_html/` lalu Extract.
2. **Import SQL**: Buka `phpMyAdmin` -> Pilih database -> Import `database/siakad_smknu.sql`.
3. **Update Config**: Edit `config/database.php` isi `DB_NAME`, `DB_USER`, `DB_PASS`.
4. **Verifikasi Akses**: Buka `http://siakad-smknu.my.id/`
