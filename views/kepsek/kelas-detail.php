<?php $pageTitle = 'Detail Kelas'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Detail <?= htmlspecialchars(format_kelas($kelasInfo['nama_kelas'] ?? '')) ?></h2>
    <a href="<?= base_url('index.php?page=kepsek&action=kelas') ?>" class="btn btn-secondary">← Kembali ke Data Kelas</a>
</div>

<!-- INFORMASI KELAS CARD -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3>Informasi Kelas</h3>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><label>Nama Kelas</label><span><strong><?= htmlspecialchars($kelasInfo['nama_kelas'] ?? '-') ?></strong></span></div>
            <div class="detail-item"><label>Kode Kelas</label><span><?= htmlspecialchars($kelasInfo['kode_kelas'] ?? '-') ?></span></div>
            <div class="detail-item"><label>Wali Kelas</label><span><?= htmlspecialchars($kelasInfo['wali_nama'] ?? '-') ?></span></div>
            <div class="detail-item"><label>Tahun Ajaran</label><span><?= htmlspecialchars($kelasInfo['tahun_ajaran'] ?? '-') ?></span></div>
            <div class="detail-item"><label>Total Siswa</label><span><strong style="color:var(--primary);"><?= count($siswaKelas) ?> Siswa</strong></span></div>
        </div>
    </div>
</div>

<!-- DAFTAR SISWA CARD -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Siswa <?= htmlspecialchars(format_kelas($kelasInfo['nama_kelas'] ?? '')) ?></h3>
    </div>
    <div class="card-body">
        <?php if (empty($siswaKelas)): ?>
            <p class="empty-state">Belum ada siswa yang terdaftar di kelas ini.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Kode Siswa</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>No. HP</th>
                            <th style="width:160px; text-align:center;">Aksi Monitoring</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($siswaKelas as $idx => $s): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><?= htmlspecialchars($s['kode_siswa'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['nisn'] ?? '-') ?></td>
                            <td><strong><?= htmlspecialchars($s['nama']) ?></strong></td>
                            <td><?= htmlspecialchars($s['jenis_kelamin'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($s['no_hp'] ?? '-') ?></td>
                            <td style="text-align:center;">
                                <a href="<?= base_url('index.php?page=kepsek&action=siswaDetail&id='.$s['id']) ?>" class="btn btn-primary btn-sm">
                                    🔍 Detail Biodata
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
