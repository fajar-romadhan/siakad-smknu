---
name: arenhost-deployment
description: Comprehensive knowledge, pitfalls, and step-by-step automated deployment workflows for hosting PHP applications (Native PHP & Laravel) on ArenHost cPanel shared hosting, including Git Version Control integration for zero-zip continuous updates.
---

# ArenHost cPanel Deployment & Git CI/CD Automation Skill

Skill ini memuat seluruh panduan praktis, aturan keamanan, penanganan kendala server LiteSpeed/cPanel ArenHost, serta arsitektur deployment otomatis menggunakan **Git™ Version Control & GitHub** tanpa perlu upload file ZIP berulang kali saat ada revisi.

---

## 1. PROFIL SERVER & LINGKUNGAN ARENHOST

* **Web Server**: LiteSpeed Web Server / CloudLinux
* **Control Panel**: cPanel Jupiter Theme (Port `2083` atau `mail.tarsius.kencang.com`)
* **PHP Support**: EA-PHP 7.4 / 8.0 / 8.1 / 8.2 / 8.3
* **Database**: MariaDB / MySQL 5.7+ (`/usr/bin/mariadb`)
* **Default Web Root**: `/home/<cpanel_user>/public_html`
* **SSH / Terminal**: Didukung (Port standar 22 / custom cPanel key)

---

## 2. ATURAN KRITIS & JEBAKAN NYATA ARENHOST

### ⚠️ A. Password Database Tanpa Karakter Escape (`\`)
* **Jebakan**: cPanel Password Generator terkadang menghasilkan karakter backslash (`\`). Karakter ini menyebabkan koneksi PDO / MySQLi / `.env` gagal dengan pesan `Access Denied for user`.
* **Solusi**: Gunakan hanya kombinasi huruf besar-kecil, angka, dan simbol sederhana seperti `@`, `!`, `#`, `_`, `$`.

### ⚠️ B. Hak Akses User Database (Privileges)
* **Jebakan**: Membuat Database dan User baru tidak otomatis menghubungkan keduanya. Web akan langsung throw `SQLSTATE[HY000] [1045]`.
* **Solusi**: Masuk ke menu **MySQL Databases** $\rightarrow$ **Add User to Database** $\rightarrow$ Pilih User & DB $\rightarrow$ Centang **ALL PRIVILEGES** $\rightarrow$ **Make Changes**.

### ⚠️ C. Struktur `.htaccess` untuk PHP Native MVC
* **Jebakan**: Jika seluruh project ditaruh di `public_html` dan entry point ada di `public_html/public/`, request dari root domain harus diteruskan ke `public/` secara transparan tanpa mengubah URL di browser.
* **Solusi Standar `.htaccess` Root**:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

Options -Indexes

<FilesMatch "\.(sql|md|log|env|json|lock)$">
    Require all denied
</FilesMatch>
```

### ⚠️ D. DNS Propagasi & SSL Let's Encrypt
* **Jebakan**: Domain baru (khususnya `.my.id` / `.id`) memerlukan propagasi DNS global (15–30 menit). Jika AutoSSL dijalankan saat propagasi belum selesai, sistem akan memasang sertifikat *Self-Signed* (menyebabkan warning merah di browser HP).
* **Solusi**: Cek DNS di `dnschecker.org`. Setelah hijau, buka cPanel $\rightarrow$ **SSL/TLS Certificates** $\rightarrow$ **Wizard** $\rightarrow$ centang domain $\rightarrow$ pilih **Let's Encrypt Certificate** $\rightarrow$ **Issue & Install**.

---

## 3. INTEGRASI GITHUB KE CPANEL (ZERO-ZIP REVISION WORKFLOW)

Dengan workflow ini, setiap ada perbaikan kode / revisi fitur, pengembang **TIDAK PERLU** mengompres file ZIP dan mengupload ulang lewat File Manager.

```
[Local Code / IDE]  -- git push -->  [GitHub Repository]  -- git pull / deploy -->  [cPanel Live Hosting]
```

### Tahap 1: Hubungkan Kunci SSH (Deploy Key)
1. Di cPanel: Masuk menu **SSH Access** $\rightarrow$ **Manage SSH Keys** $\rightarrow$ **Generate a New Key** (Nama: `id_rsa`).
2. Masuk ke **Public Keys** $\rightarrow$ Klik **View/Download** pada `id_rsa.pub` $\rightarrow$ Salin seluruh teks key (`ssh-rsa AAAA...`).
3. Di GitHub: Buka Repository $\rightarrow$ **Settings** $\rightarrow$ **Deploy keys** $\rightarrow$ **Add deploy key**:
   * Title: `cPanel ArenHost`
   * Key: Paste teks public key tadi
   * Centang: *Allow write access* $\rightarrow$ Klik **Add key**.

### Tahap 2: Setup Git Version Control di cPanel
1. Di cPanel: Masuk menu **Git™ Version Control** $\rightarrow$ Klik tombol biru **Create**.
2. Masukkan data:
   * **Clone URL**: `git@github.com:fajar-romadhan/siakad-smknu.git` *(Gunakan format SSH)*
   * **Repository Path**: `repositories/siakad-smknu` *(atau direktori staging)*
   * **Repository Name**: `siakad-smknu`
3. Klik **Create**.

### Tahap 3: Otomatisasi Deploy dengan `.cpanel.yml`
Buat file bernama `.cpanel.yml` di root repositori:
```yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/siakads4/public_html/
    - /bin/cp -R config $DEPLOYPATH
    - /bin/cp -R controllers $DEPLOYPATH
    - /bin/cp -R core $DEPLOYPATH
    - /bin/cp -R data $DEPLOYPATH
    - /bin/cp -R models $DEPLOYPATH
    - /bin/cp -R public $DEPLOYPATH
    - /bin/cp -R views $DEPLOYPATH
    - /bin/cp .htaccess $DEPLOYPATH
    - /bin/cp index.php $DEPLOYPATH
```

---

## 4. WORKFLOW REVISI KODINGAN (SUPER CEPAT)

Saat Anda atau tim melakukan perubahan kodingan di masa mendatang:

### Langkah di Komputer (Lokal):
```bash
git add .
git commit -m "fix: deskripsi perbaikan fitur"
git push origin main
```

### Langkah di cPanel (Hosting):
1. Buka cPanel $\rightarrow$ masuk menu **Git™ Version Control**.
2. Klik tombol **Manage** di samping repositori.
3. Masuk ke tab **Pull or Deploy** $\rightarrow$ klik tombol **`Update from Remote`** lalu klik **`Deploy HEAD Commit`**.
4. Website langsung ter-update secara instan dalam 2 detik! ⚡

---

## 5. CHECKLIST VERIFIKASI SETELAH UPDATE

- [ ] Status HTTP `200 OK`
- [ ] Gembok SSL aman (`https://`)
- [ ] Session login admin, guru, siswa berfungsi
- [ ] Query database & filter berjalan lancar
- [ ] File `.gitignore` melindungi file kredensial dan dump lokal
