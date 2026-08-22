<?php $pageTitle = 'Rekap Absensi Guru'; ob_start();
$guruId = isset($_GET['guru_id']) ? (int)$_GET['guru_id'] : 0;
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');
$namaGuru='';
foreach($guruList as $g){ if($g['id']==$guruId){ $namaGuru=$g['nama']; break; } }
?>
<div class="card">
    <div class="card-header"><h3>Rekap Absensi Guru</h3></div>
    <div class="card-body">
        <form method="get" style="display:grid;grid-template-columns:1.5fr 1fr 1fr auto;gap:12px;align-items:end">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="absensiGuru">
            <div class="form-group" style="margin:0"><label>Nama Guru</label>
                <select name="guru_id" class="form-control">
                    <option value="0">— Semua Guru —</option>
                    <?php foreach ($guruList as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= $guruId==$g['id']?'selected':'' ?>><?= htmlspecialchars($g['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0"><label>Bulan</label>
                <select name="bulan" class="form-control">
                    <?php for($i=1;$i<=12;$i++): ?>
                        <option value="<?= $i ?>" <?= $bulan==$i?'selected':'' ?>><?= date('F',mktime(0,0,0,$i)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0"><label>Tahun</label><input type="number" name="tahun" value="<?= htmlspecialchars($tahun) ?>" class="form-control"></div>
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px">
    <?php foreach ($ring as $lbl => $jml):
        $col = ['Hadir'=>['#E5F4EA','#00923F'],'Izin'=>['#FFF7E5','#B87C00'],'Sakit'=>['#E5F0FF','#0056B3'],'Alpa'=>['#FBE5E5','#B32020']][$lbl];
    ?>
        <div style="background:<?= $col[0] ?>;padding:16px;border-radius:10px;text-align:center">
            <div style="font-size:26px;font-weight:700;color:<?= $col[1] ?>"><?= $jml ?></div>
            <small style="color:<?= $col[1] ?>;font-weight:600"><?= $lbl ?></small>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Detail<?= $namaGuru?' — '.htmlspecialchars($namaGuru):' Semua Guru' ?></h3>
        <span style="font-size:13px;color:var(--gray-500)"><?= count($data) ?> catatan</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th style="width:50px">No</th><th>Tanggal</th><th>Nama Guru</th><th>Status</th></tr></thead>
            <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="5" style="text-align:center;padding:20px;color:var(--gray-500)">Tidak ada data absensi.</td></tr>
            <?php else: foreach ($data as $i=>$r):
                $sc = ['Hadir'=>'#00923F','Izin'=>'#B87C00','Sakit'=>'#0056B3','Alpa'=>'#B32020'][$r['status']] ?? '#333';
                $sbg = ['Hadir'=>'#E5F4EA','Izin'=>'#FFF7E5','Sakit'=>'#E5F0FF','Alpa'=>'#FBE5E5'][$r['status']] ?? '#F1F3F5';
            ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($r['nama']) ?></td>
                    <td><span style="background:<?= $sbg ?>;color:<?= $sc ?>;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600"><?= htmlspecialchars($r['status']) ?></span></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
