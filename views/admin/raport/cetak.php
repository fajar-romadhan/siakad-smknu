<?php
/** Cetak Raport PDF (HTML template). Hanya nilai TERVALIDASI yang tampil.
 * Vars: $siswa,$kelas,$wali,$nilai,$absensi,$bulan,$tahun,$status
 */
if (!function_exists('grd')) {
    function grd($v){
        if($v===null||$v==='') return '-';
        $v=(float)$v;
        if($v>=85) return 'A';
        if($v>=75) return 'B';
        if($v>=65) return 'C';
        return 'D';
    }
}
if (!function_exists('pred')) {
    function pred($v){
        if($v===null||$v==='') return '-';
        $v=(float)$v;
        if($v>=85) return 'Sangat Baik';
        if($v>=75) return 'Baik';
        if($v>=65) return 'Cukup';
        return 'Perlu Bimbingan';
    }
}
if (!function_exists('formatTanggal')) {
    function formatTanggal($value){
        if(!$value) return '-';
        try { return date('d F Y', strtotime($value)); } catch (Exception $e) { return '-'; }
    }
}
if (!function_exists('formatBulan')) {
    function formatBulan($bulan){
        $bulan = (int)($bulan ?? date('m'));
        return date('F', strtotime('2020-' . $bulan . '-01'));
    }
}

$nilaiValid = array_values(array_filter($nilai, fn($n) => (int)($n['is_validated'] ?? 0) === 1));
$jml = count($nilaiValid);
$rata = $jml ? round(array_sum(array_map(fn($n) => (float)($n['nilai_akhir'] ?? 0), $nilaiValid)) / $jml, 2) : 0;

$tglLahir = formatTanggal($siswa['tanggal_lahir'] ?? null);
$alamat = trim((string)($siswa['alamat'] ?? '') . ((string)($siswa['rt'] ?? '') !== '' ? ' RT ' . $siswa['rt'] : '') . ((string)($siswa['rw'] ?? '') !== '' ? '/' . $siswa['rw'] : '') . ((string)($siswa['kelurahan'] ?? '') !== '' ? ', ' . $siswa['kelurahan'] : '') . ((string)($siswa['kecamatan'] ?? '') !== '' ? ', ' . $siswa['kecamatan'] : ''), ', ');
$alamat = $alamat !== '' ? $alamat : '-';
$bulanLabel = formatBulan($bulan ?? date('m'));
$today = date('d F Y');

$namaWali = $wali['nama'] ?? '________________';
$nipWali = $wali['nip'] ?? '-';
$namaKepala = '________________';
$namaOrtu = $siswa['nama_ayah'] ?? $siswa['ayah_nama'] ?? '________________';
$namaWaliSiswa = $siswa['nama_wali'] ?? $siswa['wali_nama'] ?? '________________';
$status = $status ?? [];
$catatanWali = trim((string)($status['catatan_wali'] ?? $siswa['catatan_wali'] ?? ''));
if ($catatanWali === '') { $catatanWali = '-'; }

