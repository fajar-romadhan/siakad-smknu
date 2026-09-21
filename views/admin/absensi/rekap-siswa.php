<?php $pageTitle='Rekap Absensi Siswa'; ob_start(); 
$kelasIdSel=$_GET['kelas_id']??''; $bulanSel=$_GET['bulan']??date('m'); $tahunSel=$_GET['tahun']??date('Y');
$mapelIdSel=isset($_GET['mapel_id'])?(int)$_GET['mapel_id']:0;

$namaKelasSel=''; foreach($kelasList as $k){ if($k['id']==$kelasIdSel) $namaKelasSel=$k['nama_kelas']; }

$namaMapelSel = 'Semua Mata Pelajaran';
if(!empty($mapelList)) {
    foreach($mapelList as $mp){ if($mp['id']==$mapelIdSel) $namaMapelSel=$mp['nama_mapel']; }
}

function formatTglIndo($dateStr) {
    if (!$dateStr) return '-';
    $timestamp = strtotime($dateStr);
    $hari = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
    $bulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $namaHari = $hari[date('l', $timestamp)] ?? date('l', $timestamp);
    $tgl = date('d', $timestamp);
    $blnNum = (int)date('m', $timestamp);
    $namaBulan = $bulan[$blnNum] ?? date('F', $timestamp);
    $thn = date('Y', $timestamp);
    return "$namaHari, $tgl $namaBulan $thn";
}
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
            <div class="form-row" style="grid-template-columns:1fr 1fr 1.4fr 1fr 1fr auto;align-items:end;gap:12px;">
                <div class="form-group"><label>Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-control">
                        <?php foreach($tahunAjaranList as $ta): ?>
                            <option value="<?= $ta['id'] ?>" <?= ($_GET['tahun_ajaran_id']??'')==$ta['id']?'selected':'' ?>><?= htmlspecialchars($ta['tahun_ajaran']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Kelas</label>
                    <select name="kelas_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Pilih Kelas</option>
                        <?php foreach($kelasList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= $kelasIdSel==$k['id']?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Mata Pelajaran</label>
                    <select name="mapel_id" class="form-control">
                        <option value="">Semua Mata Pelajaran</option>
                        <?php if(!empty($mapelList)): foreach($mapelList as $mp): ?>
                            <option value="<?= $mp['id'] ?>" <?= $mapelIdSel==$mp['id']?'selected':'' ?>><?= htmlspecialchars($mp['nama_mapel']) ?></option>
                        <?php endforeach; endif; ?>
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

        <?php if (!empty($detailSiswa)): ?>
            <!-- TAMPILAN DETAIL PRESENSI HARIAN 1 SISWA (MENGGANTIKAN TABEL REKAP KELAS) -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin:10px 0 16px;flex-wrap:wrap;gap:12px;">
                <div>
                    <h4 style="color:var(--primary);font-size:18px;font-weight:700;margin-bottom:4px;">
                        Detail Presensi Harian Siswa: <?= htmlspecialchars($detailSiswa['nama']) ?>
                    </h4>
                    <p style="margin:0;font-size:13px;color:var(--gray-600);">
                        NISN: <strong><?= htmlspecialchars($detailSiswa['nisn'] ?: '-') ?></strong> | 
                        Kelas: <strong><?= htmlspecialchars($namaKelasSel ?: '-') ?></strong> | 
                        Mata Pelajaran: <strong><?= htmlspecialchars($namaMapelSel) ?></strong> | 
                        Periode: <strong><?= date('F', mktime(0,0,0, (int)$bulanSel)) ?> <?= $tahunSel ?></strong>
                    </p>
                </div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;" class="no-print">
                    <a href="<?= base_url('index.php?page=absensi&action=rekapSiswa&kelas_id='.$kelasIdSel.'&bulan='.$bulanSel.'&tahun='.$tahunSel.'&tahun_ajaran_id='.($_GET['tahun_ajaran_id']??'').'&mapel_id='.$mapelIdSel) ?>" class="btn btn-sm btn-secondary">
                        ← Kembali ke Rekap Kelas
                    </a>
                    <a href="<?= base_url('index.php?page=absensi&action=exportSiswa&kelas_id='.$kelasIdSel.'&bulan='.$bulanSel.'&tahun='.$tahunSel.'&mapel_id='.$mapelIdSel.'&siswa_id='.$detailSiswa['id']) ?>" class="btn btn-sm btn-success">
                        📊 Export Excel
                    </a>
                    <button onclick="window.print()" class="btn btn-sm btn-danger">
                        🗶️ Cetak PDF
                    </button>
                </div>
            </div>

            <!-- Ringkasan Statistik Siswa -->
            <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px;">
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--primary);"><?= $detailStats['Hadir'] ?></h3><p>Hadir</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#856404;"><?= $detailStats['Izin'] ?></h3><p>Izin</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#0C5460;"><?= $detailStats['Sakit'] ?></h3><p>Sakit</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--danger);"><?= $detailStats['Alpa'] ?></h3><p>Alpa</p></div></div>
            </div>

            <?php if (empty($detailAbsensi)): ?>
                <p class="empty-state">Belum ada catatan riwayat absensi harian untuk siswa ini pada kriteria yang dipilih.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:50px;">No</th>
                                <th style="width:220px;">Hari & Tanggal</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru Pengampu</th>
                                <th style="width:140px; text-align:center;">Status Presensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($detailAbsensi as $idx => $da): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td><strong><?= formatTglIndo($da['tanggal']) ?></strong></td>
                                <td><?= htmlspecialchars($da['nama_mapel'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($da['guru_nama'] ?? '-') ?></td>
                                <td style="text-align:center;">
                                    <?php if ($da['status'] === 'Hadir'): ?>
                                        <span class="badge hadir" style="padding:5px 12px; font-size:12px; font-weight:600;">Hadir</span>
                                    <?php elseif ($da['status'] === 'Izin'): ?>
                                        <span class="badge izin" style="padding:5px 12px; font-size:12px; font-weight:600;">Izin</span>
                                    <?php elseif ($da['status'] === 'Sakit'): ?>
                                        <span class="badge sakit" style="padding:5px 12px; font-size:12px; font-weight:600;">Sakit</span>
                                    <?php else: ?>
                                        <span class="badge alpa" style="padding:5px 12px; font-size:12px; font-weight:600;">Alpa</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php elseif(!empty($rekapData) || !empty($rekapPerSiswa)): ?>
            <!-- TAMPILAN TABEL REKAP KELAS (DITAMPILKAN JIKA BELUM PILIH SISWA) -->
            <div class="stats-grid" style="grid-template-columns:repeat(5,1fr);">
                <div class="stat-card sm"><div class="stat-info"><h3><?= $totalSiswaKelas ?></h3><p>Total Siswa</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--primary);"><?= $stats['Hadir'] ?></h3><p>Hadir</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#856404;"><?= $stats['Izin'] ?></h3><p>Izin</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:#0C5460;"><?= $stats['Sakit'] ?></h3><p>Sakit</p></div></div>
                <div class="stat-card sm"><div class="stat-info"><h3 style="color:var(--danger);"><?= $stats['Alpa'] ?></h3><p>Alpa</p></div></div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin:20px 0 12px;">
                <div>
                    <h4 style="color:var(--primary);font-size:16px;font-weight:600;margin-bottom:2px;">Rekap Absensi Kelas <?= htmlspecialchars($namaKelasSel) ?></h4>
                    <p style="margin:0;font-size:12px;color:var(--gray-600);">Filter Mapel: <strong><?= htmlspecialchars($namaMapelSel) ?></strong></p>
                </div>
                <div style="display:flex;gap:8px;" class="no-print">
                    <a href="<?= base_url('index.php?page=absensi&action=exportSiswa&kelas_id='.$kelasIdSel.'&bulan='.$bulanSel.'&tahun='.$tahunSel.'&mapel_id='.$mapelIdSel) ?>" class="btn btn-sm btn-success">📊 Export Excel</a>
                    <button onclick="window.print()" class="btn btn-sm btn-danger">🗶️ Cetak PDF</button>
                </div>
            </div>

            <?php if(!empty($rekapPerSiswa)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th style="text-align:center; width:80px;">Hadir</th>
                            <th style="text-align:center; width:80px;">Izin</th>
                            <th style="text-align:center; width:80px;">Sakit</th>
                            <th style="text-align:center; width:80px;">Alpa</th>
                            <th style="width:110px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rekapPerSiswa as $i=>$rs): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($rs['nisn']) ?></td>
                            <td><strong><?= htmlspecialchars($rs['nama']) ?></strong></td>
                            <td style="text-align:center;"><span class="badge hadir"><?= $rs['h'] ?></span></td>
                            <td style="text-align:center;"><span class="badge izin"><?= $rs['i'] ?></span></td>
                            <td style="text-align:center;"><span class="badge sakit"><?= $rs['sk'] ?></span></td>
                            <td style="text-align:center;"><span class="badge alpa"><?= $rs['al'] ?></span></td>
                            <td style="text-align:center;">
                                <a href="<?= base_url('index.php?page=absensi&action=rekapSiswa&kelas_id='.$kelasIdSel.'&bulan='.$bulanSel.'&tahun='.$tahunSel.'&tahun_ajaran_id='.($_GET['tahun_ajaran_id']??'').'&mapel_id='.$mapelIdSel.'&siswa_id='.$rs['id']) ?>" class="btn btn-sm btn-info" style="display:inline-flex; align-items:center; gap:4px;">
                                    🔍 Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        <?php elseif(isset($_GET['kelas_id'])): ?>
            <p class="empty-state">Belum ada data absensi untuk kelas & kriteria yang dipilih.</p>
        <?php endif; ?>
    </div>
</div>

<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
