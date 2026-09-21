<?php $pageTitle='Tambah Siswa ke Kelas'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Tambah Siswa ke <?= htmlspecialchars(format_kelas($kelas['nama_kelas'] ?? '')) ?></h3>
        <form method="GET" action="<?= base_url('index.php') ?>" style="display:flex;gap:8px;align-items:center;">
            <input type="hidden" name="page" value="kelas">
            <input type="hidden" name="action" value="tambahSiswa">
            <input type="hidden" name="id" value="<?= $kelas['id'] ?>">
            <div class="search-bar-figma" style="min-width:220px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="search" placeholder="Cari nama siswa..." value="<?= htmlspecialchars((string)($search ?? '')) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>
    <div class="card-body">
        <?php if(empty($siswaList)): ?>
            <p class="empty-state">Tidak ada siswa yang bisa ditambahkan. Semua siswa mungkin sudah masuk ke kelas ini atau kelas lain.</p>
        <?php else: ?>
        <form method="POST" action="<?= base_url('index.php?page=kelas&action=simpanSiswa&id='.$kelas['id']) ?>">
            <div class="table-responsive"><table class="table"><thead><tr><th style="width:50px;"><input type="checkbox" id="selectAll"></th><th>NISN</th><th>Nama</th></tr></thead><tbody>
               <?php foreach($siswaList as $s): 
    $disabled = false;
    $statusText = '';

    if(!empty($s['kelas_id'])) {
        $disabled = true;

        if($s['kelas_id'] == $kelas['id']) {
            $statusText = '<span style="color: #00923F; font-weight: 600;">Sudah terdaftar di kelas ini</span>';
        } else {
            $statusText = '<span style="color: #00923F; font-weight: 600;">Sudah terdaftar di ' . htmlspecialchars(format_kelas($s['nama_kelas'] ?? '')) . '</span>';
        }
    }
?> 
                
                <tr>
                    <td><input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" class="siswa-check" <?= $disabled ? 'disabled' : '' ?>></td>
                    <td><?= htmlspecialchars((string)($s['nisn'] ?? '-')) ?></td>
                    <td>
                        <?= htmlspecialchars((string)($s['nama'] ?? '-')) ?>
                        <?php if($statusText): ?>
                            <div style="font-size:0.95rem;color:var(--gray-500);margin-top:4px;line-height:1.4;"><?= $statusText ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody></table></div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;padding:10px 12px;background:var(--primary-soft);border-radius:var(--radius-sm);">
                <span id="selectedCount" style="font-weight:600;color:var(--gray-500);">0 siswa dipilih</span>
                <div style="display:flex;gap:8px;">
                    <a href="<?= base_url('index.php?page=kelas&action=detail&id='.$kelas['id']) ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Tambahkan ke Kelas</button>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
