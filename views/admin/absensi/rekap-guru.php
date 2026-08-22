<?php $pageTitle='Rekap Absensi Guru'; ob_start();
$guruId=isset($_GET['guru_id'])?(int)$_GET['guru_id']:0;
$periode=$_GET['periode']??'bulan';
$semester=$_GET['semester']??'ganjil';
$exportQs='&guru_id='.$guruId.'&periode='.$periode.'&bulan='.$bulan.'&tahun='.$tahun.'&semester='.$semester;
$periodeLabel = $periode==='semester' ? ('Semester '.ucfirst($semester).' '.$tahun) : (date('F',mktime(0,0,0,$bulan)).' '.$tahun);
$namaGuruTerpilih = '';
foreach($guruList as $g){ if($g['id']==$guruId){ $namaGuruTerpilih=$g['nama']; break; } }
?>
<div class="card">
    <div class="card-header">
        <h3>Rekap Absensi Guru</h3>
        <a href="<?= base_url('index.php?page=absensi') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="GET" class="filter-form" style="padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);margin-bottom:18px;">
            <input type="hidden" name="page" value="absensi"><input type="hidden" name="action" value="rekapGuru">
            <h4 style="font-size:14px;color:var(--primary);margin-bottom:10px;font-weight:600;">Filter Data</h4>
            <div class="form-row" style="grid-template-columns:1.4fr 1fr 1fr 1fr auto;align-items:end;gap:12px;">
                <div class="form-group"><label>Nama Guru</label>
                    <select name="guru_id" class="form-control">
                        <option value="0">— Semua Guru —</option>
                        <?php foreach($guruList as $g): ?>
                            <option value="<?= $g['id'] ?>" <?= $guruId==$g['id']?'selected':'' ?>><?= htmlspecialchars($g['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Periode</label>
                    <select name="periode" class="form-control" onchange="togglePeriode(this.value)">
                        <option value="bulan" <?= $periode==='bulan'?'selected':'' ?>>Per Bulan</option>
                        <option value="semester" <?= $periode==='semester'?'selected':'' ?>>Per Semester</option>
                    </select>
                </div>
                <div class="form-group" id="grpBulan" style="<?= $periode==='semester'?'display:none;':'' ?>"><label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <?php for($i=1;$i<=12;$i++): ?>
                            <option value="<?= $i ?>" <?= $bulan==$i?'selected':'' ?>><?= date('F',mktime(0,0,0,$i)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group" id="grpSemester" style="<?= $periode==='bulan'?'display:none;':'' ?>"><label>Semester</label>
                    <select name="semester" class="form-control">
                        <option value="ganjil" <?= $semester==='ganjil'?'selected':'' ?>>Ganjil (Jul–Des)</option>
                        <option value="genap" <?= $semester==='genap'?'selected':'' ?>>Genap (Jan–Jun)</option>
                    </select>
                </div>
                <div class="form-group"><label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= $tahun ?>">
                </div>
                <div class="form-group"><label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </div>
        </form>

        <?php if(!empty($rekapData)): ?>
            <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--primary);"><?= $stats['Hadir'] ?></h3><p>Hadir</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#856404;"><?= $stats['Izin'] ?></h3><p>Izin</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#0C5460;"><?= $stats['Sakit'] ?></h3><p>Sakit</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--danger);"><?= $stats['Alpa'] ?></h3><p>Alpa</p></div></div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin:20px 0 12px;">
                <h4 style="color:var(--primary);font-size:16px;font-weight:600;">
                    Rekap<?= $namaGuruTerpilih?' — '.htmlspecialchars($namaGuruTerpilih):' Semua Guru' ?> · <?= $periodeLabel ?>
                </h4>
                <div style="display:flex;gap:8px;" class="no-print">
                    <a href="<?= base_url('index.php?page=absensi&action=exportGuru'.$exportQs) ?>" class="btn btn-sm btn-success">📊 Export Excel</a>
                    <button onclick="window.print()" class="btn btn-sm btn-danger">🖨 Cetak PDF</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th style="width:60px;">No</th><th>Tanggal</th><th>Nama Guru</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach($rekapData as $i=>$r): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= date('d/m/Y',strtotime($r['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($r['nama']) ?></td>
                            <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="empty-state">Belum ada data absensi guru untuk filter yang dipilih.</p>
        <?php endif; ?>
    </div>
</div>
<script>
function togglePeriode(v){
    document.getElementById('grpBulan').style.display = (v==='semester')?'none':'';
    document.getElementById('grpSemester').style.display = (v==='semester')?'':'none';
}
</script>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
