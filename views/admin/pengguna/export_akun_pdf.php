<?php
/**
 * Daftar Akun Login — untuk simulasi/demo
 * Print-friendly HTML, auto-trigger dialog print via Ctrl+P
 */
$tglCetak = date('d F Y');
$roleLabels = ['admin'=>'Administrator', 'kepala_sekolah'=>'Kepala Sekolah', 'guru'=>'Guru', 'siswa'=>'Siswa'];
$roleIcons  = ['admin'=>'🔑', 'kepala_sekolah'=>'👔', 'guru'=>'👨‍🏫', 'siswa'=>'👨‍🎓'];
$logoPath = BASE_PATH . '/public/img/logo.png';
$logoUrl = file_exists($logoPath) ? base_url('img/logo.png') . '?v=' . filemtime($logoPath) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Login - <?= APP_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:'Poppins',Arial,sans-serif;
            color:#1F2937;
            background:#F5F7FA;
            padding:24px;
            font-size:10.5pt;
            line-height:1.5;
        }
        .page {
            max-width:210mm; margin:0 auto;
            background:#fff;
            padding:18mm 15mm;
            box-shadow:0 8px 30px rgba(0,0,0,.08);
            min-height:297mm;
        }
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

        /* Kop */
        .kop {
            display:flex; align-items:center; gap:14px;
            padding-bottom:12px; border-bottom:3px double #00923F;
            margin-bottom:18px;
        }
        .kop-logo { width:70px; height:70px; flex-shrink:0; }
        .kop-logo img { width:100%; height:100%; object-fit:contain; }
        .kop-logo .placeholder {
            width:70px; height:70px; border-radius:50%;
            background:#00923F; color:#fff;
            display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:11px;
        }
        .kop-text { flex:1; text-align:center; }
        .kop-text h1 { font-size:14pt; font-weight:700; color:#00923F; }
        .kop-text h2 { font-size:16pt; font-weight:800; color:#00923F; }
        .kop-text p { font-size:9pt; color:#4B5563; }

        /* Title */
        .doc-title { text-align:center; margin:12px 0 6px; }
        .doc-title h3 { font-size:13pt; font-weight:700; text-decoration:underline; letter-spacing:.8px; }
        .doc-title p { font-size:9.5pt; color:#4B5563; margin-top:2px; }

        /* Warning banner */
        .warning {
            background:#FFF7E0; border-left:4px solid #F59E0B;
            padding:10px 14px; border-radius:4px;
            margin:14px 0 18px;
            font-size:9.5pt; color:#78350F;
            display:flex; gap:10px; align-items:flex-start;
        }
        .warning strong { display:block; color:#92400E; font-size:10pt; margin-bottom:2px; }

        /* Meta info */
        .meta {
            display:grid; grid-template-columns:1fr 1fr; gap:14px;
            margin-bottom:14px; font-size:9.5pt;
        }
        .meta strong { display:inline-block; width:100px; color:#4B5563; }

        /* Role section */
        .role-section { margin-bottom:14px; page-break-inside:avoid; }
        .role-heading {
            background:linear-gradient(90deg, #00923F, #007532);
            color:#fff;
            padding:8px 14px; border-radius:4px 4px 0 0;
            font-size:11pt; font-weight:600;
            display:flex; justify-content:space-between; align-items:center;
        }
        .role-heading .icon { font-size:14pt; }
        .role-heading .count {
            background:rgba(255,255,255,.22); padding:2px 10px; border-radius:12px;
            font-size:9pt; font-weight:600;
        }

        table { width:100%; border-collapse:collapse; }
        thead th {
            background:#F0FAF3; color:#00923F;
            padding:7px 10px; text-align:left;
            font-size:9pt; font-weight:600; letter-spacing:.3px;
            border-bottom:2px solid #00923F;
        }
        tbody td {
            padding:7px 10px; font-size:10pt;
            border-bottom:1px solid #E5E7EB;
            vertical-align:middle;
        }
        tbody tr:nth-child(even) td { background:#FAFBFC; }
        .username, .password-pill {
            font-family:'JetBrains Mono','Courier New',monospace;
            font-weight:600;
        }
        .username { color:#1F2937; }
        .password-pill {
            display:inline-block;
            background:#E5F4EA; color:#00923F;
            padding:3px 10px; border-radius:4px;
            font-size:10pt; letter-spacing:.4px;
        }
        .password-none {
            color:#9CA3AF; font-style:italic; font-size:9.5pt;
        }
        .empty { padding:14px; text-align:center; color:#9CA3AF; font-style:italic; background:#F9FAFB; }

        /* Signature */
        .signature {
            display:grid; grid-template-columns:1fr 1fr; gap:20px;
            margin-top:28px; page-break-inside:avoid;
        }
        .signature .col { text-align:center; font-size:10pt; }
        .signature .col .sign-space { height:52px; }
        .signature .col .name { font-weight:700; text-decoration:underline; }
        .signature .col .role { font-size:9pt; color:#4B5563; }

        .footer-meta {
            margin-top:16px; padding-top:8px; border-top:1px solid #E5E7EB;
            display:flex; justify-content:space-between;
            font-size:8pt; color:#6B7280;
        }

        @page { size:A4; margin:0; }
        @media print {
            body { background:#fff; padding:0; }
            .toolbar { display:none !important; }
            .page { max-width:none; margin:0; padding:13mm 13mm; box-shadow:none; min-height:auto; }
            .role-section, .signature { page-break-inside:avoid; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="btn btn-secondary" onclick="window.history.back()">← Kembali</button>
        <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="page">
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
            </div>
        </div>

        <div class="doc-title">
            <h3>DAFTAR AKUN LOGIN SISTEM INFORMASI AKADEMIK</h3>
            <p>Untuk keperluan simulasi &amp; uji coba sistem</p>
        </div>

        <div class="warning">
            <span style="font-size:16pt">⚠️</span>
            <div>
                <strong>DOKUMEN INTERNAL — RAHASIA</strong>
                Daftar password di bawah bersifat default/sementara untuk keperluan pengujian sistem. Setelah go-live,
                seluruh pengguna wajib mengganti password masing-masing. Jangan sebarluaskan dokumen ini di luar tim yang berkepentingan.
            </div>
        </div>

        <div class="meta">
            <div>
                <p><strong>Tanggal Cetak</strong>: <?= $tglCetak ?></p>
                <p><strong>Dicetak Oleh</strong>: <?= htmlspecialchars(Auth::user('nama')) ?></p>
            </div>
            <div style="text-align:right">
                <p><strong>Total Akun</strong>: <?= count($users) ?> pengguna</p>
                <p><strong>URL Sistem</strong>: <?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'localhost') ?>/siakad-smknu</p>
            </div>
        </div>

        <?php foreach ($grouped as $role => $usersRole): ?>
            <div class="role-section">
                <div class="role-heading">
                    <span><span class="icon"><?= $roleIcons[$role] ?></span> &nbsp; <?= $roleLabels[$role] ?></span>
                    <span class="count"><?= count($usersRole) ?> akun</span>
                </div>
                <?php if (empty($usersRole)): ?>
                    <div class="empty">Tidak ada akun dengan role ini.</div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width:5%">No</th>
                                <th style="width:24%">Nama</th>
                                <th style="width:18%">Username</th>
                                <th style="width:16%">Password</th>
                                <th style="width:12%">Role</th>
                                <th style="width:15%"><?= $role==='siswa' ? 'Kelas' : ($role==='guru' ? 'Kode / Wali' : 'Keterangan') ?></th>
                                <th style="width:10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usersRole as $i => $u): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($u['nama']) ?></strong></td>
                                    <td><span class="username"><?= htmlspecialchars($u['username']) ?></span></td>
                                    <td>
                                        <?php if ($u['password_plain']): ?>
                                            <span class="password-pill"><?= htmlspecialchars($u['password_plain']) ?></span>
                                        <?php else: ?>
                                            <span class="password-none">(sudah diubah)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span style="font-weight:600;color:#00923F;"><?= $roleLabels[$u['role']] ?? ucfirst($u['role']) ?></span></td>
                                    <td><?= htmlspecialchars($u['info_tambahan'] ?: '—') ?></td>
                                    <td>
                                        <?php if ($u['status']==='aktif'): ?>
                                            <span style="background:#E5F4EA;color:#00923F;padding:2px 8px;border-radius:10px;font-size:8.5pt;font-weight:600">Aktif</span>
                                        <?php else: ?>
                                            <span style="background:#FBE5E5;color:#B32020;padding:2px 8px;border-radius:10px;font-size:8.5pt;font-weight:600"><?= ucfirst($u['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

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

        <div class="footer-meta">
            <span>Dokumen dihasilkan oleh SIAKAD SMK NU Muara Sugihan</span>
            <span><?= date('d/m/Y H:i:s') ?></span>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() { window.print(); }, 400);
        });
    </script>
</body>
</html>
