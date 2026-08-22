<?php $pageTitle='Rekap Absensi Siswa'; ob_start(); 
$kelasIdSel=$_GET['kelas_id']??''; $bulanSel=$_GET['bulan']??date('m'); $tahunSel=$_GET['tahun']??date('Y');
$namaKelasSel=''; foreach($kelasList as $k){ if($k['id']==$kelasIdSel) $namaKelasSel=$k['nama_kelas']; }
?>
<div class="card">
    <div class="card-header">
        <h3>Rekap Absensi Siswa</h3>
        <a href="<?= base_url('index.php?page=absensi') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="GET" class="filter-form" style="padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);margin-bottom:18px;">
            <input type="hidden" name="page" value="absensi"><input type="hidden" name="action" value="rekapSiswa">
            <h4 style="font-size:14px;color:var(--primary);margin-bottom:10px;font-weight:600;">Filter Data</h4>
            <div class="form-row" style="grid-template-columns:1fr 1fr 1fr 1fr auto;align-items:end;gap:12px;">
                <div class="form-group"><label>Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-control">
                        <?php foreach($tahunAjaranList as $ta): ?>
                            <option value="<?= $ta['id'] ?>" <?= ($_GET['tahun_ajaran_id']??'')==$ta['id']?'selected':'' ?>><?= htmlspecialchars($ta['tahun_ajaran']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Kelas</label>
                    <select name="kelas_id" class="form-control">
                        <option value="">Pilih Kelas</option>
                        <?php foreach($kelasList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= $kelasIdSel==$k['id']?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <?php for($i=1;$i<=12;$i++): ?>
                            <option value="<?= $i ?>" <?= $bulanSel==$i?'selected':'' ?>><?= date('F',mktime(0,0,0,$i)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group"><label>Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= $tahunSel ?>">
                </div>
                <div class="form-group"><label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </div>
        </form>

        <?php if(!empty($rekapData) || !empty($rekapPerSiswa)): ?>
            <div class="stats-grid" style="grid-template-columns:repeat(5,1fr);">
                <div class="stat-card sm"><div class="stat-info"><h3><?= $totalSiswaKelas ?></h3><p>Total Siswa</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--primary);"><?= $stats['Hadir'] ?></h3><p>Hadir</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#856404;"><?= $stats['Izin'] ?></h3><p>Izin</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#0C5460;"><?= $stats['Sakit'] ?></h3><p>Sakit</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--danger);"><?= $stats['Alpa'] ?></h3><p>Alpa</p></div></div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin:20px 0 12px;">
                <h4 style="color:var(--primary);font-size:16px;font-weight:600;">Rekap Absensi Kelas <?= htmlspecialchars($namaKelasSel) ?></h4>
                <div style="display:flex;gap:8px;" class="no-print">
                    <a href="<?= base_url('index.php?page=absensi&action=exportSiswa&kelas_id='.$kelasIdSel.'&bulan='.$bulanSel.'&tahun='.$tahunSel) ?>" class="btn btn-sm btn-success">📊 Export Excel</a>
                    <button onclick="window.print()" class="btn btn-sm btn-danger">🗶️ Cetak PDF</button>
                </div>
            </div>

            <?php if(!empty($rekapPerSiswa)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th style="width:50px;">No</th><th>NISN</th><th>Nama Siswa</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpa</th></tr></thead>
                    <tbody>
                        <?php foreach($rekapPerSiswa as $i=>$rs): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($rs['nisn']) ?></td>
                            <td><?= htmlspecialchars($rs['nama']) ?></td>
                            <td><span class="badge hadir"><?= $rs['h'] ?></span></td>
                            <td><span class="badge izin"><?= $rs['i'] ?></span></td>
                            <td><span class="badge sakit"><?= $rs['sk'] ?></span></td>
                            <td><span class="badge alpa"><?= $rs['al'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        <?php elseif(isset($_GET['kelas_id'])): ?>
            <p class="empty-state">Belum ada data absensi untuk kelas & bulan yang dipilih.</p>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
