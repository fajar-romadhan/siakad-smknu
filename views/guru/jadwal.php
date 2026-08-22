<?php
$pageTitle = 'Jadwal Mengajar';
ob_start();

$db = getDB();
require_once BASE_PATH . '/models/GuruModel.php';

$guru = (new GuruModel())->whereOne('user_id', Auth::id());

$jadwal = [];

if ($guru) {
    $st = $db->prepare("
        SELECT
            j.*,
            m.nama_mapel,
            k.nama_kelas
        FROM jadwal j
        JOIN mapel m ON j.mapel_id = m.id
        JOIN kelas k ON j.kelas_id = k.id
        WHERE j.guru_id = ?
        ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
                 j.jam_mulai
    ");
    $st->execute([$guru['id']]);
    $jadwal = $st->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Ambil hanya hari yang memiliki jadwal
|--------------------------------------------------------------------------
*/
$jadwalPerHari = [];

foreach ($jadwal as $j) {
    $jadwalPerHari[$j['hari']][] = $j;
}
?>

<div class="dash-header-row">
    <h2 class="page-title">Jadwal Mengajar</h2>
    <p style="color:var(--gray-500);font-size:13px;">
        Jadwal ditetapkan oleh admin
    </p>
</div>

<div class="card">
    <div class="card-header">
        <h3>Jadwal Mengajar</h3>
    </div>

    <div class="card-body">
        <?php if (empty($jadwal)): ?>
            <p class="text-muted" style="padding:10px;background:var(--gray-50);border-radius:var(--radius-sm);">
                Belum ada jadwal mengajar.
            </p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Hari</th>
                            <th style="width:130px;">Jam</th>
                            <th style="width:120px;">Kode Mapel</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal as $i => $j): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><?= htmlspecialchars($j['hari']) ?></td>
                                <td><?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?></td>
                                <td><?= htmlspecialchars($j['kode_mapel'] ?? '-') ?></td>
                                <td><strong><?= htmlspecialchars($j['nama_mapel']) ?></strong></td>
                                <td><?= htmlspecialchars($j['nama_kelas']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>