<?php $pageTitle = 'Jadwal Pelajaran'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Jadwal Pelajaran</h3>
        <form method="get" style="display:flex;gap:8px;align-items:center">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="jadwal">
            <label style="font-size:14px;color:var(--gray-600)">Kelas:</label>
            <select name="kelas_id" onchange="this.form.submit()" class="form-control" style="width:200px">
                <?php foreach ($kelasList as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= $k['id']==$selectedKelas?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Hari</th><th>Jam</th><th>Mata Pelajaran</th><th>Guru</th></tr></thead>
            <tbody>
            <?php if (empty($jadwal)): ?>
                <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--gray-500)">Belum ada jadwal untuk kelas ini.</td></tr>
            <?php else: $lastHari = null; foreach ($jadwal as $j): ?>
                <tr>
                    <td><?= $lastHari !== $j['hari'] ? '<strong>'.htmlspecialchars($j['hari']).'</strong>' : '' ?></td>
                    <td><?= substr($j['jam_mulai'],0,5) ?> – <?= substr($j['jam_selesai'],0,5) ?></td>
                    <td><?= htmlspecialchars($j['nama_mapel'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($j['guru_nama'] ?? '-') ?></td>
                </tr>
                <?php $lastHari = $j['hari']; ?>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
