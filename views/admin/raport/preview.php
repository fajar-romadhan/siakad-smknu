<?php $pageTitle='Preview Raport'; ob_start();
function gr($v){ if($v===null||$v==='')return '-'; $v=(float)$v; if($v>=85)return 'A'; if($v>=75)return 'B'; if($v>=65)return 'C'; return 'D'; }
$bulanLabel = date('F', strtotime('2020-' . ($bulan ?? date('m')) . '-01'));
$backUrl = Auth::role() === 'admin' ? base_url('index.php?page=raport&action=bulanan&kelas_id=' . ($kelas['id'] ?? 0)) : base_url('index.php?page=raport');
if(!$siswa || !$kelas){ echo '<div class="alert alert-danger">Data tidak ditemukan.</div>'; }
?>
<div class="dash-header-row">
    <h2 class="page-title">Preview Raport — <?= htmlspecialchars($siswa['nama']??'') ?></h2>
    <a href="<?= $backUrl ?>" class="btn btn-secondary">← Kembali</a>
</div>

<?php if($adaBelumValidasi): ?>
<div class="alert alert-danger" style="font-weight:600;">
    ⚠ Terdapat nilai yang <u>BELUM DIVALIDASI</u> oleh guru. Nilai belum divalidasi tidak akan tercetak di raport. Minta guru pengampu memvalidasi nilai terlebih dahulu.
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Data Siswa</h3></div>
    <div class="card-body" style="padding-top:10px;">
        <div class="alert alert-info" style="margin-bottom:12px;">Periode: <strong><?= htmlspecialchars($bulanLabel) ?> <?= htmlspecialchars($tahun) ?></strong></div>
    </div>
    <div class="card-body">
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
                <thead><tr><th style="width:50px;">No</th><th>Mata Pelajaran</th><th style="width:80px;">Nilai Harian</th><th style="width:80px;">Tugas</th><th style="width:80px;">Kompetensi / Praktik</th><th style="width:90px;">Akhir</th><th style="width:220px;">Capaian Kompetensi</th><th style="width:70px;">Grade</th><th style="width:130px;">Status</th></tr></thead>
                <tbody>
                <?php foreach($nilai as $i=>$n): $val=(int)($n['is_validated']??0)===1; ?>
                    <tr<?= $val?'':' style="background:#fff6f6;"' ?>>
                        <td><?= $i+1 ?></td>
                        <td><strong><?= htmlspecialchars($n['nama_mapel']) ?></strong></td>
                        <td><?= $n['nilai_tugas']??'-' ?></td>
                        <td><?= $n['nilai_uts']??'-' ?></td>
                        <td><?= $n['nilai_uas']??'-' ?></td>
                        <td><strong><?= $n['nilai_akhir']??'-' ?></strong></td>
                        <td><?= htmlspecialchars($n['capaian_kompetensi']?:'-') ?></td>
                        <td><?= gr($n['nilai_akhir']) ?></td>
                        <td>
                            <?= $val?'<span class="badge aktif">Tervalidasi</span>':'<span class="badge draft">Belum divalidasi</span>' ?>
                            <?php if ($val && Auth::role() === 'admin'): ?>
                                <br>
                                <a href="<?= base_url('index.php?page=nilai&action=bukaKunci&kelas_id='.$kelas['id'].'&mapel_id='.$n['mapel_id'].'&bulan='.$bulan.'&tahun='.$tahun.'&siswa_id='.$siswa['id']) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   style="padding:2px 6px; font-size:10px; margin-top:2px;" 
                                   onclick="return confirm('Apakah Anda yakin ingin membuka kunci nilai ini agar dapat diedit kembali oleh Guru?')">
                                    🔓 Buka Kunci
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?> 
                </tbody>
                <?php 
                $sumAkhir = 0; $cntAkhir = 0;
                foreach($nilai as $nItem) {
                    if ($nItem['nilai_akhir'] !== null && $nItem['nilai_akhir'] !== '') {
                        $sumAkhir += (float)$nItem['nilai_akhir'];
                        $cntAkhir++;
                    }
                }
                $avgAkhir = $cntAkhir > 0 ? round($sumAkhir / $cntAkhir, 2) : 0;
                ?>
                <tfoot>
                    <tr style="background:#E8F5E9; font-weight:700;">
                        <td colspan="5" style="text-align:right; font-weight:700;">Rata-Rata Nilai Akhir:</td>
                        <td><strong style="color:var(--primary); font-size:15px;"><?= $cntAkhir > 0 ? number_format($avgAkhir, 2) : '-' ?></strong></td>
                        <td colspan="3"><strong style="color:var(--primary);"><?= $cntAkhir > 0 ? gr($avgAkhir) : '-' ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-actions">
            <a href="<?= base_url('index.php?page=raport&action=cetak&kelas_id='.$kelas['id'].'&siswa_id='.$siswa['id'].'&bulan='.$bulan.'&tahun='.$tahun) ?>" target="_blank" class="btn btn-primary">🖨 Cetak Raport (PDF)</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
