<?php $pageTitle='Buat Pengumuman'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Tambah Pengumuman Baru</h3>
        <a href="<?= base_url('index.php?page=pengumuman') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('index.php?page=pengumuman&action=store') ?>">
            <div class="form-group">
                <label>Judul Pengumuman *</label>
                <input type="text" name="judul" class="form-control" placeholder="Tulis judul pengumuman..." required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Publish *</label>
                    <input type="date" name="tanggal_publish" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    <small style="color:var(--gray-400);font-size:11px;">Jika tanggal di masa depan, pengumuman akan dijadwalkan otomatis</small>
                </div>
                <div class="form-group">
                    <label>Pilih Penerima *</label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;padding:8px;border:2px solid var(--gray-200);border-radius:var(--radius-sm);background:var(--white);">
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--gray-50);border-radius:20px;cursor:pointer;font-size:13px;font-weight:500;">
                            <input type="checkbox" name="penerima[]" value="guru"> Guru
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--gray-50);border-radius:20px;cursor:pointer;font-size:13px;font-weight:500;">
                            <input type="checkbox" name="penerima[]" value="siswa"> Siswa
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--gray-50);border-radius:20px;cursor:pointer;font-size:13px;font-weight:500;">
                            <input type="checkbox" name="penerima[]" value="kepala_sekolah"> Kepala Sekolah
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Isi Pengumuman *</label>
                <textarea name="isi" class="form-control" rows="7" placeholder="Tulis isi pengumuman..." required></textarea>
            </div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=pengumuman') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" name="draft" value="1" class="btn btn-info">Simpan sebagai Draft</button>
                <button type="submit" class="btn btn-primary">Publish</button>
            </div>
        </form>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
