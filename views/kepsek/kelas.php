<?php $pageTitle = 'Data Kelas'; ob_start(); ?>
<div class="card">
    <div class="card-header"><h3>Data Kelas (Monitoring Kepala Sekolah)</h3></div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:50px">#</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th style="text-align:center">Jumlah Siswa</th>
                    <th style="width:160px; text-align:center;">Aksi Monitoring</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="5" style="text-align:center;padding:20px;color:var(--gray-500)">Belum ada data kelas.</td></tr>
            <?php else: foreach ($data as $i => $r): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong style="color:var(--primary)"><?= htmlspecialchars($r['nama_kelas']) ?></strong></td>
                    <td><?= htmlspecialchars($r['wali_nama'] ?? '-') ?></td>
                    <td style="text-align:center"><span style="background:var(--primary-light);color:var(--primary);padding:4px 10px;border-radius:12px;font-weight:600"><?= $r['jml_siswa'] ?> Siswa</span></td>
                    <td style="text-align:center">
                        <a href="<?= base_url('index.php?page=kepsek&action=kelasDetail&id='.$r['id']) ?>" class="btn btn-primary btn-sm">
                            🔍 Detail Siswa
                        </a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
