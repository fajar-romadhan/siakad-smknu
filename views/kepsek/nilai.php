<?php $pageTitle = 'Rekap Nilai'; ob_start(); ?>
<div class="card">
    <div class="card-header"><h3>Filter Rekap Nilai</h3></div>
    <div class="card-body">
        <form method="get" style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;align-items:end">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="nilai">
            <div class="form-group" style="margin:0"><label>Kelas</label>
                <select name="kelas_id" class="form-control">
                    <option value="0">Semua Kelas</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $k['id']==$kelasId?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0"><label>Mata Pelajaran</label>
                <select name="mapel_id" class="form-control">
                    <option value="0">Semua Mapel</option>
                    <?php foreach ($mapelList as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $m['id']==$mapelId?'selected':'' ?>><?= htmlspecialchars($m['nama_mapel']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:22px">
    <div style="background:var(--white);padding:16px;border-radius:10px;border:1px solid var(--stroke-light);text-align:center">
        <div style="font-size:26px;font-weight:700;color:var(--primary)"><?= $avg ?></div><small style="color:var(--gray-600);font-weight:600">Rata-rata Akhir</small>
    </div>
    <?php foreach ($ring as $grade => $jml):
        $col = ['A'=>['#E5F4EA','#00923F'],'B'=>['#E8F5E9','#4CAF50'],'C'=>['#FFF7E0','#B87C00'],'D'=>['#FBE5E5','#B32020']][$grade];
    ?>
        <div style="background:<?= $col[0] ?>;padding:16px;border-radius:10px;text-align:center">
            <div style="font-size:26px;font-weight:700;color:<?= $col[1] ?>"><?= $jml ?></div>
            <small style="color:<?= $col[1] ?>;font-weight:600">Grade <?= $grade ?></small>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Detail Nilai (max 500 baris)</h3>
        <span style="font-size:13px;color:var(--gray-500)"><?= count($data) ?> catatan</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>NISN</th><th>Nama Siswa</th><th>Kelas</th><th>Mata Pelajaran</th><th style="text-align:center">Nilai Harian</th><th style="text-align:center">Tugas</th><th style="text-align:center">Kompetensi / Praktik</th><th style="text-align:center">Akhir</th><th style="text-align:center">Grade</th></tr></thead>
            <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="9" style="text-align:center;padding:20px;color:var(--gray-500)">Tidak ada data nilai pada filter tersebut.</td></tr>
            <?php else: foreach ($data as $r):
                $v = $r['nilai_akhir'] !== null ? (float)$r['nilai_akhir'] : null;
                if ($v === null) { $g='—'; $c='#ADB5BD'; }
                elseif ($v >= 85) { $g='A'; $c='#00923F'; }
                elseif ($v >= 75) { $g='B'; $c='#4CAF50'; }
                elseif ($v >= 65) { $g='C'; $c='#B87C00'; }
                else { $g='D'; $c='#B32020'; }
                $fmt = function($x){ return $x !== null ? number_format((float)$x, 1) : '<span style="color:#ADB5BD">—</span>'; };
            ?>
                <tr>
                    <td><?= htmlspecialchars($r['nisn'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['siswa_nama'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['nama_kelas'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['nama_mapel'] ?? '-') ?></td>
                    <td style="text-align:center"><?= $fmt($r['nilai_tugas']) ?></td>
                    <td style="text-align:center"><?= $fmt($r['nilai_uts']) ?></td>
                    <td style="text-align:center"><?= $fmt($r['nilai_uas']) ?></td>
                    <td style="text-align:center;font-weight:700;color:var(--primary)"><?= $fmt($r['nilai_akhir']) ?></td>
                    <td style="text-align:center"><span style="background:<?= $c ?>1A;color:<?= $c ?>;padding:3px 10px;border-radius:12px;font-weight:700"><?= $g ?></span></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
