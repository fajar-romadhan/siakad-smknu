<?php $pageTitle = 'Tambah Data Guru'; ob_start(); ?>
<div class="card">
    <div class="card-header"><h3>Tambah Data Guru</h3></div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('index.php?page=guru&action=store') ?>" enctype="multipart/form-data">

            <h4 class="form-section-title">Foto Profil</h4>
            <div class="form-group" style="margin-bottom:20px;">
                <label>Foto Profil Guru <small>(JPG, PNG, WEBP, Maks. 3MB)</small></label>
                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>

            <h4 class="form-section-title">Identitas</h4>
            <div class="form-row">
                <div class="form-group"><label>Nama Lengkap *</label><input type="text" name="nama" class="form-control" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>NUPTK <small>(kosongkan jika tidak ada)</small></label><input type="text" name="nuptk" class="form-control"></div>
                <div class="form-group"><label>NIK</label><input type="text" name="nik" class="form-control"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>NIP <small>(kosongkan jika tidak ada)</small></label><input type="text" name="nip" class="form-control"></div>
                <div class="form-group"><label>Jenis Kelamin *</label><select name="jenis_kelamin" class="form-control" required><option value="Laki-Laki">Laki-Laki</option><option value="Perempuan">Perempuan</option></select></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control"></div>
                <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Agama</label><input type="text" name="agama" class="form-control"></div>
                <div class="form-group"><label>Gelar</label><input type="text" name="gelar" class="form-control" placeholder="Contoh: S.Pd., M.Kom."></div>
            </div>

            <h4 class="form-section-title">Kepegawaian</h4>
            <div class="form-row">
                <div class="form-group"><label>Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-control">
                        <option value="">- Pilih -</option>
                        <option value="PNS">PNS</option>
                        <option value="PPPK">PPPK</option>
                        <option value="GTY">GTY (Guru Tetap Yayasan)</option>
                        <option value="GTT">GTT (Guru Tidak Tetap)</option>
                        <option value="Honorer">Honorer</option>
                    </select>
                </div>
                <div class="form-group"><label>Jenis PTK</label>
                    <select name="jenis_ptk" class="form-control">
                        <option value="">- Pilih -</option>
                        <option value="Guru Mapel">Guru Mapel</option>
                        <option value="Guru Kelas">Guru Kelas</option>
                        <option value="Guru BK">Guru BK</option>
                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                        <option value="Tenaga Administrasi">Tenaga Administrasi</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Jenjang Pendidikan</label>
                    <select name="jenjang_pendidikan" class="form-control">
                        <option value="">- Pilih -</option>
                        <option value="SMA/SMK">SMA/SMK</option>
                        <option value="D1">D1</option><option value="D2">D2</option>
                        <option value="D3">D3</option><option value="D4">D4</option>
                        <option value="S1">S1</option><option value="S2">S2</option><option value="S3">S3</option>
                    </select>
                </div>
                <div class="form-group"><label>Jurusan / Prodi</label><input type="text" name="jurusan_prodi" class="form-control"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>TMT Kerja <small>(mulai bergabung)</small></label><input type="date" name="tmt_kerja" class="form-control"></div>
                <div class="form-group"><label>Tugas Tambahan <small>(opsional)</small></label><input type="text" name="tugas_tambahan" class="form-control" placeholder="Contoh: Wakil Kepala Kurikulum"></div>
            </div>

            <h4 class="form-section-title">Kontak</h4>
            <div class="form-row">
                <div class="form-group"><label>No. HP</label><input type="text" name="no_hp" class="form-control"></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control"></div>
            </div>
            <div class="form-group"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2"></textarea></div>
            <div class="form-group"><label>Tahun Masuk *</label><input type="number" name="tahun_masuk" class="form-control" value="<?= date('Y') ?>" required></div>

            <div class="form-actions">
                <a href="<?= base_url('index.php?page=guru') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
