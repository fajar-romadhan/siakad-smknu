<?php $pageTitle = 'Detail Siswa'; ob_start();
function dss($siswa,$k){ return ($siswa[$k] ?? '') !== '' && ($siswa[$k] ?? null) !== null ? htmlspecialchars($siswa[$k]) : '-'; }
$alamatLengkap = trim(($siswa['alamat']??'').
    (($siswa['rt']??'')?' RT '.$siswa['rt']:'').
    (($siswa['rw']??'')?'/RW '.$siswa['rw']:'').
    (($siswa['kelurahan']??'')?', Kel. '.$siswa['kelurahan']:'').
    (($siswa['kecamatan']??'')?', Kec. '.$siswa['kecamatan']:'').
    (($siswa['kode_pos']??'')?' '.$siswa['kode_pos']:''), ', ');
?>
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Biodata Siswa (Monitoring Kepala Sekolah)</h3>
        <a href="<?= base_url('index.php?page=kepsek&action=siswa') ?>" class="btn btn-secondary">← Kembali ke Data Siswa</a>
    </div>
    <div class="card-body">
        <h4 class="form-section-title">Identitas</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Kode Siswa</label><span><?= dss($siswa,'kode_siswa') ?></span></div>
            <div class="detail-item"><label>NISN</label><span><?= dss($siswa,'nisn') ?></span></div>
            <div class="detail-item"><label>NIPD</label><span><?= dss($siswa,'nipd') ?></span></div>
            <div class="detail-item"><label>NIK</label><span><?= dss($siswa,'nik') ?></span></div>
            <div class="detail-item"><label>Nama</label><span><?= dss($siswa,'nama') ?></span></div>
            <div class="detail-item"><label>Kelas</label><span><?= dss($siswa,'nama_kelas') ?></span></div>
            <div class="detail-item"><label>Jenis Kelamin</label><span><?= dss($siswa,'jenis_kelamin') ?></span></div>
            <div class="detail-item"><label>Tempat, Tgl Lahir</label><span><?= dss($siswa,'tempat_lahir') ?><?= ($siswa['tanggal_lahir']??null)?', '.date('d F Y',strtotime($siswa['tanggal_lahir'])):'' ?></span></div>
            <div class="detail-item"><label>Agama</label><span><?= dss($siswa,'agama') ?></span></div>
        </div>

        <h4 class="form-section-title">Alamat & Kontak</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Alamat Lengkap</label><span><?= $alamatLengkap ?: '-' ?></span></div>
            <div class="detail-item"><label>No. HP</label><span><?= dss($siswa,'no_hp') ?></span></div>
        </div>

        <h4 class="form-section-title">Data Tambahan</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Penerima KIP</label><span><?= ($siswa['penerima_kip']??'tidak')==='ya' ? 'Ya'.(($siswa['nomor_kip']??'')?' ('.$siswa['nomor_kip'].')':'') : 'Tidak' ?></span></div>
            <div class="detail-item"><label>Sekolah Asal</label><span><?= dss($siswa,'sekolah_asal') ?></span></div>
            <div class="detail-item"><label>Jenis Tinggal</label><span><?= dss($siswa,'jenis_tinggal') ?></span></div>
            <div class="detail-item"><label>Anak ke-</label><span><?= dss($siswa,'anak_ke') ?></span></div>
            <div class="detail-item"><label>Jumlah Saudara</label><span><?= dss($siswa,'jml_saudara') ?></span></div>
            <div class="detail-item"><label>Jarak ke Sekolah</label><span><?= ($siswa['jarak_sekolah_km']??'')!=='' && ($siswa['jarak_sekolah_km']??null)!==null ? $siswa['jarak_sekolah_km'].' KM' : '-' ?></span></div>
        </div>

        <h4 class="form-section-title">Data Ayah</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Nama</label><span><?= dss($siswa,'ayah_nama') ?></span></div>
            <div class="detail-item"><label>NIK</label><span><?= dss($siswa,'ayah_nik') ?></span></div>
            <div class="detail-item"><label>Tahun Lahir</label><span><?= dss($siswa,'ayah_tahun_lahir') ?></span></div>
            <div class="detail-item"><label>Pendidikan</label><span><?= dss($siswa,'ayah_pendidikan') ?></span></div>
            <div class="detail-item"><label>Pekerjaan</label><span><?= dss($siswa,'ayah_pekerjaan') ?></span></div>
        </div>

        <h4 class="form-section-title">Data Ibu</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Nama</label><span><?= dss($siswa,'ibu_nama') ?></span></div>
            <div class="detail-item"><label>NIK</label><span><?= dss($siswa,'ibu_nik') ?></span></div>
            <div class="detail-item"><label>Tahun Lahir</label><span><?= dss($siswa,'ibu_tahun_lahir') ?></span></div>
            <div class="detail-item"><label>Pendidikan</label><span><?= dss($siswa,'ibu_pendidikan') ?></span></div>
            <div class="detail-item"><label>Pekerjaan</label><span><?= dss($siswa,'ibu_pekerjaan') ?></span></div>
        </div>

        <div class="form-actions" style="margin-top:24px;">
            <a href="<?= base_url('index.php?page=kepsek&action=siswa') ?>" class="btn btn-secondary">← Kembali ke Data Siswa</a>
        </div>
    </div>
