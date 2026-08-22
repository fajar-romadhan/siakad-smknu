<?php $pageTitle='Validasi Absensi Guru'; ob_start();
$guruId = isset($_GET['guru_id']) ? (int)$_GET['guru_id'] : 0;
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$namaGuruTerpilih = '';
foreach ($guruList as $g) { if ($g['id'] === $guruId) { $namaGuruTerpilih = $g['nama']; break; } }
?>
<div class="card">
    <div class="card-header">
        <h3>Validasi Absensi Guru</h3>
        <a href="<?= base_url('index.php?page=absensi') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <?php if ($message = flash('success')): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php elseif ($message = flash('error')): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="stats-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:20px;">
            <div class="stat-card sm"><div class="stat-info"><h3><?= $stats['pending'] ?></h3><p>Menunggu Validasi</p></div></div>
            <div class="stat-card sm"><div class="stat-info"><h3><?= $stats['validated'] ?></h3><p>Tervalidasi</p></div></div>
        </div>
        <p style="font-size:13px;color:var(--gray-600);margin-bottom:18px;">Catatan: angka akan berubah setiap hari sesuai absensi guru yang divalidasi oleh admin. Sebelum rekap absensi guru, semua absen hari ini harus divalidasi admin.</p>

        <div class="card" style="padding:0;border:none;box-shadow:none;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Tanggal</th>
                            <th>Nama Guru</th>
                            <th>Status</th>
                            <th>Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr><td colspan="6" style="text-align:center;padding:20px;color:var(--gray-500);">Tidak ada data absensi guru untuk filter ini.</td></tr>
                        <?php else: foreach ($data as $i => $r): ?>
                            <?php
                                $isValidated = ((int)($r['is_validated'] ?? 0) === 1);
                                $badgeClass = $isValidated ? 'aktif' : 'draft';
                                $statusColor = ['Hadir'=>'hadir','Izin'=>'izin','Sakit'=>'sakit','Alpa'=>'alpa'][$r['status']] ?? 'draft';
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= date('d M Y', strtotime($r['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($r['nama']) ?></td>
                                <td><span class="badge <?= $statusColor ?>"><?= htmlspecialchars($r['status']) ?></span></td>
                                <td>
                                    <?php if ($isValidated): ?>
                                        <span class="badge aktif">Tervalidasi</span>
                                        <?php if (!empty($r['validated_by_name'])): ?>
                                            <div style="font-size:12px;color:var(--gray-500);margin-top:4px;">oleh <?= htmlspecialchars($r['validated_by_name']) ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <form method="POST" action="<?= base_url('index.php?page=absensi&action=approveGuru') ?>" style="display:inline-flex;gap:6px;align-items:center;">
                                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
                                            <input type="hidden" name="guru_id" value="<?= $guruId ?>">
                                            <button type="submit" class="btn btn-sm btn-success">Validasi</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>