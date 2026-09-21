<?php $pageTitle = 'Rekap Absensi Siswa'; ob_start(); 
$bulanNama = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>
<div class="dash-header-row">
    <h2 class="page-title">Rekap Presensi Siswa (Monitoring Kepala Sekolah)</h2>
</div>

<!-- FILTER CARD -->
<div class="card" style="margin-bottom:22px;">
    <div class="card-header">
        <h3>Filter Rekap Presensi Siswa</h3>
    </div>
    <div class="card-body">
        <form method="get" action="<?= base_url('index.php') ?>" class="filter-form" style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="absensi">

            <div class="form-group" style="margin:0;">
                <label style="font-weight:600;margin-bottom:4px;display:block;">Kelas:</label>
                <select name="kelas_id" class="form-control" style="min-width:150px;">
                    <option value="0">Semua Kelas</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $k['id']==$kelasId?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-weight:600;margin-bottom:4px;display:block;">Mata Pelajaran:</label>
                <select name="mapel_id" class="form-control" style="min-width:180px;">
                    <option value="0">Semua Mata Pelajaran</option>
                    <?php foreach ($mapelList as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $m['id']==$mapelId?'selected':'' ?>><?= htmlspecialchars($m['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-weight:600;margin-bottom:4px;display:block;">Bulan:</label>
                <select name="bulan" class="form-control" style="min-width:130px;">
                    <option value="0">Semua Bulan</option>
                    <?php foreach ($bulanNama as $mNum => $mName): ?>
                        <option value="<?= $mNum ?>" <?= $bulan==$mNum?'selected':'' ?>><?= $mName ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-weight:600;margin-bottom:4px;display:block;">Tahun:</label>
                <input type="number" name="tahun" value="<?= htmlspecialchars($tahun) ?>" class="form-control" style="width:100px;">
            </div>

            <div class="form-group" style="margin:0;display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                <a href="<?= base_url('index.php?page=kepsek&action=absensi') ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- SINGLE STUDENT DETAIL CARD IF SISWA_ID IS SELECTED -->
<?php if ($siswaId > 0 && !empty($detailSiswa)): ?>
<div class="card" style="margin-bottom: 24px; border:2px solid #00923F;">
    <div class="card-header" style="background:#E8F5E9; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="color:#00923F; margin:0;">📋 Detail Presensi Harian: <strong><?= htmlspecialchars($detailSiswa['nama']) ?></strong> (<?= htmlspecialchars($detailSiswa['nama_kelas'] ?? '-') ?>)</h3>
        <a href="<?= base_url('index.php?page=kepsek&action=absensi&kelas_id='.$kelasId.'&mapel_id='.$mapelId.'&bulan='.$bulan.'&tahun='.$tahun) ?>" class="btn btn-secondary btn-sm">← Kembali ke Rekap Kelas</a>
    </div>
    <div class="card-body">
        <?php if (empty($detailLogs)): ?>
            <p class="empty-state">Belum ada riwayat presensi harian untuk siswa ini pada filter terpilih.</p>
        <?php else: ?>
            <div class="table-responsive" style="max-height:500px; overflow-y:auto;">
                <table class="table">
                    <thead>
                        <tr style="position:sticky; top:0; z-index:10; background:var(--primary); color:#fff;">
                            <th style="width:50px;">No</th>
                            <th>Hari & Tanggal</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th style="text-align:center;">Status Presensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $hariIndo = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                        foreach ($detailLogs as $idx => $dl):
                            $time = strtotime($dl['tanggal']);
                            $namaHari = $hariIndo[date('w', $time)] ?? '';
                            $tglFmt = $namaHari . ', ' . date('d', $time) . ' ' . ($bulanNama[(int)date('m', $time)] ?? '') . ' ' . date('Y', $time);
                            $sc = ['Hadir'=>'#00923F','Izin'=>'#B87C00','Sakit'=>'#0056B3','Alpa'=>'#B32020'][$dl['status']] ?? '#333';
                            $sbg = ['Hadir'=>'#E5F4EA','Izin'=>'#FFF7E5','Sakit'=>'#E5F0FF','Alpa'=>'#FBE5E5'][$dl['status']] ?? '#F1F3F5';
                        ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><strong><?= $tglFmt ?></strong></td>
                            <td><?= htmlspecialchars($dl['nama_mapel'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($dl['guru_nama'] ?? '-') ?></td>
                            <td style="text-align:center;">
                                <span style="background:<?= $sbg ?>;color:<?= $sc ?>;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;">
                                    <?= htmlspecialchars($dl['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- STATS BADGES -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px">
    <?php foreach ($ring as $lbl => $jml):
        $col = ['Hadir'=>['#E5F4EA','#00923F'],'Izin'=>['#FFF7E5','#B87C00'],'Sakit'=>['#E5F0FF','#0056B3'],'Alpa'=>['#FBE5E5','#B32020']][$lbl];
    ?>
        <div style="background:<?= $col[0] ?>;padding:16px;border-radius:10px;text-align:center">
            <div style="font-size:26px;font-weight:700;color:<?= $col[1] ?>"><?= number_format($jml) ?></div>
            <small style="color:<?= $col[1] ?>;font-weight:600"><?= $lbl ?></small>
        </div>
    <?php endforeach; ?>
</div>

<!-- GENERAL ATTENDANCE TABLE WITH FAST PAGINATION & STICKY HEADER -->
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <h3>Daftar Catatan Presensi Siswa</h3>
        <span style="font-size:13px;color:var(--gray-500); font-weight:600;">Total <?= number_format($totalRecords) ?> Catatan Log</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive" style="max-height:600px; overflow-y:auto;">
            <table class="table" style="margin:0;">
                <thead>
                    <tr style="position:sticky; top:0; z-index:10; background:var(--primary); color:#fff;">
                        <th style="width:50px;">No</th>
                        <th>Tanggal</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th style="text-align:center;">Status</th>
                        <th style="width:120px; text-align:center;">Detail Harian</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--gray-500)">Tidak ada data absensi pada filter ini.</td></tr>
                <?php else: foreach ($data as $idx => $r):
                    $sc = ['Hadir'=>'#00923F','Izin'=>'#B87C00','Sakit'=>'#0056B3','Alpa'=>'#B32020'][$r['status']] ?? '#333';
                    $sbg = ['Hadir'=>'#E5F4EA','Izin'=>'#FFF7E5','Sakit'=>'#E5F0FF','Alpa'=>'#FBE5E5'][$r['status']] ?? '#F1F3F5';
                ?>
                    <tr>
                        <td><?= $offset + $idx + 1 ?></td>
                        <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($r['nisn'] ?? '-') ?></td>
                        <td><strong><?= htmlspecialchars($r['siswa_nama'] ?? '-') ?></strong></td>
                        <td><?= htmlspecialchars($r['nama_kelas'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($r['nama_mapel'] ?? '-') ?></td>
                        <td style="text-align:center;">
                            <span style="background:<?= $sbg ?>;color:<?= $sc ?>;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;">
                                <?= htmlspecialchars($r['status']) ?>
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <a href="<?= base_url('index.php?page=kepsek&action=absensi&siswa_id='.$r['siswa_id'].'&kelas_id='.$kelasId.'&mapel_id='.$mapelId.'&bulan='.$bulan.'&tahun='.$tahun) ?>" class="btn btn-primary btn-sm" style="padding:3px 8px; font-size:11px;">
                                🔍 Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <div style="display:flex; justify-content:space-between; align-items:center; padding:14px 20px; border-top:1px solid var(--stroke-light); background:#FAFBFD; flex-wrap:wrap; gap:12px;">
            <div style="font-size:13px; color:var(--gray-600);">
                Menampilkan Halaman <strong><?= $p ?></strong> dari <strong><?= $totalPages ?></strong> (Total <?= number_format($totalRecords) ?> catatan log)
            </div>
            <div style="display:flex; gap:6px;">
                <?php 
                $baseUrl = base_url('index.php?page=kepsek&action=absensi&kelas_id='.$kelasId.'&mapel_id='.$mapelId.'&bulan='.$bulan.'&tahun='.$tahun);
                if ($p > 1): ?>
                    <a href="<?= $baseUrl . '&p=' . ($p - 1) ?>" class="btn btn-secondary btn-sm">← Sebelumnya</a>
                <?php endif; ?>
                
                <?php 
                $startP = max(1, $p - 2);
                $endP = min($totalPages, $p + 2);
                for ($i = $startP; $i <= $endP; $i++): ?>
                    <a href="<?= $baseUrl . '&p=' . $i ?>" class="btn btn-sm <?= $i === $p ? 'btn-primary' : 'btn-secondary' ?>" style="min-width:32px;">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($p < $totalPages): ?>
                    <a href="<?= $baseUrl . '&p=' . ($p + 1) ?>" class="btn btn-secondary btn-sm">Selanjutnya →</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
