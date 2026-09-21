<?php 
$pageTitle = 'Buka Kunci Validasi Nilai'; 
ob_start(); 
$bulanNama = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<div class="dash-header-row">
    <h2 class="page-title">🔓 Kelola & Buka Kunci Validasi Nilai</h2>
</div>

<!-- SEARCH & FILTER CARD -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3>Filter & Pencarian Nama Siswa</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('index.php') ?>" class="filter-form" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
            <input type="hidden" name="page" value="nilai">

            <div class="form-group" style="margin:0; flex:1; min-width:240px;">
                <label style="font-weight:600; margin-bottom:4px; display:block;">🔍 Cari Nama Siswa / NISN:</label>
                <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Ketik nama siswa atau NISN..." style="width:100%;">
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-weight:600; margin-bottom:4px; display:block;">Kelas:</label>
                <select name="kelas_id" class="form-control" style="min-width:150px;">
                    <option value="0">Semua Kelas</option>
                    <?php foreach($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($kelasId ?? 0) == $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-weight:600; margin-bottom:4px; display:block;">Bulan:</label>
                <select name="bulan" class="form-control" style="min-width:130px;">
                    <option value="0">Semua Bulan</option>
                    <?php foreach($bulanNama as $mNum => $mName): ?>
                        <option value="<?= $mNum ?>" <?= ($bulan ?? 0) == $mNum ? 'selected' : '' ?>>
                            <?= $mName ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label style="font-weight:600; margin-bottom:4px; display:block;">Tahun Ajaran:</label>
                <select name="tahun_ajaran_id" class="form-control" style="min-width:160px;">
                    <option value="0">Semua TA</option>
                    <?php foreach($tahunAjaranList as $ta): ?>
                        <option value="<?= $ta['id'] ?>" <?= ($tahunAjaranId ?? 0) == $ta['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ta['tahun_ajaran']) ?> (<?= ucfirst($ta['semester_aktif'] ?? '') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0; display:flex; gap:8px;">
                <button type="submit" class="btn btn-primary">🔍 Cari / Filter</button>
                <?php if (!empty($q) || $kelasId > 0 || $bulan > 0 || $tahunAjaranId > 0): ?>
                    <a href="<?= base_url('index.php?page=nilai') ?>" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- HASIL PENCARIAN NAMA SISWA (JIKA Q DIISI) -->
<?php if (!empty($q)): ?>
<div class="card" style="margin-bottom: 24px; border: 2px solid #00923F;">
    <div class="card-header" style="background:#E8F5E9;">
        <h3 style="color:#00923F; margin:0;">🎯 Hasil Pencarian Siswa untuk: "<strong><?= htmlspecialchars($q) ?></strong>"</h3>
    </div>
    <div class="card-body">
        <?php if (empty($hasilPencarianSiswa)): ?>
            <p class="empty-state">Tidak ditemukan data nilai siswa yang cocok dengan pencarian "<strong><?= htmlspecialchars($q) ?></strong>".</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:40px;">No</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th>Periode</th>
                            <th style="text-align:center;">Nilai Akhir</th>
                            <th style="text-align:center;">Status</th>
                            <th style="width:160px; text-align:center;">Aksi Buka Kunci</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($hasilPencarianSiswa as $idx => $ns): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><strong><?= htmlspecialchars($ns['siswa_nama']) ?></strong></td>
                            <td><?= htmlspecialchars($ns['nisn'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($ns['nama_kelas']) ?></td>
                            <td><?= htmlspecialchars($ns['nama_mapel']) ?></td>
                            <td><?= htmlspecialchars($ns['guru_nama'] ?? '-') ?></td>
                            <td><?= $bulanNama[(int)$ns['bulan']] ?? 'Bulan '.$ns['bulan'] ?> <?= $ns['tahun'] ?></td>
                            <td style="text-align:center;">
                                <strong><?= $ns['nilai_akhir'] !== null ? number_format($ns['nilai_akhir'], 2) : '-' ?></strong>
                            </td>
                            <td style="text-align:center;">
                                <?php if ((int)$ns['is_validated'] === 1): ?>
                                    <span class="badge" style="background:#28a745; color:#fff; padding:4px 8px; border-radius:4px; font-weight:600; font-size:11px;">🔒 Tervalidasi</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#ffc107; color:#212529; padding:4px 8px; border-radius:4px; font-weight:600; font-size:11px;">📝 Draft</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <?php if ((int)$ns['is_validated'] === 1): ?>
                                    <a href="<?= base_url('index.php?page=nilai&action=bukaKunci&nilai_id='.$ns['nilai_id'].'&q='.urlencode($q)) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Buka kunci nilai <?= htmlspecialchars(addslashes($ns['siswa_nama'])) ?> untuk mata pelajaran <?= htmlspecialchars(addslashes($ns['nama_mapel'])) ?>?')">
                                        🔓 Buka Kunci Siswa Ini
                                    </a>
                                <?php else: ?>
                                    <span style="color:#6c757d; font-size:11px; font-style:italic;">Sudah Terbuka</span>
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
<?php endif; ?>

<!-- DATA REKAP VALIDASI KELAS & MAPEL -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Validasi Nilai Per Kelas & Mata Pelajaran</h3>
    </div>
    <div class="card-body">
        <?php if(empty($rekapValidasi)): ?>
            <p class="empty-state">Belum ada data nilai yang diinput atau divalidasi pada filter ini.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th>Periode</th>
                            <th style="text-align:center;">Jumlah Siswa</th>
                            <th style="text-align:center;">Status Validasi</th>
                            <th style="width:160px; text-align:center;">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rekapValidasi as $idx => $r): ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><strong><?= htmlspecialchars($r['nama_kelas']) ?></strong></td>
                            <td><?= htmlspecialchars($r['nama_mapel']) ?></td>
                            <td><?= htmlspecialchars($r['guru_nama'] ?? '-') ?></td>
                            <td>
                                <?= $bulanNama[(int)$r['bulan']] ?? 'Bulan '.$r['bulan'] ?> <?= $r['tahun'] ?>
                            </td>
                            <td style="text-align:center;">
                                <strong><?= $r['total_siswa'] ?> Siswa</strong>
                            </td>
                            <td style="text-align:center;">
                                <?php if ((int)$r['total_terkunci'] > 0 && (int)$r['total_draft'] === 0): ?>
                                    <span class="badge" style="background:#28a745; color:#fff; padding:6px 10px; border-radius:4px; font-weight:600;">🔒 Terkunci Full (<?= $r['total_terkunci'] ?>)</span>
                                <?php elseif ((int)$r['total_terkunci'] > 0): ?>
                                    <span class="badge" style="background:#17a2b8; color:#fff; padding:6px 10px; border-radius:4px; font-weight:600;">🔒 Sebagian Terkunci (<?= $r['total_terkunci'] ?>/<?= $r['total_siswa'] ?>)</span>
                                <?php else: ?>
                                    <span class="badge" style="background:#ffc107; color:#212529; padding:6px 10px; border-radius:4px; font-weight:600;">📝 Draft (Belum Dikunci)</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <?php if ((int)$r['total_terkunci'] > 0): ?>
                                    <a href="<?= base_url('index.php?page=nilai&action=bukaKunci&kelas_id='.$r['kelas_id'].'&mapel_id='.$r['mapel_id'].'&bulan='.$r['bulan'].'&tahun='.$r['tahun'].(!empty($q) ? '&q='.urlencode($q) : '')) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Apakah Anda yakin ingin membuka kunci nilai <?= htmlspecialchars(addslashes($r['nama_mapel'])) ?> - <?= htmlspecialchars(addslashes($r['nama_kelas'])) ?>? Guru pengampu akan dapat mengedit nilai ini kembali.')">
                                        🔓 Buka Kunci Validasi
                                    </a>
                                <?php else: ?>
                                    <span style="color:#6c757d; font-size:12px; font-style:italic;">Tidak Terkunci</span>
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
