<?php
require_once BASE_PATH.'/core/Model.php';
class NilaiModel extends Model {
    protected $table = 'nilai';

    public function findByPeriod($siswaId, $mapelId, $kelasId, $bulan, $tahun, $tahunAjaranId = null) {
        $conditions = [
            'siswa_id' => (int)$siswaId,
            'mapel_id' => (int)$mapelId,
            'kelas_id' => (int)$kelasId,
            'bulan' => (int)$bulan,
            'tahun' => (int)$tahun,
        ];
        if ($tahunAjaranId !== null && $tahunAjaranId !== '') {
            $conditions['tahun_ajaran_id'] = (int)$tahunAjaranId;
        }
        return $this->findOneBy($conditions);
    }

    public function upsertByPeriod(array $data, $siswaId, $mapelId, $kelasId, $bulan, $tahun, $tahunAjaranId = null) {
        $conditions = [
            'siswa_id' => (int)$siswaId,
            'mapel_id' => (int)$mapelId,
            'kelas_id' => (int)$kelasId,
            'bulan' => (int)$bulan,
            'tahun' => (int)$tahun,
        ];
        if ($tahunAjaranId !== null && $tahunAjaranId !== '') {
            $conditions['tahun_ajaran_id'] = (int)$tahunAjaranId;
        }

        $existing = $this->findOneBy($conditions);
        if ($existing) {
            $this->update($existing['id'], $data);
            return $existing['id'];
        }

        $payload = $data + [
            'siswa_id' => (int)$siswaId,
            'mapel_id' => (int)$mapelId,
            'kelas_id' => (int)$kelasId,
            'bulan' => (int)$bulan,
            'tahun' => (int)$tahun,
        ];
        if ($tahunAjaranId !== null && $tahunAjaranId !== '') {
            $payload['tahun_ajaran_id'] = (int)$tahunAjaranId;
        }

        return $this->insert($payload);
    }
}