</div>

<!-- MONITORING DATA NILAI SISWA (READ-ONLY) -->
<div class="card" style="margin-top: 24px;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <h3>Data Nilai Siswa (Monitoring Read-Only)</h3>
        <form method="GET" action="<?= base_url('index.php') ?>" class="filter-form" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin:0;">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="siswaDetail">
            <input type="hidden" name="id" value="<?= $siswa['id'] ?>">

            <div class="form-group" style="margin:0; display:flex; align-items:center; gap:6px;">
                <label style="margin:0; font-weight:600; white-space:nowrap;">Bulan:</label>
                <select name="bulan" class="form-control" onchange="this.form.submit()" style="min-width:130px;">
                    <option value="0" <?= ($bulan ?? 0) === 0 ? 'selected' : '' ?>>Semua Bulan</option>
                    <?php 
                    $bulanNama = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    foreach($bulanNama as $mNum => $mName): 
                    ?>
                    <option value="<?= $mNum ?>" <?= ($bulan ?? 0) === $mNum ? 'selected' : '' ?>><?= $mName ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0; display:flex; align-items:center; gap:6px;">
                <label style="margin:0; font-weight:600; white-space:nowrap;">Tahun Ajaran:</label>
                <select name="tahun_ajaran_id" class="form-control" onchange="this.form.submit()" style="min-width:180px;">
                    <option value="0" <?= ($tahunAjaranId ?? 0) === 0 ? 'selected' : '' ?>>Semua TA</option>
                    <?php if(!empty($tahunAjaranList)): foreach($tahunAjaranList as $ta): ?>
                    <option value="<?= $ta['id'] ?>" <?= ($tahunAjaranId ?? 0) == $ta['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ta['tahun_ajaran']) ?> (<?= ucfirst($ta['semester_aktif'] ?? '') ?>) <?= ($ta['status'] ?? '') === 'aktif' ? '[Aktif]' : '' ?>
                    </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        </form>
    </div>
    <div class="card-body">
        <?php if (empty($nilaiList)): ?>
            <p class="empty-state">Belum ada data nilai untuk periode bulan dan tahun ajaran yang dipilih.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Guru Pengampu</th>
                            <th style="text-align:center; width:75px;">Tugas</th>
                            <th style="text-align:center; width:75px;">UTS</th>
                            <th style="text-align:center; width:75px;">UAS</th>
                            <th style="text-align:center; width:95px;">Nilai Akhir</th>
                            <th>Capaian Kompetensi</th>
                            <th style="width:110px; text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($nilaiList as $idx => $n): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><strong><?= htmlspecialchars($n['nama_mapel']) ?></strong></td>
                            <td><?= htmlspecialchars($n['nama_kelas'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($n['guru_nama'] ?? '-') ?></td>
                            <td style="text-align:center;"><?= $n['nilai_tugas'] !== null ? number_format($n['nilai_tugas'], 2) : '-' ?></td>
                            <td style="text-align:center;"><?= $n['nilai_uts'] !== null ? number_format($n['nilai_uts'], 2) : '-' ?></td>
                            <td style="text-align:center;"><?= $n['nilai_uas'] !== null ? number_format($n['nilai_uas'], 2) : '-' ?></td>
                            <td style="text-align:center;">
                                <strong style="color: <?= ((float)($n['nilai_akhir']??0) >= 80) ? '#28a745' : ((float)($n['nilai_akhir']??0) >= 70 ? '#fd7e14' : '#dc3545') ?>;">
                                    <?= $n['nilai_akhir'] !== null ? number_format($n['nilai_akhir'], 2) : '-' ?>
                                </strong>
                            </td>
                            <td><small><?= htmlspecialchars($n['capaian_kompetensi'] ?? '-') ?></small></td>
                            <td style="text-align:center;">
                                <?php if ((int)($n['is_validated'] ?? 0) === 1): ?>
                                    <span class="badge" style="background:#28a745; color:#fff; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:600; display:inline-block;">Tervalidasi</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#ffc107; color:#212529; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:600; display:inline-block;">Draft</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
