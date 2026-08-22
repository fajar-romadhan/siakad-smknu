<?php $pageTitle = 'Rekap Absensi'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Filter Rekap Absensi</h3>
    </div>
    <div class="card-body">
        <form method="get" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;align-items:end">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="absensi">
            <div class="form-group" style="margin:0"><label>Dari</label><input type="date" name="tgl_awal" value="<?= htmlspecialchars($tglAwal) ?>" class="form-control"></div>
            <div class="form-group" style="margin:0"><label>Sampai</label><input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tglAkhir) ?>" class="form-control"></div>
            <div class="form-group" style="margin:0"><label>Kelas</label>
                <select name="kelas_id" class="form-control">
                    <option value="0">Semua Kelas</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $k['id']==$kelasId?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
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
        <h3>Detail Absensi (max 500 baris)</h3>
        <span style="font-size:13px;color:var(--gray-500)"><?= count($data) ?> catatan</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Tanggal</th><th>NISN</th><th>Nama Siswa</th><th>Kelas</th><th>Mapel</th><th>Status</th></tr></thead>
            <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="6" style="text-align:center;padding:20px;color:var(--gray-500)">Tidak ada data absensi pada rentang filter.</td></tr>
            <?php else: foreach ($data as $r):
                $sc = ['Hadir'=>'#00923F','Izin'=>'#B87C00','Sakit'=>'#0056B3','Alpa'=>'#B32020'][$r['status']] ?? '#333';
                $sbg = ['Hadir'=>'#E5F4EA','Izin'=>'#FFF7E5','Sakit'=>'#E5F0FF','Alpa'=>'#FBE5E5'][$r['status']] ?? '#F1F3F5';
            ?>
                <tr>
                    <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($r['nisn'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['siswa_nama'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['nama_kelas'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['nama_mapel'] ?? '-') ?></td>
                    <td><span style="background:<?= $sbg ?>;color:<?= $sc ?>;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600"><?= htmlspecialchars($r['status']) ?></span></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
