<?php $pageTitle = 'Dashboard Admin'; ob_start(); ?>
<style>
/* ===== CHART STYLES (scoped) ===== */
.dashboard-grid { display:grid; grid-template-columns:1.5fr 1fr; gap:22px; margin-top:6px; }
@media(max-width:1024px){ .dashboard-grid{ grid-template-columns:1fr; } }

.chart-card .card-body { padding:14px 18px 18px; }
.chart-meta { display:flex; align-items:baseline; gap:12px; margin-bottom:10px; }
.chart-meta .val { font-size:28px; font-weight:700; color:var(--gray-800); font-family:'Poppins',sans-serif; line-height:1; }
.chart-meta .lbl { font-size:13px; color:var(--gray-500); }
.chart-meta .delta { margin-left:auto; font-size:12px; font-weight:600; padding:4px 10px; border-radius:12px; background:#E5F4EA; color:#00923F; }

.chart-wrap { position:relative; width:100%; }
.chart-svg { width:100%; height:auto; max-height:240px; display:block; overflow:visible; }
.chart-svg .grid-line { stroke:#EEF1F4; stroke-width:1; stroke-dasharray:3 4; }
.chart-svg .axis-y { fill:#94A3AD; font-family:'Poppins',sans-serif; font-size:10px; font-weight:500; }
.chart-svg .axis-x { fill:#94A3AD; font-family:'Poppins',sans-serif; font-size:11px; font-weight:500; }
.chart-svg .area { fill:url(#gradArea); }
.chart-svg .line { fill:none; stroke:#00923F; stroke-width:2.2; stroke-linecap:round; stroke-linejoin:round; filter:drop-shadow(0 3px 6px rgba(0,146,63,.25)); }
.chart-svg .dot { fill:#fff; stroke:#00923F; stroke-width:2; transition:r .2s; cursor:pointer; }
.chart-svg .dot:hover { r:5.5; }

.chart-legend { display:flex; align-items:center; gap:8px; font-size:12px; color:var(--gray-500); margin-top:6px; padding-left:4px; }
.chart-legend .dot-lg { width:8px; height:8px; border-radius:50%; background:#00923F; }

/* Info list */
.info-list { list-style:none; padding:0; margin:0; }
.info-list li {
    padding:14px 0 14px 22px;
    border-bottom:1px solid #F1F3F5;
    font-size:13.5px;
    color:var(--gray-700);
    display:flex;
    flex-direction:column;
    gap:3px;
    position:relative;
    transition:transform .2s ease, background .2s ease;
    border-radius:6px;
}
.info-list li::before {
    content:'';
    position:absolute;
    left:6px;
    top:20px;
    width:8px;
    height:8px;
    border-radius:50%;
    background:var(--primary);
    box-shadow:0 0 0 3px rgba(0,146,63,.12);
    flex-shrink:0;
    transition:transform .2s ease;
}
.info-list li:hover { background:#F9FBFA; transform:none; padding-left:24px; }
.info-list li:hover::before { transform:scale(1.15); }
.info-list li:last-child { border-bottom:none; }
.info-list li small { color:var(--gray-500); font-size:12px; }
.empty-state { text-align:center; color:var(--gray-500); padding:24px 0; font-size:13px; }

/* Header row */
.dash-header-row { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:14px; }
.page-title { font-size:20px; font-weight:700; color:var(--gray-800); font-family:'Poppins',sans-serif; }
</style>

<div class="dash-header-row">
    <h2 class="page-title">Dashboard Admin</h2>
    <div class="search-bar-figma">
        <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" placeholder="Search">
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-info"><h3><?= $totalPengguna ?? ($totalGuru+$totalSiswa+1) ?></h3><p>Total Pengguna</p></div></div>
    <div class="stat-card"><div class="stat-icon">👨‍🏫</div><div class="stat-info"><h3><?= $totalGuru ?></h3><p>Guru</p></div></div>
    <div class="stat-card"><div class="stat-icon">👨‍🎓</div><div class="stat-info"><h3><?= $totalSiswa ?></h3><p>Siswa</p></div></div>
    <div class="stat-card"><div class="stat-icon">🏫</div><div class="stat-info"><h3><?= $totalKelas ?></h3><p>Kelas</p></div></div>
</div>

<div class="dashboard-grid">
    <div class="card chart-card">
        <div class="card-header"><h3>Aktivitas Pengguna</h3><span style="font-size:12px;color:var(--gray-500)">7 hari terakhir</span></div>
        <div class="card-body">
            <?php
            $aktivitas = $aktivitas ?? [];
            if (empty($aktivitas)) {
                $aktivitas = [['label'=>'Sen','total'=>0],['label'=>'Sel','total'=>0],['label'=>'Rab','total'=>0],['label'=>'Kam','total'=>0],['label'=>'Jum','total'=>0],['label'=>'Sab','total'=>0]];
            }
            $labels = array_column($aktivitas, 'label');
            $values = array_column($aktivitas, 'total');
            $totalAkt = array_sum($values);
            $avgAkt = count($values) ? round($totalAkt / count($values), 1) : 0;
            $maxV = max(1, max($values));
            // viewBox proportions (landscape kompak)
            $w = 600; $h = 200;
            $padL = 30; $padR = 12; $padT = 12; $padB = 26;
            $iw = $w - $padL - $padR;
            $ih = $h - $padT - $padB;
            $n = count($values);
            $step = $n > 1 ? $iw / ($n - 1) : 0;
            $pts = [];
            foreach ($values as $i => $v) {
                $x = $padL + ($step * $i);
                $y = $padT + $ih - ($v / $maxV) * $ih;
                $pts[] = ['x'=>round($x,2), 'y'=>round($y,2), 'label'=>$labels[$i], 'v'=>$v];
            }
            // Smooth path using Catmull-Rom → Bezier
            $smoothPath = 'M ' . $pts[0]['x'] . ' ' . $pts[0]['y'];
            for ($i = 0; $i < $n - 1; $i++) {
                $p0 = $i > 0 ? $pts[$i-1] : $pts[$i];
                $p1 = $pts[$i];
                $p2 = $pts[$i+1];
                $p3 = $i < $n - 2 ? $pts[$i+2] : $pts[$i+1];
                $c1x = round($p1['x'] + ($p2['x'] - $p0['x']) / 6, 2);
                $c1y = round($p1['y'] + ($p2['y'] - $p0['y']) / 6, 2);
                $c2x = round($p2['x'] - ($p3['x'] - $p1['x']) / 6, 2);
                $c2y = round($p2['y'] - ($p3['y'] - $p1['y']) / 6, 2);
                $smoothPath .= " C {$c1x} {$c1y} {$c2x} {$c2y} {$p2['x']} {$p2['y']}";
            }
            $areaPath = $smoothPath . " L {$pts[$n-1]['x']} " . ($padT + $ih) . " L {$pts[0]['x']} " . ($padT + $ih) . " Z";
            $labelY = $padT + $ih + 16;
            ?>
            <div class="chart-meta">
                <span class="val"><?= $totalAkt ?></span>
                <span class="lbl">total aktivitas · rata-rata <?= $avgAkt ?>/hari</span>
                <span class="delta">▲ 7d</span>
            </div>
            <div class="chart-wrap">
                <svg class="chart-svg" viewBox="0 0 <?= $w ?> <?= $h ?>" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradArea" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#00923F" stop-opacity="0.28"/>
                            <stop offset="100%" stop-color="#00923F" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <?php for ($g = 0; $g <= 3; $g++): $gy = $padT + ($ih / 3) * $g; ?>
                        <line class="grid-line" x1="<?= $padL ?>" y1="<?= $gy ?>" x2="<?= $padL + $iw ?>" y2="<?= $gy ?>"/>
                        <text class="axis-y" x="<?= $padL - 6 ?>" y="<?= $gy + 3 ?>" text-anchor="end"><?= round($maxV - ($maxV / 3) * $g) ?></text>
                    <?php endfor; ?>
                    <path class="area" d="<?= $areaPath ?>"/>
                    <path class="line" d="<?= $smoothPath ?>"/>
                    <?php foreach ($pts as $p): ?>
                        <circle class="dot" cx="<?= $p['x'] ?>" cy="<?= $p['y'] ?>" r="3.5"><title><?= $p['label'] ?>: <?= $p['v'] ?></title></circle>
                        <text class="axis-x" x="<?= $p['x'] ?>" y="<?= $labelY ?>" text-anchor="middle"><?= $p['label'] ?></text>
                    <?php endforeach; ?>
                </svg>
            </div>
            <div class="chart-legend"><span class="dot-lg"></span> Aktivitas login harian (absen guru + siswa)</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Informasi Terbaru</h3></div>
        <div class="card-body">
            <?php if (empty($infoTerbaru)): ?>
                <div class="empty-state">Belum ada aktivitas terbaru</div>
            <?php else: ?>
                <ul class="info-list">
                    <?php foreach ($infoTerbaru as $info): ?>
                        <li><?= htmlspecialchars($info['pesan']) ?><small><?= htmlspecialchars($info['waktu']) ?></small></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
