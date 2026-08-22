<?php $pageTitle='Preview Raport'; ob_start();
function gr($v){ if($v===null||$v==='')return '-'; $v=(float)$v; if($v>=85)return 'A'; if($v>=75)return 'B'; if($v>=65)return 'C'; return 'D'; }
$backUrl = base_url('index.php?page=raport');
$bulan = (int)($_GET['bulan'] ?? date('m'));
$tahun = (int)($_GET['tahun'] ?? date('Y'));
$bulanLabel = date('F', strtotime('2020-' . $bulan . '-01'));
?>
<div class="dash-header-row">
    <h2 class="page-title">Preview Raport</h2>
    <a href="<?= $backUrl ?>" class="btn btn-secondary">← Kembali</a>
</div>
<?php if(!$siswa || !$kelas): ?>
    <div class="alert alert-danger">Data tidak ditemukan.</div>
<?php else: ?>
    <?php if($adaBelumValidasi): ?>
        <div class="alert alert-danger" style="font-weight:600;">
            ⚠ Terdapat nilai yang <u>BELUM DIVALIDASI</u> oleh guru. Nilai belum divalidasi tidak akan tercetak di raport.
        </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header"><h3>Data Siswa</h3></div>
        <div class="card-body">
            <div class="alert alert-info" style="margin-bottom:12px;">Periode: <strong><?= htmlspecialchars($bulanLabel) ?> <?= htmlspecialchars($tahun) ?></strong></div>
            <div class="detail-grid">
                <div class="detail-item"><label>Nama</label><span><?= htmlspecialchars($siswa['nama']) ?></span></div>
                <div class="detail-item"><label>NISN</label><span><?= htmlspecialchars($siswa['nisn']) ?></span></div>
                <div class="detail-item"><label>Kelas</label><span><?= htmlspecialchars($kelas['nama_kelas']) ?></span></div>
                <div class="detail-item"><label>Wali Kelas</label><span><?= htmlspecialchars($wali['nama']??'-') ?></span></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Daftar Nilai</h3></div>
        <div class="card-body">
            <?php if(empty($nilai)): ?>
                <p class="empty-state">Belum ada nilai untuk siswa ini di kelas tersebut.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th style="width:50px;">No</th><th>Mata Pelajaran</th><th style="width:220px;">Capaian Kompetensi</th><th style="width:80px;">Nilai Harian</th><th style="width:80px;">Tugas</th><th style="width:80px;">Kompetensi / Praktik</th><th style="width:90px;">Akhir</th><th style="width:70px;">Grade</th></tr></thead>
                    <tbody>
                        <?php foreach($nilai as $i=>$n): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><strong><?= htmlspecialchars($n['nama_mapel']) ?></strong></td>
                            <td><?= htmlspecialchars($n['capaian_kompetensi']?:'-') ?></td>
                            <td><?= $n['nilai_tugas']??'-' ?></td>
                            <td><?= $n['nilai_uts']??'-' ?></td>
                            <td><?= $n['nilai_uas']??'-' ?></td>
                            <td><?= $n['nilai_akhir']??'-' ?></td>
                            <td><?= $n['nilai_akhir']!==null ? (int)$n['nilai_akhir'] >= 85 ? 'A' : ((int)$n['nilai_akhir'] >= 75 ? 'B' : ((int)$n['nilai_akhir'] >= 65 ? 'C' : 'D')) : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=raport&action=cetak&kelas_id='.$kelas['id'].'&siswa_id='.$siswa['id'].'&bulan='.$bulan.'&tahun='.$tahun) ?>" target="_blank" class="btn btn-primary">🖨 Cetak Raport (PDF)</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>