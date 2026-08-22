<?php $pageTitle='Edit Pengumuman'; ob_start(); $penerimaArr = array_map('trim', explode(',', $p['penerima'] ?? '')); ?>
<div class="card">
    <div class="card-header">
        <h3>Edit Pengumuman</h3>
        <a href="<?= base_url('index.php?page=pengumuman') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('index.php?page=pengumuman&action=update&id='.$p['id']) ?>">
            <div class="form-group">
                <label>Judul Pengumuman *</label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($p['judul']) ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Publish *</label>
                    <input type="date" name="tanggal_publish" class="form-control" value="<?= substr($p['tanggal_publish'],0,10) ?>">
                </div>
                <div class="form-group">
                    <label>Pilih Penerima *</label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;padding:8px;border:2px solid var(--gray-200);border-radius:var(--radius-sm);background:var(--white);">
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--gray-50);border-radius:20px;cursor:pointer;font-size:13px;font-weight:500;">
                            <input type="checkbox" name="penerima[]" value="guru" <?= in_array('guru',$penerimaArr)?'checked':'' ?>> Guru
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--gray-50);border-radius:20px;cursor:pointer;font-size:13px;font-weight:500;">
                            <input type="checkbox" name="penerima[]" value="siswa" <?= in_array('siswa',$penerimaArr)?'checked':'' ?>> Siswa
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;padding:6px 12px;background:var(--gray-50);border-radius:20px;cursor:pointer;font-size:13px;font-weight:500;">
                            <input type="checkbox" name="penerima[]" value="kepala_sekolah" <?= in_array('kepala_sekolah',$penerimaArr)?'checked':'' ?>> Kepala Sekolah
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Isi Pengumuman *</label>
                <textarea name="isi" class="form-control" rows="7" required><?= htmlspecialchars($p['isi']) ?></textarea>
            </div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=pengumuman') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" name="draft" value="1" class="btn btn-info">Simpan sebagai Draft</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
