<?php
/**
 * Rekap Pengguna — Print/PDF view
 * Standalone HTML (tanpa layout wrapper). Optimized untuk A4 print.
 * User buka halaman ini → dialog print browser auto muncul → Save as PDF
 */
$tglCetak = date('d F Y');
$namaHariIni = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][date('w')];
$roleLabels = ['admin'=>'Administrator', 'kepala_sekolah'=>'Kepala Sekolah', 'guru'=>'Guru', 'siswa'=>'Siswa'];
$logoPath = BASE_PATH . '/public/img/logo.png';
$logoUrl = file_exists($logoPath) ? base_url('img/logo.png') . '?v=' . filemtime($logoPath) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pengguna Sistem - <?= APP_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:'Poppins',Arial,sans-serif;
            color:#1F2937;
            background:#F5F7FA;
            padding:24px;
            font-size:11pt;
            line-height:1.5;
        }
        .page {
            max-width:210mm; margin:0 auto;
            background:#fff;
            padding:20mm 18mm;
            box-shadow:0 8px 30px rgba(0,0,0,.08);
            min-height:297mm;
        }
        /* ===== Toolbar (screen only) ===== */
        .toolbar {
            max-width:210mm; margin:0 auto 12px;
            display:flex; gap:10px; justify-content:flex-end;
        }
        .btn {
            display:inline-flex; align-items:center; gap:6px;
            padding:9px 16px; border-radius:8px;
            font-size:13px; font-weight:600; font-family:inherit;
            border:none; cursor:pointer; text-decoration:none;
            transition:transform .15s, box-shadow .15s;
        }
        .btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.12); }
        .btn-primary { background:#00923F; color:#fff; }
        .btn-secondary { background:#E5E7EB; color:#1F2937; }

        /* ===== Kop surat ===== */
        .kop {
            display:flex; align-items:center; gap:16px;
            padding-bottom:14px; border-bottom:3px double #00923F;
            margin-bottom:22px;
        }
        .kop-logo {
            width:80px; height:80px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
        }
        .kop-logo img { width:100%; height:100%; object-fit:contain; }
        .kop-logo .placeholder {
            width:80px; height:80px; border-radius:50%;
            background:#00923F; color:#fff;
            display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:13px; text-align:center;
        }
        .kop-text { flex:1; text-align:center; }
        .kop-text h1 { font-size:16pt; font-weight:700; color:#00923F; letter-spacing:.5px; margin-bottom:2px; }
        .kop-text h2 { font-size:18pt; font-weight:800; color:#00923F; letter-spacing:.5px; margin-bottom:4px; }
        .kop-text p { font-size:9.5pt; color:#4B5563; line-height:1.4; }

        /* ===== Title ===== */
        .doc-title {
            text-align:center; margin:14px 0 8px;
        }
        .doc-title h3 { font-size:14pt; font-weight:700; text-decoration:underline; letter-spacing:1px; margin-bottom:4px; }
        .doc-title p { font-size:10pt; color:#4B5563; }

        /* ===== Info blok ===== */
        .info-block {
            display:grid; grid-template-columns:1fr 1fr; gap:20px;
            margin:18px 0 14px;
        }
        .info-block .col p { font-size:10.5pt; margin-bottom:3px; }
        .info-block .col strong { display:inline-block; width:110px; }

        /* ===== Ringkasan stat ===== */
        .summary {
            display:grid; grid-template-columns:repeat(4,1fr); gap:8px;
            margin:14px 0 20px;
        }
        .summary .box {
            border:1px solid #DEE2E6; border-left:3px solid #00923F;
            padding:8px 12px; border-radius:4px;
        }
        .summary .box .num { font-size:16pt; font-weight:700; color:#00923F; line-height:1; }
        .summary .box .lbl { font-size:9pt; color:#4B5563; margin-top:2px; }

        /* ===== Tabel per role ===== */
        .role-section { margin-bottom:16px; page-break-inside:avoid; }
        .role-heading {
            background:#00923F; color:#fff;
            padding:6px 12px; border-radius:4px 4px 0 0;
            font-size:11pt; font-weight:600; letter-spacing:.3px;
            display:flex; justify-content:space-between; align-items:center;
        }
        .role-heading .count {
            background:rgba(255,255,255,.2); padding:2px 10px; border-radius:12px;
            font-size:9.5pt;
        }
        table { width:100%; border-collapse:collapse; }
        thead th {
            background:#F0FAF3; color:#00923F;
            padding:7px 10px; text-align:left;
            font-size:9.5pt; font-weight:600;
            border-bottom:2px solid #00923F;
        }
        tbody td {
            padding:6px 10px; font-size:10pt;
            border-bottom:1px solid #E5E7EB;
            vertical-align:top;
        }
        tbody tr:nth-child(even) td { background:#FAFBFC; }
        .badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:8.5pt; font-weight:600; }
        .badge.aktif { background:#E5F4EA; color:#00923F; }
        .badge.nonaktif { background:#FBE5E5; color:#B32020; }
        .empty { padding:14px; text-align:center; color:#9CA3AF; font-style:italic; background:#F9FAFB; }

        /* ===== Signature ===== */
        .signature {
            display:grid; grid-template-columns:1fr 1fr; gap:20px;
            margin-top:32px; page-break-inside:avoid;
        }
        .signature .col { text-align:center; font-size:10.5pt; }
        .signature .col p { margin-bottom:2px; }
        .signature .col .sign-space { height:60px; }
        .signature .col .name { font-weight:700; text-decoration:underline; margin-top:4px; }
        .signature .col .role { font-size:9.5pt; color:#4B5563; }

        /* ===== Footer meta ===== */
        .footer-meta {
            margin-top:20px; padding-top:10px; border-top:1px solid #E5E7EB;
            display:flex; justify-content:space-between; align-items:center;
            font-size:8.5pt; color:#6B7280;
        }

        /* ===== PRINT ===== */
        @page { size:A4; margin:0; }
        @media print {
            body { background:#fff; padding:0; }
            .toolbar { display:none !important; }
            .page { max-width:none; margin:0; padding:15mm 15mm; box-shadow:none; min-height:auto; }
            .role-section { page-break-inside:avoid; }
            .signature { page-break-inside:avoid; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="btn btn-secondary" onclick="window.history.back()">← Kembali</button>
        <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="page">
        <!-- Kop surat -->
        <div class="kop">
            <div class="kop-logo">
                <?php if ($logoUrl): ?>
                    <img src="<?= $logoUrl ?>" alt="Logo">
                <?php else: ?>
                    <div class="placeholder">SMK NU</div>
                <?php endif; ?>
            </div>
            <div class="kop-text">
                <h1>YAYASAN NAHDLATUL ULAMA</h1>
                <h2>SMK NU MUARA SUGIHAN</h2>
                <p>Jalan Raya Muara Sugihan, Kabupaten Banyuasin, Sumatera Selatan</p>
                <p>Telp: (0711) xxx-xxxx · Email: info@smknu-mrs.sch.id</p>
            </div>
        </div>

        <!-- Judul dokumen -->
        <div class="doc-title">
            <h3>REKAP DATA PENGGUNA SISTEM INFORMASI AKADEMIK</h3>
            <p>Periode data per <?= $tglCetak ?></p>
        </div>

        <!-- Info -->
        <div class="info-block">
            <div class="col">
                <p><strong>Nomor Dok.</strong>: SIAKAD/<?= date('Y/m') ?>/<?= str_pad(rand(1,999), 3, '0', STR_PAD_LEFT) ?></p>
                <p><strong>Tanggal Cetak</strong>: <?= $namaHariIni ?>, <?= $tglCetak ?></p>
            </div>
            <div class="col" style="text-align:right">
                <p><strong>Dicetak oleh</strong>: <?= htmlspecialchars(Auth::user('nama')) ?></p>
                <p><strong>Sumber Data</strong>: Database SIAKAD SMK NU</p>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="summary">
            <div class="box"><div class="num"><?= $stat['total'] ?></div><div class="lbl">Total Pengguna</div></div>
            <div class="box"><div class="num"><?= $stat['guru'] ?></div><div class="lbl">Guru</div></div>
            <div class="box"><div class="num"><?= $stat['siswa'] ?></div><div class="lbl">Siswa</div></div>
            <div class="box"><div class="num"><?= $stat['admin'] + $stat['kepsek'] ?></div><div class="lbl">Admin & Kepsek</div></div>
        </div>

        <!-- Section per role -->
        <?php $no = 1; foreach ($grouped as $role => $users): ?>
            <div class="role-section">
                <div class="role-heading">
                    <span><?= $roleLabels[$role] ?></span>
                    <span class="count"><?= count($users) ?> orang</span>
                </div>
                <?php if (empty($users)): ?>
                    <div class="empty">Tidak ada data pengguna dengan role ini.</div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width:6%">No</th>
                                <th style="width:20%">Username</th>
                                <th style="width:30%">Nama Lengkap</th>
                                <th style="width:12%">Status</th>
                                <th style="width:16%">Terakhir Login</th>
                                <th style="width:16%">Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $i => $u): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($u['username']) ?></td>
                                    <td><?= htmlspecialchars($u['nama']) ?></td>
                                    <td><span class="badge <?= $u['status']==='aktif'?'aktif':'nonaktif' ?>"><?= ucfirst($u['status']) ?></span></td>
                                    <td><?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '—' ?></td>
                                    <td><?= isset($u['created_at']) && $u['created_at'] ? date('d/m/Y', strtotime($u['created_at'])) : '—' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <!-- Tanda tangan -->
        <div class="signature">
            <div class="col">
                <p>Mengetahui,</p>
                <p>Kepala Sekolah</p>
                <div class="sign-space"></div>
                <p class="name">(_________________________)</p>
                <p class="role">NIP.</p>
            </div>
            <div class="col">
                <p>Muara Sugihan, <?= $tglCetak ?></p>
                <p>Administrator Sistem</p>
                <div class="sign-space"></div>
                <p class="name"><?= htmlspecialchars(Auth::user('nama')) ?></p>
                <p class="role">SIAKAD SMK NU</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-meta">
            <span>Dokumen ini dihasilkan oleh SIAKAD SMK NU Muara Sugihan</span>
            <span>Halaman 1 / 1 · <?= date('d/m/Y H:i:s') ?></span>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog setelah page fully loaded (delay tipis biar font ke-render)
        window.addEventListener('load', function() {
            setTimeout(function() { window.print(); }, 400);
        });
    </script>
</body>
</html>
