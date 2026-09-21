<?php $pageTitle = 'Rekap Absensi Guru'; ob_start();
$guruId = isset($_GET['guru_id']) ? (int)$_GET['guru_id'] : 0;
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');
$namaGuru='';
foreach($guruList as $g){ if($g['id']==$guruId){ $namaGuru=$g['nama']; break; } }
?>
<div class="dash-header-row">
    <h2 class="page-title">Rekap Presensi Guru (Monitoring Kepala Sekolah)</h2>
</div>

<div class="card" style="margin-bottom:22px;">
    <div class="card-header"><h3>Filter Presensi Guru</h3></div>
    <div class="card-body">
        <form method="get" action="<?= base_url('index.php') ?>" style="display:grid;grid-template-columns:1.5fr 1fr 1fr auto;gap:12px;align-items:end">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="absensiGuru">
            <div class="form-group" style="margin:0"><label style="font-weight:600;margin-bottom:4px;display:block;">Nama Guru:</label>
                <select name="guru_id" class="form-control">
                    <option value="0">— Semua Guru —</option>
                    <?php foreach ($guruList as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= $guruId==$g['id']?'selected':'' ?>><?= htmlspecialchars($g['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0"><label style="font-weight:600;margin-bottom:4px;display:block;">Bulan:</label>
                <select name="bulan" class="form-control">
                    <?php 
                    $bulanNama = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    for($i=1;$i<=12;$i++): ?>
                        <option value="<?= $i ?>" <?= $bulan==$i?'selected':'' ?>><?= $bulanNama[$i] ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0"><label style="font-weight:600;margin-bottom:4px;display:block;">Tahun:</label>
                <input type="number" name="tahun" value="<?= htmlspecialchars($tahun) ?>" class="form-control">
            </div>
            <div class="form-group" style="margin:0;display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">Terapkan</button>
                <a href="<?= base_url('index.php?page=kepsek&action=absensiGuru') ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

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

<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Detail Presensi Guru<?= $namaGuru?' — '.htmlspecialchars($namaGuru):' (Semua Guru)' ?></h3>
        <span style="font-size:13px;color:var(--gray-500); font-weight:600;"><?= number_format($totalRecords) ?> Catatan Presensi</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive" style="max-height:600px; overflow-y:auto;">
            <table class="table" style="margin:0;">
                <thead>
                    <tr style="position:sticky; top:0; z-index:10; background:var(--primary); color:#fff;">
                        <th style="width:50px">No</th>
                        <th>Tanggal</th>
                        <th>Nama Guru</th>
                        <th style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
                    <tr><td colspan="4" style="text-align:center;padding:30px;color:var(--gray-500)">Tidak ada data absensi guru pada filter ini.</td></tr>
                <?php else: foreach ($data as $i=>$r):
                    $sc = ['Hadir'=>'#00923F','Izin'=>'#B87C00','Sakit'=>'#0056B3','Alpa'=>'#B32020'][$r['status']] ?? '#333';
                    $sbg = ['Hadir'=>'#E5F4EA','Izin'=>'#FFF7E5','Sakit'=>'#E5F0FF','Alpa'=>'#FBE5E5'][$r['status']] ?? '#F1F3F5';
                ?>
                    <tr>
                        <td><?= $offset + $i + 1 ?></td>
                        <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                        <td><strong><?= htmlspecialchars($r['nama']) ?></strong></td>
                        <td style="text-align:center;">
                            <span style="background:<?= $sbg ?>;color:<?= $sc ?>;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:600;display:inline-block;">
                                <?= htmlspecialchars($r['status']) ?>
                            </span>
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
                $baseUrl = base_url('index.php?page=kepsek&action=absensiGuru&guru_id='.$guruId.'&bulan='.$bulan.'&tahun='.$tahun);
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
