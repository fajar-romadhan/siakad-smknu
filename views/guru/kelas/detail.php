<?php $pageTitle = 'Data Siswa - ' . htmlspecialchars(format_kelas($kelas['nama_kelas'])); ob_start(); ?>
<div class="dash-header-row">
    <div>
        <h2 class="page-title"><?= htmlspecialchars(format_kelas($kelas['nama_kelas'])) ?></h2>
        <p style="color:var(--gray-600); margin-top:4px; font-size:14px;">
            Wali Kelas: <strong><?= htmlspecialchars($wali['nama'] ?? 'Belum ditentukan') ?></strong> | Total: <strong><?= count($siswaKelas) ?> Siswa</strong>
        </p>
    </div>
    <a href="<?= base_url('index.php?page=kelas') ?>" class="btn btn-secondary">← Kembali ke Daftar Kelas</a>
</div>

<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Daftar Siswa <?= htmlspecialchars(format_kelas($kelas['nama_kelas'])) ?></h3>
        <div class="search-bar-figma" style="min-width:240px;">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Cari nama / NISN..." oninput="filterTableRows('siswaKelasGuruTable', this.value)">
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($siswaKelas)): ?>
            <p class="empty-state">Belum ada siswa yang terdaftar di kelas ini.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="siswaKelasGuruTable">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>NISN</th>
                            <th>NIPD</th>
                            <th>Nama Siswa</th>
                            <th style="width:120px;">Jenis Kelamin</th>
                            <th style="width:160px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($siswaKelas as $i => $s): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($s['nisn'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($s['nipd'] ?: '-') ?></td>
                            <td><strong><?= htmlspecialchars($s['nama']) ?></strong></td>
                            <td><?= htmlspecialchars($s['jenis_kelamin'] ?: '-') ?></td>
                            <td style="text-align:center;">
                                <a href="<?= base_url('index.php?page=siswa&action=detail&id='.$s['id']) ?>" class="btn btn-sm btn-info" style="display:inline-flex; align-items:center; gap:4px;">
                                    👤 Detail Biodata
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
