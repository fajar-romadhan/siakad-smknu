<?php $pageTitle='Input Nilai'; ob_start();
$adaValidasi = false; $semuaTervalidasi = count($siswaList)>0;
foreach($siswaList as $s){ if((int)($s['is_validated']??0)===1) $adaValidasi=true; if((int)($s['is_validated']??0)!==1) $semuaTervalidasi=false; }
$bulanLabel = date('F', strtotime('2020-'.$bulan.'-01'));
?>
<div class="dash-header-row">
    <h2 class="page-title">Input Nilai Bulanan - <?= htmlspecialchars(format_kelas($kelas['nama_kelas'])) ?> - <?= htmlspecialchars($mapel['nama_mapel']) ?></h2>
    <a href="<?= base_url('index.php?page=nilai') ?>" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card" style="margin-bottom:16px;">
    <div class="card-header"><h3>Input Nilai Bulanan</h3></div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('index.php') ?>" class="filter-form">
            <input type="hidden" name="page" value="nilai">
            <input type="hidden" name="action" value="input">
            <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($kelas['id'] ?? 0) ?>">
            <input type="hidden" name="mapel_id" value="<?= htmlspecialchars($mapel['id'] ?? 0) ?>">
            <div class="form-row">
                <div class="form-group"><label>Kelas</label><div class="form-control" style="background:#f8f9fa;"><?= htmlspecialchars($kelas['nama_kelas'] ?? '-') ?></div></div>
                <div class="form-group"><label>Mata Pelajaran</label><div class="form-control" style="background:#f8f9fa;"><?= htmlspecialchars($mapel['nama_mapel'] ?? '-') ?></div></div>
                <div class="form-group"><label>Bulan</label><select name="bulan" class="form-control">
                    <?php foreach(range(1,12) as $m): ?><option value="<?= $m ?>" <?= $bulan==$m?'selected':'' ?>><?= date('F',strtotime("2020-$m-01")) ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group"><label>Tahun</label><select name="tahun" class="form-control">
                    <?php foreach(range(date('Y')-2, date('Y')) as $y): ?><option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>><?= $y ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group" style="align-self:flex-end;"><button type="submit" class="btn btn-primary">Tampilkan</button></div>
            </div>
        </form>
    </div>
</div>

<?php if(!empty($msg)): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>Daftar Nilai Siswa</h3>
        <p style="color:var(--gray-500);font-size:12px;">Formula: <strong>Nilai Harian 30% + Tugas 30% + Kompetensi / Praktik 40%</strong>. Nilai yang sudah <b>divalidasi</b> terkunci dan tidak bisa diubah.</p>
    </div>
    <div class="card-body">
        <?php if(empty($siswaList)): ?>
            <p class="empty-state">Belum ada siswa di kelas ini.</p>
        <?php else: ?>
        <form method="POST" action="<?= base_url('index.php?page=nilai&action=store') ?>">
            <input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">
            <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
            <input type="hidden" name="bulan" value="<?= $bulan ?>">
            <input type="hidden" name="tahun" value="<?= $tahun ?>">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:60px;">No</th>
                            <th>Nama Siswa</th>
                            <th style="width:110px;">Nilai Harian</th>
                            <th style="width:110px;">Tugas</th>
                            <th style="width:110px;">Kompetensi / Praktik</th>
                            <th style="width:250px;">Capaian Kompetensi</th>
                            <th style="width:100px;">Nilai Akhir</th>
                            <th style="width:110px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($siswaList as $i => $s):
                            $locked = (int)($s['is_validated']??0)===1;
                            $dis = $locked ? 'readonly' : '';
                        ?>
                        <tr<?= $locked?' style="background:#f6fbf7;"':'' ?>>
                            <td><?= $i+1 ?></td>
                            <td><strong><?= htmlspecialchars($s['nama']) ?></strong></td>
                            <td><input type="number" name="nilai[<?= $s['id'] ?>][tugas]" class="form-control" min="0" max="100" step="0.01" value="<?= $s['nilai_tugas']??'' ?>" style="padding:6px 10px;" <?= $dis ?>></td>
                            <td><input type="number" name="nilai[<?= $s['id'] ?>][uts]" class="form-control" min="0" max="100" step="0.01" value="<?= $s['nilai_uts']??'' ?>" style="padding:6px 10px;" <?= $dis ?>></td>
                            <td><input type="number" name="nilai[<?= $s['id'] ?>][uas]" class="form-control" min="0" max="100" step="0.01" value="<?= $s['nilai_uas']??'' ?>" style="padding:6px 10px;" <?= $dis ?>></td>
                            <td>
                                <textarea name="nilai[<?= $s['id'] ?>][capaian_kompetensi]" class="form-control" rows="2" <?= $dis ?> style="min-height:60px;"><?= htmlspecialchars($s['capaian_kompetensi'] ?? '') ?></textarea>
                            </td>
                            <td>
                                <?php if($s['nilai_akhir']!==null): ?>
                                    <strong><?= htmlspecialchars($s['nilai_akhir']) ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($locked): ?>
                                    <span class="badge aktif">🔒 Tervalidasi</span>
                                <?php else: ?>
                                    <span class="badge draft">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=nilai&action=cetakRekap&kelas_id='.$kelas['id'].'&mapel_id='.$mapel['id'].'&bulan='.$bulan.'&tahun='.$tahun) ?>" target="_blank" class="btn btn-secondary">🖨 Cetak Rekap Nilai</a>
                <?php if(!$semuaTervalidasi): ?>
                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                <?php endif; ?>
            </div>
        </form>

        <?php if(!$semuaTervalidasi): ?>
        <form method="POST" action="<?= base_url('index.php?page=nilai&action=validasi') ?>" onsubmit="return confirm('Validasi akan MENGUNCI semua nilai yang sudah lengkap. Nilai tidak dapat diubah lagi. Lanjutkan?');" style="margin-top:12px;border-top:1px dashed var(--gray-200);padding-top:16px;">
            <input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">
            <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
            <input type="hidden" name="bulan" value="<?= $bulan ?>">
            <input type="hidden" name="tahun" value="<?= $tahun ?>">
            <button type="submit" class="btn btn-success">✔ Validasi & Kunci Nilai (untuk Raport)</button>
            <small style="color:var(--gray-400);display:block;margin-top:6px;">Hanya nilai yang sudah lengkap (Nilai Harian, Tugas, Kompetensi / Praktik terisi) yang akan dikunci.</small>
        </form>
        <?php else: ?>
        <div class="alert alert-success" style="margin-top:12px;">Semua nilai kelas ini sudah divalidasi & terkunci.</div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="card" style="margin-top:16px;">
    <div class="card-header"><h3>Riwayat Nilai Bulanan</h3></div>
    <div class="card-body">
        <?php if(empty($history)): ?>
            <p class="empty-state">Belum ada riwayat nilai untuk periode ini.</p>
        <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;">
            <?php foreach($history as $item): ?>
            <a href="<?= base_url('index.php?page=nilai&action=input&kelas_id='.$kelas['id'].'&mapel_id='.$mapel['id'].'&bulan='.$item['bulan'].'&tahun='.$item['tahun']) ?>" style="display:block;padding:12px 14px;border:1px solid var(--gray-200);border-radius:8px;text-decoration:none;color:var(--gray-700);background:var(--white);">
                <strong><?= htmlspecialchars($item['label']) ?> <?= $item['tahun'] ?></strong><br>
                <small style="color:var(--gray-500);">Status: <?= htmlspecialchars($item['status']) ?></small>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
