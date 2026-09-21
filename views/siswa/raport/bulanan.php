<?php $pageTitle='Raport Bulanan'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Raport Bulanan</h2>
    <a href="<?= base_url('index.php?page=raport') ?>" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card">
    <div class="card-header"><h3>Raport Bulanan</h3></div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('index.php') ?>" class="filter-form">
            <input type="hidden" name="page" value="raport">
            <input type="hidden" name="action" value="bulanan">
            <div class="form-row">
                <div class="form-group"><label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <?php foreach(range(1,12) as $m): ?>
                        <option value="<?= $m ?>" <?= $bulan==$m?'selected':'' ?>><?= date('F',strtotime("2020-$m-01")) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Tahun</label>
                    <select name="tahun" class="form-control">
                        <?php foreach(range(date('Y')-2, date('Y')) as $y): ?>
                        <option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="align-self:flex-end;">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </div>
        </form>

        <?php if(empty($nilai)): ?>
            <p class="empty-state">Belum ada nilai tervalidasi untuk periode ini.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th style="width:40px;">No</th><th>Mata Pelajaran</th><th style="width:220px;">Capaian Kompetensi</th><th style="width:70px;">Nilai Harian</th><th style="width:70px;">Tugas</th><th style="width:70px;">Kompetensi / Praktik</th><th style="width:75px;">Nilai Akhir</th><th style="width:55px;">Grade</th></tr></thead>
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
                <?php 
                $sumAkhir = 0; $cntAkhir = 0;
                foreach($nilai as $n) {
                    if ($n['nilai_akhir'] !== null && $n['nilai_akhir'] !== '') {
                        $sumAkhir += (float)$n['nilai_akhir'];
                        $cntAkhir++;
                    }
                }
                $avgAkhir = $cntAkhir > 0 ? round($sumAkhir / $cntAkhir, 2) : 0;
                $grdAvg = $avgAkhir >= 85 ? 'A' : ($avgAkhir >= 75 ? 'B' : ($avgAkhir >= 65 ? 'C' : 'D'));
                ?>
                <tfoot>
                    <tr style="background:#E8F5E9; font-weight:700;">
                        <td colspan="6" style="text-align:right; font-weight:700;">Rata-Rata Nilai Akhir:</td>
                        <td><strong style="color:var(--primary); font-size:15px;"><?= $cntAkhir > 0 ? number_format($avgAkhir, 2) : '-' ?></strong></td>
                        <td><strong style="color:var(--primary);"><?= $cntAkhir > 0 ? $grdAvg : '-' ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>