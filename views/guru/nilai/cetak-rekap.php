<?php
/** Cetak Rekap Nilai — halaman print-friendly (auto print).
 * Vars: $kelas, $mapel, $rows, $guru
 */
function grade($v){ if($v===null)return '-'; $v=(float)$v; if($v>=85)return 'A'; if($v>=75)return 'B'; if($v>=65)return 'C'; return 'D'; }
?>
<!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8">
<title>Rekap Nilai - <?= htmlspecialchars($kelas['nama_kelas']) ?> - <?= htmlspecialchars($mapel['nama_mapel']) ?></title>
<style>
    *{box-sizing:border-box;} body{font-family:'Times New Roman',serif;color:#000;margin:30px;font-size:12pt;}
    .kop{text-align:center;border-bottom:3px double #000;padding-bottom:10px;margin-bottom:18px;}
    .kop h1{font-size:16pt;margin:0;text-transform:uppercase;}
    .kop p{margin:2px 0;font-size:10pt;}
    .info{margin-bottom:12px;} .info td{padding:2px 6px;font-size:11pt;}
    table.data{width:100%;border-collapse:collapse;margin-top:8px;}
    table.data th,table.data td{border:1px solid #000;padding:6px 8px;font-size:10.5pt;text-align:center;}
    table.data th{background:#e8e8e8;} table.data td.nama{text-align:left;}
    .ttd{margin-top:40px;width:100%;} .ttd td{width:50%;vertical-align:top;font-size:11pt;}
    .no-print{margin-bottom:16px;text-align:center;}
    .btn{padding:8px 18px;font-size:11pt;cursor:pointer;border:1px solid #333;border-radius:4px;background:#00923F;color:#fff;margin:0 4px;text-decoration:none;}
    .btn.sec{background:#666;}
    @media print{ .no-print{display:none;} body{margin:12mm;} }
</style>
</head><body>

<div class="no-print">
    <button class="btn" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    <a class="btn sec" href="<?= base_url('index.php?page=nilai&action=input&kelas_id='.$kelas['id'].'&mapel_id='.$mapel['id']) ?>">← Kembali</a>
</div>

<div class="kop">
    <h1><?= APP_NAME ?></h1>
    <p>Muara Sugihan, Kabupaten Banyuasin, Sumatera Selatan</p>
</div>

<h3 style="text-align:center;text-decoration:underline;margin:6px 0 16px;">REKAP NILAI SISWA</h3>

<table class="info">
    <tr><td>Kelas</td><td>: <?= htmlspecialchars($kelas['nama_kelas']) ?></td>
        <td>Mata Pelajaran</td><td>: <?= htmlspecialchars($mapel['nama_mapel']) ?></td></tr>
    <tr><td>Tahun/Semester</td><td>: <?= htmlspecialchars(($mapel['tingkat']??'').' / '.ucfirst($mapel['semester']??'')) ?></td>
        <td>Guru Pengampu</td><td>: <?= htmlspecialchars($guru['nama']??'-') ?></td></tr>
</table>

<table class="data">
    <thead><tr>
        <th style="width:40px;">No</th><th style="width:110px;">NISN</th><th>Nama Siswa</th>
        <th style="width:60px;">Nilai Harian</th><th style="width:60px;">Tugas</th><th style="width:60px;">Kompetensi / Praktik</th>
        <th style="width:70px;">Akhir</th><th style="width:55px;">Grade</th><th style="width:90px;">Status</th>
    </tr></thead>
    <tbody>
    <?php foreach($rows as $i=>$r): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($r['nisn']) ?></td>
            <td class="nama"><?= htmlspecialchars($r['nama']) ?></td>
            <td><?= $r['nilai_tugas']!==null?$r['nilai_tugas']:'-' ?></td>
            <td><?= $r['nilai_uts']!==null?$r['nilai_uts']:'-' ?></td>
            <td><?= $r['nilai_uas']!==null?$r['nilai_uas']:'-' ?></td>
            <td><strong><?= $r['nilai_akhir']!==null?$r['nilai_akhir']:'-' ?></strong></td>
            <td><?= grade($r['nilai_akhir']) ?></td>
            <td><?= ((int)($r['is_validated']??0)===1)?'Tervalidasi':'Belum' ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<table class="ttd">
    <tr>
        <td></td>
        <td style="text-align:center;">
            Muara Sugihan, <?= date('d F Y') ?><br>Guru Pengampu,<br><br><br><br>
            <strong><u><?= htmlspecialchars($guru['nama']??'') ?></u></strong><br>
            NIP. <?= htmlspecialchars($guru['nip']??'-') ?>
        </td>
    </tr>
</table>

<script>window.addEventListener('load',function(){ setTimeout(function(){ window.print(); }, 400); });</script>
</body></html>
