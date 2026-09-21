<?php
class DashboardController {
    public function index() {
        $role = Auth::role();
        $db = getDB();
        if ($role==='admin') {
            require_once BASE_PATH.'/models/GuruModel.php';
            require_once BASE_PATH.'/models/SiswaModel.php';
            require_once BASE_PATH.'/models/KelasModel.php';
            require_once BASE_PATH.'/models/MapelModel.php';
            $totalGuru = (new GuruModel())->count();
            $totalSiswa = (new SiswaModel())->count();
            $totalKelas = (new KelasModel())->count();
            $totalMapel = (new MapelModel())->count();
            // Total pengguna (semua user)
            $totalPengguna = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();

            // Grafik aktivitas 6 hari (Sen-Sab): agregat absensi guru + siswa per hari
            $namaHari = ['Sen','Sel','Rab','Kam','Jum','Sab'];
            $aktivitas = [];
            for ($i=5; $i>=0; $i--) {
                $tgl = date('Y-m-d', strtotime("-{$i} days"));
                $wd = (int)date('w', strtotime($tgl)); // 0=Minggu
                $lbl = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'][$wd];
                $q1 = $db->prepare("SELECT COUNT(DISTINCT guru_id) FROM absensi_guru WHERE DATE(tanggal)=?");
                $q1->execute([$tgl]);
                $q2 = $db->prepare("SELECT COUNT(DISTINCT siswa_id) FROM absensi_siswa WHERE DATE(tanggal)=?");
                $q2->execute([$tgl]);
                $aktivitas[] = ['label'=>$lbl, 'total'=>(int)$q1->fetchColumn() + (int)$q2->fetchColumn()];
            }

            // Informasi terbaru: pengumuman aktif + statistik absen hari ini + jadwal terbaru
            $infoTerbaru = [];
            try {
                $peng = $db->prepare("SELECT judul, tanggal_publish FROM pengumuman WHERE status='aktif' AND tanggal_publish <= NOW() ORDER BY tanggal_publish DESC LIMIT 2");
                $peng->execute();
                foreach($peng->fetchAll() as $p) {
                    $infoTerbaru[] = ['pesan'=>'Pengumuman: '.$p['judul'], 'waktu'=>date('d M Y', strtotime($p['tanggal_publish']))];
                }
            } catch (Exception $e) {}
            // Statistik absen hari ini
            try {
                $today = date('Y-m-d');
                $absToday = $db->prepare("SELECT status, COUNT(*) as jml FROM absensi_siswa WHERE DATE(tanggal)=? GROUP BY status");
                $absToday->execute([$today]);
                $rows = $absToday->fetchAll();
                if ($rows) {
                    $total = 0; $hadir = 0;
                    foreach($rows as $r) { $total += (int)$r['jml']; if($r['status']==='Hadir') $hadir=(int)$r['jml']; }
                    $pct = $total>0 ? round(($hadir/$total)*100) : 0;
                    $infoTerbaru[] = ['pesan'=>"Absensi hari ini: {$pct}% siswa hadir", 'waktu'=>'Hari ini'];
                }
            } catch (Exception $e) {}
            // Info jadwal terbaru
            try {
                $jadBaru = $db->query("SELECT k.nama_kelas FROM jadwal j JOIN kelas k ON j.kelas_id=k.id ORDER BY j.id DESC LIMIT 1")->fetch();
                if ($jadBaru) {
                    $infoTerbaru[] = ['pesan'=>'Jadwal kelas '.$jadBaru['nama_kelas'].' telah diperbarui', 'waktu'=>'Baru saja'];
                }
            } catch (Exception $e) {}

            require VIEW_PATH.'/admin/dashboard.php';
        } elseif ($role==='guru') {
            require_once BASE_PATH.'/models/GuruModel.php';
            $guru = (new GuruModel())->whereOne('user_id', Auth::id());
            $jadwalHari = [];
            if ($guru) {
                $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][date('w')];
                $jadwalHari = $db->prepare("SELECT j.*,m.nama_mapel,k.nama_kelas FROM jadwal j JOIN mapel m ON j.mapel_id=m.id JOIN kelas k ON j.kelas_id=k.id WHERE j.guru_id=? AND j.hari=? ORDER BY j.jam_mulai");
                $jadwalHari->execute([$guru['id'],$hari]);
                $jadwalHari = $jadwalHari->fetchAll();
            }
            require VIEW_PATH.'/guru/dashboard.php';
        } else {
            require_once BASE_PATH.'/models/SiswaModel.php';
            require_once BASE_PATH.'/models/KelasSiswaModel.php';
            $siswa = (new SiswaModel())->whereOne('user_id', Auth::id());
            $kelas = null; $jadwalHari = []; $statsAbsen = ['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
            if ($siswa) {
                $ks = $db->prepare("SELECT ks.*,k.nama_kelas,g.nama as wali FROM kelas_siswa ks JOIN kelas k ON ks.kelas_id=k.id LEFT JOIN guru g ON k.wali_kelas_id=g.id WHERE ks.siswa_id=? LIMIT 1");
                $ks->execute([$siswa['id']]); $kelas=$ks->fetch();
                if ($kelas) {
                    $hari=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][date('w')];
                    // Hitung agregat kehadiran per hari (jika 1 hari ada banyak mapel, tetap dihitung 1 hari)
                    $sqlDaily = "SELECT status_harian, COUNT(*) as jml
                                 FROM (
                                     SELECT 
                                         tanggal,
                                         CASE 
                                             WHEN SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) > 0 THEN 'Hadir'
                                             WHEN SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) > 0 THEN 'Izin'
                                             WHEN SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) > 0 THEN 'Sakit'
                                             ELSE 'Alpa'
                                         END AS status_harian
                                     FROM absensi_siswa 
                                     WHERE siswa_id=? 
                                     GROUP BY tanggal
                                 ) AS daily_att
                                 GROUP BY status_harian";
                    $abs=$db->prepare($sqlDaily);
                    $abs->execute([$siswa['id']]);
                    foreach($abs->fetchAll() as $a) {
                        if (isset($statsAbsen[$a['status_harian']])) {
                            $statsAbsen[$a['status_harian']] = (int)$a['jml'];
                        }
                    }
                }
            }
            require VIEW_PATH.'/siswa/dashboard.php';
        }
    }
}
