# 📝 PROGRESS LOG — SIAKAD SMK NU MUARA SUGIHAN

Dokumen riwayat seluruh aktivitas pembaruan kode, integrasi repositori GitHub, dan deployment live server di hosting ArenHost.

---

## 📌 Sesi Kerja: 21 September 2026 (22:20 – 23:02 WIB)
* **Author / Developer**: FJR (`fajar-romadhan` / `atangray6@gmail.com`)
* **Domain Target**: [https://siakad-smknu.my.id/](https://siakad-smknu.my.id/)
* **Akun Hosting**: ArenHost cPanel (`siakads4` / IP: `195.88.211.130`)
* **Tujuan**: Memproses paket pembaruan dari klien (`V2-AFTER REVISI.zip`), menyelaraskan kode lokal, mengamankan konfigurasi hosting, push ke GitHub, dan deploy ke live server.

### 1. 🔍 Analisis & Temuan Paket Revisi V2
* Paket arsip `V2-AFTER REVISI.zip` (37.7 MB) memuat:
  1. `siakad-smknu.zip` (37.5 MB) — Source code aplikasi revisi terbaru.
  2. `siakad_smknu.sql` (1.49 MB, tanggal pembuatan 9 September 2026) — Dump database dengan 15.000+ baris data riil.
* **Fitur & Perubahan Baru**:
  - **Modul Buka Kunci Nilai**: Penambahan method `bukaKunci()` pada `controllers/NilaiController.php` dan halaman admin baru `views/admin/nilai/buka-kunci.php` serta integrasi menu 🔓 di `views/components/sidebar.php`.
  - **Modul Presensi Siswa**: Hak akses rekap dibuka untuk role `guru` dan `kepala_sekolah`, ditambahkan filter `mapel_id` dan riwayat detail absensi perorangan siswa pada `controllers/AbsensiController.php`.
  - **Modul Kelas Baru**: `views/guru/kelas/index.php`, `views/guru/kelas/detail.php`, dan `views/kepsek/kelas-detail.php`.
  - **Aset Foto Profil Guru**: Penambahan 20 foto avatar guru di `public/uploads/guru/` dan folder master `foto profil guru/`.
  - **Helper**: Penambahan fungsi `format_kelas($nama)` di `config/app.php`.

### 2. 🛡️ Mitigasi Masalah & Proteksi Konfigurasi Server
* **Kredensial Database**: File `config/database.php` diproteksi tetap memakai kredensial produksi ArenHost (`siakads4_siakad`), menolak kredensial localhost XAMPP bawaan klien agar tidak memicu Error 500 / Database Connection Failed.
* **Rewrite Rule `.htaccess`**: Mempertahankan konfigurasi LiteSpeed root hosting agar tidak terjadi infinite loop redirect yang disebabkan oleh rule subfolder bawaan klien (`!^/siakad-smknu/public/`).
* **Permissions Task**: Menambahkan task `chmod -R 755 $DEPLOYPATH/public/uploads` pada `.cpanel.yml`.
* **Sintaks Database MariaDB**: Membersihkan pernyataan `CREATE DATABASE` / `USE` dan mengatasi escaping backtick PowerShell pada query `DROP TABLE IF EXISTS` agar dapat diimpor sempurna ke MariaDB.

### 3. 🚀 Riwayat Git & GitHub Commits ([fajar-romadhan/siakad-smknu](https://github.com/fajar-romadhan/siakad-smknu))
* `28c21b1`: `feat(v2): update modul buka-kunci nilai, presensi mapel, detail kelas, foto profil guru, dan otomasi deploy` (72 files changed, +2234, -440)
* `927516a`: `chore: add v2 database dump for server deployment`
* `f63f46b`: `fix(db): update siakad_smknu_v2.sql to real 1.49MB dump with clean drop table header`
* `09dd69f`: `fix(db): clean drop table syntax without backtick escaping`

### 4. 🌐 Eksekusi Deployment di Server Hosting (cPanel Terminal)
* Kloning repositori ke server: `~/repositories/siakad-smknu`.
* Sinkronisasi file aplikasi ke: `/home/siakads4/public_html/`.
* Izin direktori upload diset `755` pada `/home/siakads4/public_html/public/uploads`.
* Impor database `siakads4_siakad` sukses 100% menggunakan file `database/siakad_smknu_v2.sql`.

### 5. 🧪 Hasil Verifikasi Akhir (Semua Lolos / PASSED)
* [x] **HTTP Server Live**: `https://siakad-smknu.my.id/` $\rightarrow$ `HTTP 200 OK`.
* [x] **Aset Foto Profil Guru**: `https://siakad-smknu.my.id/uploads/guru/guru_1788162552_3199.png` $\rightarrow$ `HTTP 200 OK`.
* [x] **Autentikasi Admin**: Berhasil login (Redirect 302 ke dashboard).
* [x] **Fitur Buka Kunci Nilai**: Halaman `index.php?page=nilai` aktif normal.
* [x] **Paket Cadangan**: Dibuat `siakad-deploy-v2.zip` (28 MB) di root workspace.
