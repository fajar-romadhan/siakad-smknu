<?php
require_once BASE_PATH.'/models/UserModel.php';
class PenggunaController {
    private $model;
    public function __construct() { $this->model=new UserModel(); }

    public function index() {
        Auth::requireRole('admin');
        $data=$this->model->all('nama ASC');
        $totalAdmin=$this->model->count("role='admin'");
        $totalGuru=$this->model->count("role='guru'");
        $totalSiswa=$this->model->count("role='siswa'");
        $msg=flash('success');
        require VIEW_PATH.'/admin/pengguna/index.php';
    }

    public function edit($id) {
        Auth::requireRole('admin');
        $user=$this->model->find($id);
        $msg=flash('success');
        require VIEW_PATH.'/admin/pengguna/edit.php';
    }

    public function update($id) {
        Auth::requireRole('admin');
        $data=['status'=>$_POST['status']];
        if(!empty($_POST['password'])) $data['password']=password_hash($_POST['password'],PASSWORD_DEFAULT);
        $this->model->update($id,$data);
        flash('success','Pengguna berhasil diperbarui');
        redirect(base_url('index.php?page=pengguna'));
    }

    public function destroy($id) {
        Auth::requireRole('admin');
        $this->model->delete($id);
        flash('success','Pengguna dihapus');
        redirect(base_url('index.php?page=pengguna'));
    }

    /** Export daftar pengguna sebagai HTML print-friendly (user Save as PDF via Ctrl+P) */
    public function exportPdf() {
        Auth::requireRole('admin');
        $users = $this->model->all('role ASC, nama ASC');
        $grouped = ['admin'=>[], 'kepala_sekolah'=>[], 'guru'=>[], 'siswa'=>[]];
        foreach ($users as $u) {
            $r = $u['role'];
            if (isset($grouped[$r])) $grouped[$r][] = $u;
        }
        $stat = [
            'total' => count($users),
            'admin' => count($grouped['admin']),
            'kepsek' => count($grouped['kepala_sekolah']),
            'guru'  => count($grouped['guru']),
            'siswa' => count($grouped['siswa']),
            'aktif' => count(array_filter($users, fn($u) => $u['status']==='aktif')),
            'nonaktif' => count(array_filter($users, fn($u) => $u['status']!=='aktif')),
        ];
        require VIEW_PATH.'/admin/pengguna/export_pdf.php';
    }

    /** Daftar akun login untuk keperluan simulasi/demo.
     *  Password di-recover via password_verify() terhadap daftar kandidat umum. */
    public function daftarAkun() {
        Auth::requireRole('admin');
        $users = $this->model->all('FIELD(role, "admin","kepala_sekolah","guru","siswa"), id ASC');

        // Kandidat password default yang mungkin dipakai
        $candidates = ['siswa123','guru123','admin123','kepsek123','password','password123','12345678','admin'];

        $db = getDB();
        foreach ($users as &$u) {
            // Deteksi password default via bcrypt verify
            $u['password_plain'] = null;
            foreach ($candidates as $cand) {
                if (password_verify($cand, $u['password'])) {
                    $u['password_plain'] = $cand;
                    break;
                }
            }
            // Fallback deteksi password default lama (nama+tahun) untuk akun legacy
            if (!$u['password_plain']) {
                $tahunMasuk = null;
                if ($u['role'] === 'guru') {
                    $s = $db->prepare("SELECT tahun_masuk FROM guru WHERE user_id=? LIMIT 1");
                    $s->execute([$u['id']]);
                    $tahunMasuk = $s->fetchColumn();
                } elseif ($u['role'] === 'siswa') {
                    $s = $db->prepare("SELECT tahun_masuk FROM siswa WHERE user_id=? LIMIT 1");
                    $s->execute([$u['id']]);
                    $tahunMasuk = $s->fetchColumn();
                }
                if ($tahunMasuk) {
                    $oldDefault = strtolower(str_replace(' ','',$u['nama'])).$tahunMasuk;
                    if (password_verify($oldDefault, $u['password'])) {
                        $u['password_plain'] = $oldDefault;
                    }
                }
            }

            // Info tambahan sesuai role
            $u['info_tambahan'] = '';
            if ($u['role'] === 'guru') {
                $s = $db->prepare("SELECT g.kode_guru, k.nama_kelas FROM guru g LEFT JOIN kelas k ON k.wali_kelas_id=g.id WHERE g.user_id=? LIMIT 1");
                $s->execute([$u['id']]);
                if ($g = $s->fetch()) {
                    $u['info_tambahan'] = $g['kode_guru'] ?? '';
                    if (!empty($g['nama_kelas'])) $u['info_tambahan'] .= ' · Wali ' . $g['nama_kelas'];
                }
            } elseif ($u['role'] === 'siswa') {
                $s = $db->prepare("SELECT s.kode_siswa, s.nisn, k.nama_kelas FROM siswa s LEFT JOIN kelas_siswa ks ON ks.siswa_id=s.id LEFT JOIN kelas k ON ks.kelas_id=k.id WHERE s.user_id=? LIMIT 1");
                $s->execute([$u['id']]);
                if ($r = $s->fetch()) {
                    $u['info_tambahan'] = $r['nama_kelas'] ?? '';
                    if (!empty($r['kode_siswa'])) $u['info_tambahan'] .= ' · ' . $r['kode_siswa'];
                }
            }
        }
        unset($u);

        // Group by role
        $grouped = ['admin'=>[], 'kepala_sekolah'=>[], 'guru'=>[], 'siswa'=>[]];
        foreach ($users as $u) {
            if (isset($grouped[$u['role']])) $grouped[$u['role']][] = $u;
        }
        require VIEW_PATH.'/admin/pengguna/export_akun_pdf.php';
    }
}
