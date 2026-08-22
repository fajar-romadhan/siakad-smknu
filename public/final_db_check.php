<?php
define('BASE_PATH', dirname(__DIR__));
header('Content-Type: text/plain; charset=utf-8');
require_once BASE_PATH . '/config/database.php';

$db = getDB();

$guruCount = $db->query("SELECT count(*) FROM guru")->fetchColumn();
$siswaCount = $db->query("SELECT count(*) FROM siswa")->fetchColumn();
$userCount = $db->query("SELECT count(*) FROM users")->fetchColumn();
$mapelCount = $db->query("SELECT count(*) FROM mapel")->fetchColumn();
$jadwalCount = $db->query("SELECT count(*) FROM jadwal")->fetchColumn();
$kelasCount = $db->query("SELECT count(*) FROM kelas")->fetchColumn();

echo "=== SUMMARY STATUS DATABASE SIAKAD ===\n";
echo "Total Guru          : $guruCount\n";
echo "Total Siswa         : $siswaCount\n";
echo "Total Akun Pengguna : $userCount\n";
echo "Total Mata Pelajaran: $mapelCount\n";
echo "Total Jadwal        : $jadwalCount\n";
echo "Total Kelas         : $kelasCount\n";
