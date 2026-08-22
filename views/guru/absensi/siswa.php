<?php $pageTitle='Absen Siswa'; ob_start();
$mapelIdSel = $_GET['mapel_id'] ?? ($mapelList[0]['id'] ?? null);
$mapelNama = '';
foreach($mapelList as $mp) if($mp['id']==$mapelIdSel) $mapelNama = $mp['nama_mapel'];
?>
<div class="dash-header-row">
    <h2 class="page-title">Absen Siswa - Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?></h2>
    <a href="<?= base_url('index.php?page=absensi') ?>" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <h3>Isi Kehadiran Siswa</h3>
        <p style="color:var(--gray-500);font-size:12px;">Tanggal: <strong><?= date('d F Y', time()) ?></strong></p>
    </div>
    <div class="card-body">
        <?php if(empty($siswaList)): ?>
            <p class="empty-state">Belum ada siswa di kelas ini.</p>
        <?php else: ?>
        <form method="POST" action="<?= base_url('index.php?page=absensi&action=storeSiswa') ?>">
            <input type="hidden" name="kelas_id" value="<?= $kelasId ?>">
            <div class="form-group" style="max-width:400px;">
                <label>Mata Pelajaran *</label>
                <select name="mapel_id" class="form-control" required>
                    <?php foreach($mapelList as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $mapelIdSel==$m['id']?'selected':'' ?>><?= htmlspecialchars($m['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th style="width:60px;">No</th><th>Nama Siswa</th><th style="width:420px;">Status Kehadiran</th></tr></thead>
                    <tbody>
                        <?php foreach($siswaList as $i => $s): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($s['nama']) ?></td>
                            <td>
                                <div class="status-buttons sm" style="margin:0;">
                                    <?php foreach(['Hadir','Izin','Sakit','Alpa'] as $st): ?>
                                    <label class="status-btn"><input type="radio" name="absensi[<?= $s['id'] ?>]" value="<?= $st ?>" <?= $st==='Hadir'?'checked':'' ?>><span><?= $st ?></span></label>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=absensi') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($riwayat)): ?>
<div class="card">
    <div class="card-header"><h3>Riwayat Absensi Siswa (1 Bulan Terakhir)</h3></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead><tr><th style="width:120px;">Tanggal</th><th>Nama Siswa</th><th style="width:120px;">Status</th></tr></thead>
                <tbody>
                    <?php foreach($riwayat as $r): ?>
                    <tr>
                        <td><?= date('d/m/Y',strtotime($r['tanggal'])) ?></td>
                        <td><?= htmlspecialchars($r['nama']) ?></td>
                        <td><span class="badge <?= strtolower($r['status']) ?>"><?= $r['status'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
