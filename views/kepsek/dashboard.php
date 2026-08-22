<?php $pageTitle = $pageTitle ?? 'Dashboard'; ob_start(); ?>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">👨‍🏫</div><div class="stat-info"><h3><?= $stat['guru'] ?></h3><p>Total Guru</p></div></div>
    <div class="stat-card"><div class="stat-icon">👨‍🎓</div><div class="stat-info"><h3><?= $stat['siswa'] ?></h3><p>Total Siswa</p></div></div>
    <div class="stat-card"><div class="stat-icon">🏫</div><div class="stat-info"><h3><?= $stat['kelas'] ?></h3><p>Total Kelas</p></div></div>
    <div class="stat-card"><div class="stat-icon">📚</div><div class="stat-info"><h3><?= $stat['mapel'] ?></h3><p>Mata Pelajaran</p></div></div>
</div>

<div class="grid-2col" style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:22px">
    <div class="card">
        <div class="card-header"><h3>Kehadiran Siswa Hari Ini</h3><span style="font-size:13px;color:var(--gray-500)"><?= date('d M Y') ?></span></div>
        <div class="card-body">
            <?php if ($totalAbsHari === 0): ?>
                <p style="color:var(--gray-500);text-align:center;padding:16px 0">Belum ada data absensi hari ini.</p>
            <?php else: ?>
                <div style="display:flex;gap:12px;margin-bottom:14px">
                    <div style="flex:1;background:#E5F4EA;padding:12px;border-radius:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#00923F"><?= $absensiHariIni['Hadir'] ?></div><small style="color:#00923F">Hadir</small></div>
                    <div style="flex:1;background:#FFF7E5;padding:12px;border-radius:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#B87C00"><?= $absensiHariIni['Izin'] ?></div><small style="color:#B87C00">Izin</small></div>
                    <div style="flex:1;background:#E5F0FF;padding:12px;border-radius:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#0056B3"><?= $absensiHariIni['Sakit'] ?></div><small style="color:#0056B3">Sakit</small></div>
                    <div style="flex:1;background:#FBE5E5;padding:12px;border-radius:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:#B32020"><?= $absensiHariIni['Alpa'] ?></div><small style="color:#B32020">Alpa</small></div>
                </div>
                <div style="background:#F1F3F5;height:8px;border-radius:4px;overflow:hidden">
                    <div style="width:<?= $persenHadir ?>%;height:100%;background:linear-gradient(90deg,#00923F,#00A548);transition:width 1s"></div>
                </div>
                <p style="margin-top:8px;font-size:13px;color:var(--gray-600)"><?= $persenHadir ?>% siswa hadir hari ini</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Kehadiran Guru Hari Ini</h3><span style="font-size:13px;color:var(--gray-500)"><?= date('d M Y') ?></span></div>
        <div class="card-body">
            <?php $totalGuruAbs = array_sum($absGuruHari); if ($totalGuruAbs === 0): ?>
                <p style="color:var(--gray-500);text-align:center;padding:16px 0">Belum ada data absensi guru hari ini.</p>
            <?php else: ?>
                <div style="display:flex;gap:12px">
                    <?php foreach ($absGuruHari as $lbl => $jml):
                        $col = ['Hadir'=>['#E5F4EA','#00923F'],'Izin'=>['#FFF7E5','#B87C00'],'Sakit'=>['#E5F0FF','#0056B3'],'Alpa'=>['#FBE5E5','#B32020']][$lbl];
                    ?>
                        <div style="flex:1;background:<?= $col[0] ?>;padding:12px;border-radius:10px;text-align:center"><div style="font-size:22px;font-weight:700;color:<?= $col[1] ?>"><?= $jml ?></div><small style="color:<?= $col[1] ?>"><?= $lbl ?></small></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid-2col" style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:22px">
    <div class="card">
        <div class="card-header"><h3>Distribusi Nilai</h3><span style="font-size:13px;color:var(--gray-500)">Rata-rata: <strong style="color:var(--primary)"><?= $avgNilai ?></strong></span></div>
        <div class="card-body">
            <?php $maxN = max(1, max($distNilai));
                foreach ($distNilai as $grade => $jml):
                    $pct = round(($jml/$maxN)*100);
                    $lbl = ['A'=>'A (≥85)','B'=>'B (75–84)','C'=>'C (65–74)','D'=>'D (<65)'][$grade];
                    $c = ['A'=>'#00923F','B'=>'#4CAF50','C'=>'#FFC107','D'=>'#DC3545'][$grade];
            ?>
                <div style="margin-bottom:10px">
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px"><span><?= $lbl ?></span><strong><?= $jml ?></strong></div>
                    <div style="background:#F1F3F5;height:8px;border-radius:4px;overflow:hidden"><div style="width:<?= $pct ?>%;height:100%;background:<?= $c ?>;transition:width 1s"></div></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Pengumuman Terbaru</h3></div>
        <div class="card-body">
            <?php if (empty($pengumuman)): ?>
                <p style="color:var(--gray-500);text-align:center;padding:16px 0">Belum ada pengumuman.</p>
            <?php else: foreach ($pengumuman as $p): ?>
                <div style="padding:10px 0;border-bottom:1px solid var(--stroke-light)">
                    <strong style="color:var(--gray-800);font-size:14px"><?= htmlspecialchars($p['judul']) ?></strong>
                    <div style="font-size:12px;color:var(--gray-500);margin-top:2px"><?= date('d M Y', strtotime($p['tanggal_publish'])) ?></div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