$tahunPelajaranLabel = $tahunPelajaranLabel ?? '-';
$ketidakhadiran = [
    'Sakit' => isset($absensi['Sakit']) ? (int)$absensi['Sakit'] : 0,
    'Izin' => isset($absensi['Izin']) ? (int)$absensi['Izin'] : 0,
    'Alpa' => isset($absensi['Alpa']) ? (int)$absensi['Alpa'] : 0,
];
// $logo is provided by the controller as a file URI compatible with Dompdf
$logoUrl = isset($logo) && $logo ? $logo : (file_exists(BASE_PATH . '/public/img/logo.png') ? 'file:///' . str_replace('\\','/', realpath(BASE_PATH . '/public/img/logo.png')) : '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Raport - <?= htmlspecialchars($siswa['nama'] ?? '-') ?></title>
<style>
    @page { size: A4 portrait; margin: 20mm; }
    body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #000; margin: 0; padding: 0; }
    table { border-collapse: collapse; border: 1px solid #000; width: 100%; table-layout: fixed; }
    th, td { border: 1px solid #000; padding: 4px 5px; vertical-align: top; }
    th { background: #e9e9e9; font-weight: bold; text-align: center; }
    td { text-align: left; }
    td.center { text-align: center; }
    td.bold { font-weight: bold; }
    .page { width: 100%; min-height: 257mm; page-break-after: always; }
    .page:last-child { page-break-after: auto; }
    .cover { text-align: center; padding-top: 20mm; }
    .logo { width: 34mm; height: 34mm; margin: 0 auto 8mm auto; }
    .school-name { font-size: 22pt; font-weight: bold; margin: 2mm 0 1mm 0; }
    .school-meta { font-size: 11pt; line-height: 1.4; margin: 2mm 0; }
    .title-large { font-size: 24pt; font-weight: bold; margin: 14mm 0 2mm 0; }
    .subtitle { font-size: 14pt; font-weight: bold; margin: 3mm 0 2mm 0; }
    .line { border-top: 1px solid #000; margin: 4mm 0 6mm 0; }
    .field-label { width: 38%; font-weight: bold; }
    .field-value { width: 62%; }
    .section-title { font-size: 13pt; font-weight: bold; margin: 2mm 0 2mm 0; }
    .table-small { font-size: 10pt; }
    .table-small td, .table-small th { padding: 3px 4px; }
    .wrap { white-space: normal; word-break: break-word; }
    .nilai-akhir { font-weight: bold; }
    .predikat { text-align: center; }
</style>
</head>
<body>
<div class="page">
    <div class="cover">

        <!-- School identity (logo removed) -->
        <div style="margin-top:8mm;"></div>

        <div class="school-name">
            SMK NU MUARA SUGIHAN
        </div>

        <div class="school-meta">
            <strong>NPSN : 70055655</strong>
        </div>

        <div class="school-meta">
            Alamat Sekolah
        </div>

        <div class="school-meta">
            Jl. Ir. Sukarno Hatta Lingkungan Dusun 1 Purwosari
        </div>

        <div class="school-meta">
            Kelurahan/Desa : Rejosari
        </div>

        <div class="school-meta">
            Kecamatan : Muara Sugihan
        </div>

        <div class="school-meta">
            Kabupaten : Banyuasin
        </div>

        <div class="school-meta">
            Provinsi : Sumatera Selatan
        </div>

        <div class="school-meta">
            Email : smknumuarasugihan@gmail.com
        </div>

        <div style="margin-top:70px;"></div>

        <div class="title-large">
            RAPORT
        </div>

        <div class="title-large">
            HASIL BELAJAR SISWA
        </div>

        <div style="margin-top:90px;font-size:16pt;font-weight:bold;">
            TAHUN PELAJARAN
        </div>

        <div style="font-size:18pt;font-weight:bold;margin-top:10px;">
            <?= htmlspecialchars($tahunPelajaranLabel) ?>
        </div>

    </div>
</div>

<div class="page">
    <div class="subtitle">IDENTITAS PESERTA DIDIK</div>
    <div class="line"></div>
    <table style="border:none;table-layout:fixed;">
        <tr>
            <td style="border:none;padding:0 0 3mm 0;">
                <table class="table-small" style="border:1px solid #000;">
                    <tr><td class="field-label">Nama Peserta Didik</td><td class="field-value"><strong><?= htmlspecialchars($siswa['nama'] ?? '-') ?></strong></td></tr>
                    <tr><td class="field-label">NIS</td><td class="field-value"><?= htmlspecialchars($siswa['nis'] ?? $siswa['nipd'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">NISN</td><td class="field-value"><?= htmlspecialchars($siswa['nisn'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">Jenis Kelamin</td><td class="field-value"><?= htmlspecialchars($siswa['jenis_kelamin'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">Tempat Lahir</td><td class="field-value"><?= htmlspecialchars($siswa['tempat_lahir'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">Tanggal Lahir</td><td class="field-value"><?= $tglLahir ?></td></tr>
                    <tr><td class="field-label">Agama</td><td class="field-value"><?= htmlspecialchars($siswa['agama'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">Alamat</td><td class="field-value"><?= htmlspecialchars($alamat) ?></td></tr>
                    <tr><td class="field-label">Nama Orang Tua</td><td class="field-value"><?= htmlspecialchars($siswa['ayah_nama'] ?? $siswa['nama_ayah'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">Nama Wali</td><td class="field-value"><?= htmlspecialchars($namaWaliSiswa) ?></td></tr>
                    <tr><td class="field-label">Kelas</td><td class="field-value"><?= htmlspecialchars($kelas['nama_kelas'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">Jurusan</td><td class="field-value"><?= htmlspecialchars($kelas['jurusan'] ?? '-') ?></td></tr>
                    <tr><td class="field-label">Tahun Pelajaran</td><td class="field-value"><?= htmlspecialchars($tahunPelajaranLabel) ?></td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="page">
    <div class="subtitle">HASIL BELAJAR</div>
    <div class="line"></div>
    <table class="table-small" style="border:none;margin-bottom:3mm;">
        <tr>
            <td style="border:none;padding:0 0 2mm 0; width:25%;">Nama</td>
            <td style="border:none;padding:0 0 2mm 0; width:25%;"><strong><?= htmlspecialchars($siswa['nama'] ?? '-') ?></strong></td>
            <td style="border:none;padding:0 0 2mm 0; width:15%;">NISN</td>
            <td style="border:none;padding:0 0 2mm 0; width:35%;"><?= htmlspecialchars($siswa['nisn'] ?? '-') ?></td>
        </tr>
        <tr>
            <td style="border:none;padding:0 0 2mm 0;">Kelas</td>
            <td style="border:none;padding:0 0 2mm 0;"><?= htmlspecialchars($kelas['nama_kelas'] ?? '-') ?></td>
            <td style="border:none;padding:0 0 2mm 0;">Periode Bulan</td>
            <td style="border:none;padding:0 0 2mm 0;"><?= htmlspecialchars($bulanLabel . ' ' . ($tahun ?? date('Y'))) ?></td>
        </tr>
    </table>

    <?php if($jml===0): ?>
        <div class="section-title" style="color:#b00020;">Belum ada nilai yang divalidasi untuk siswa ini.</div>
    <?php else: ?>
        <table class="table-small" style="margin-bottom:4mm;">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:10%;">Kode Mapel</th>
                    <th style="width:20%;">Mata Pelajaran</th>
                    <th style="width:8%;">Nilai Harian</th>
                    <th style="width:8%;">Tugas</th>
                    <th style="width:8%;">Kompetensi / Praktik</th>
                    <th style="width:8%;">Nilai Akhir</th>
                    <th style="width:8%;">Predikat</th>
                    <th style="width:25%;">Capaian Kompetensi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($nilaiValid as $i => $n): ?>
                    <tr>
                        <td class="center"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($n['kode_mapel'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($n['nama_mapel'] ?? '-') ?></td>
                        <td class="center"><?= $n['nilai_tugas'] !== null && $n['nilai_tugas'] !== '' ? htmlspecialchars($n['nilai_tugas']) : '-' ?></td>
                        <td class="center"><?= $n['nilai_uts'] !== null && $n['nilai_uts'] !== '' ? htmlspecialchars($n['nilai_uts']) : '-' ?></td>
                        <td class="center"><?= $n['nilai_uas'] !== null && $n['nilai_uas'] !== '' ? htmlspecialchars($n['nilai_uas']) : '-' ?></td>
                        <td class="nilai-akhir center"><?= $n['nilai_akhir'] !== null && $n['nilai_akhir'] !== '' ? htmlspecialchars($n['nilai_akhir']) : '-' ?></td>
                        <td class="predikat"><?= grd($n['nilai_akhir'] ?? null) ?></td>
                        <td class="wrap"><?= htmlspecialchars($n['capaian_kompetensi'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="section-title">KETIDAKHADIRAN</div>
    <table class="table-small" style="width:60%;margin-bottom:4mm;">
        <thead>
            <tr><th style="width:70%;">Keterangan</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
            <tr><td>Sakit</td><td><?= (int)$ketidakhadiran['Sakit'] ?> hari</td></tr>
            <tr><td>Izin</td><td><?= (int)$ketidakhadiran['Izin'] ?> hari</td></tr>
            <tr><td>Alpa</td><td><?= (int)$ketidakhadiran['Alpa'] ?> hari</td></tr>
        </tbody>
    </table>

    <table class="table-small" style="margin-top:6mm;">
        <tr>
            <td class="center" style="width:33%;padding:12mm 0 3mm 0;">Orang Tua/Wali</td>
            <td class="center" style="width:34%;padding:12mm 0 3mm 0;">Wali Kelas</td>
            <td class="center" style="width:33%;padding:12mm 0 3mm 0;">Kepala Sekolah</td>
        </tr>
        <tr>
            <td class="center" style="height:18mm;">( <?= htmlspecialchars($namaOrtu) ?> )</td>
            <td class="center" style="height:18mm;"><strong><?= htmlspecialchars($namaWali) ?></strong><br>NIP. <?= htmlspecialchars($nipWali) ?></td>
            <td class="center" style="height:18mm;"><strong><?= htmlspecialchars($namaKepala) ?></strong></td>
        </tr>
    </table>
</div>
</body>
</html>
