<?php $pageTitle='Catatan Siswa'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Catatan Siswa</h2>
</div>

<div class="card">
    <div class="card-header"><h3>Pilih Kelas & Mata Pelajaran</h3></div>
    <div class="card-body">
        <?php if(empty($kelasList)): ?>
            <p class="empty-state">Belum ada kelas & mapel yang diampu. Admin belum menetapkan jadwal untuk Anda.</p>
        <?php else: ?>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <?php foreach($kelasList as $k): ?>
            <a href="<?= base_url('index.php?page=catatan&action=input&kelas_id='.$k['id'].'&mapel_id='.$k['mapel_id']) ?>" style="display:block;padding:18px;background:var(--white);border-radius:var(--radius);text-decoration:none;color:var(--gray-700);box-shadow:var(--shadow);transition:var(--transition);border:1px solid rgba(0,0,0,.03);" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='var(--shadow-hover)';" onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow)';">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <div>
                        <span class="role-badge" style="font-size:11px;">Kelas <?= htmlspecialchars($k['nama_kelas']) ?></span>
                        <h4 style="font-size:15px;color:var(--gray-800);font-weight:700;margin-top:8px;"><?= htmlspecialchars($k['nama_mapel']) ?></h4>
                        <p style="font-size:12px;color:var(--gray-500);margin-top:4px;">Klik untuk buat catatan</p>
                    </div>
                    <span style="font-size:20px;color:var(--primary);">📋</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
