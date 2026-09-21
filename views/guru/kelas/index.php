<?php $pageTitle = 'Daftar Kelas Diampu'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Kelas Yang Diampu</h2>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Kelas</h3>
        <div class="search-bar-figma" style="min-width:220px;">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" placeholder="Cari nama kelas..." oninput="filterTableRows('kelasGuruTable', this.value)">
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($kelasList)): ?>
            <p class="empty-state">Belum ada kelas yang diampu atau ditugaskan kepada Anda saat ini.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="kelasGuruTable">
                    <thead>
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Kode Kelas</th>
                            <th>Nama Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th>Wali Kelas</th>
                            <th style="text-align:center; width:120px;">Jumlah Siswa</th>
                            <th style="width:160px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($kelasList as $i => $k): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><span class="role-badge"><?= htmlspecialchars($k['kode_kelas']) ?></span></td>
                            <td><strong><?= htmlspecialchars($k['nama_kelas']) ?></strong></td>
                            <td><?= htmlspecialchars($k['tahun_ajaran']) ?></td>
                            <td><?= htmlspecialchars($k['wali'] ?? '-') ?></td>
                            <td style="text-align:center;">
                                <span class="badge" style="background:var(--primary-soft); color:var(--primary); padding:4px 10px; border-radius:12px; font-weight:600;">
                                    <?= $k['jml_siswa'] ?> Siswa
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <a href="<?= base_url('index.php?page=kelas&action=detail&id='.$k['id']) ?>" class="btn btn-primary btn-sm">
                                    Lihat Siswa →
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
