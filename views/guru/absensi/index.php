<?php $pageTitle='Absensi'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Absensi</h2>
    <p style="color:var(--gray-500);font-size:13px;"><?= date('l, d F Y', time()) ?></p>
</div>

<!-- Card Absen Guru Sendiri -->
<div class="card">
    <div class="card-header"><h3>Absen Guru (Diri Sendiri)</h3></div>
    <div class="card-body">
        <?php if($sudahAbsen): ?>
            <div style="display:flex;align-items:center;gap:16px;padding:16px;background:var(--primary-soft);border-radius:var(--radius-sm);border-left:4px solid var(--primary);">
                <span style="font-size:32px;">✅</span>
                <div>
                    <h4 style="color:var(--gray-800);font-size:15px;font-weight:600;">Anda sudah absen hari ini</h4>
                    <p style="color:var(--gray-500);font-size:13px;margin-top:4px;">Status: <span class="badge <?= strtolower($sudahAbsen['status']) ?>"><?= $sudahAbsen['status'] ?></span></p>
                    <?php if ((int)($sudahAbsen['is_validated'] ?? 0) === 1): ?>
                        <p style="margin:6px 0 0;font-size:13px;"><span class="badge aktif">Sudah divalidasi admin</span></p>
                    <?php else: ?>
                        <p style="margin:6px 0 0;font-size:13px;"><span class="badge draft">Menunggu validasi admin</span></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
        <form method="POST" action="<?= base_url('index.php?page=absensi&action=storeGuru') ?>">
            <p style="color:var(--gray-600);margin-bottom:12px;font-size:14px;font-weight:500;">Pilih Status Kehadiran Anda:</p>
            <div class="status-buttons">
                <?php foreach(['Hadir','Izin','Sakit','Alpa'] as $s): ?>
                <label class="status-btn"><input type="radio" name="status" value="<?= $s ?>" required><span><?= $s ?></span></label>
                <?php endforeach; ?>
            </div>
            <div class="form-actions">
                <button type="reset" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Grid: Absen Siswa + Riwayat Guru -->
<div class="dashboard-grid">
    <div class="card">
        <div class="card-header"><h3>Absen Siswa - Pilih Kelas & Mata Pelajaran</h3></div>
        <div class="card-body">
            <?php
            // Ambil list kelas+mapel yg diampu guru (dari jadwal)
            $db = getDB();
            require_once BASE_PATH.'/models/GuruModel.php';
            $guru = (new GuruModel())->whereOne('user_id', Auth::id());
            $kelasMapelList = [];
            if ($guru) {
                $st = $db->prepare("SELECT DISTINCT k.id as kelas_id, k.nama_kelas, m.id as mapel_id, m.nama_mapel FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN mapel m ON j.mapel_id=m.id WHERE j.guru_id=? ORDER BY k.nama_kelas, m.nama_mapel");
                $st->execute([$guru['id']]);
                $kelasMapelList = $st->fetchAll();
            }
            ?>
            <?php if(empty($kelasMapelList)): ?>
                <p class="empty-state">Belum ada kelas & mapel yang diampu. Admin belum menetapkan jadwal untuk Anda.</p>
            <?php else: ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <?php foreach($kelasMapelList as $km): ?>
                <a href="<?= base_url('index.php?page=absensi&action=siswa&kelas_id='.$km['kelas_id'].'&mapel_id='.$km['mapel_id']) ?>" style="display:block;padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);border:1px solid var(--gray-100);text-decoration:none;color:var(--gray-700);transition:var(--transition);" onmouseover="this.style.background='var(--primary-soft)';this.style.transform='translateY(-2px)';" onmouseout="this.style.background='var(--gray-50)';this.style.transform='';">
                    <div style="display:flex;justify-content:space-between;align-items:start;">
                        <div>
                            <strong style="font-size:14px;color:var(--gray-800);"><?= htmlspecialchars($km['nama_mapel']) ?></strong>
                            <p style="font-size:12px;color:var(--gray-500);margin-top:2px;">Kelas <?= htmlspecialchars($km['nama_kelas']) ?></p>
                        </div>
                        <span style="font-size:18px;">→</span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Riwayat Absen Anda</h3></div>
        <div class="card-body">
            <?php if(empty($riwayatGuru)): ?>
                <p class="empty-state">Belum ada riwayat absensi</p>
            <?php else: ?>
            <div style="max-height:300px;overflow-y:auto;">
                <?php foreach($riwayatGuru as $r): ?>
                <div style="display:grid;grid-template-columns:1fr auto;gap:10px;padding:10px 12px;border-bottom:1px solid var(--gray-100);align-items:center;">
                    <div>
                        <div style="font-size:13px;color:var(--gray-700);font-weight:600;"><?= date('d F Y',strtotime($r['tanggal'])) ?></div>
                        <div style="font-size:12px;color:var(--gray-500);margin-top:4px;">
                            <?php if ((int)($r['is_validated'] ?? 0) === 1): ?>
                                <span class="badge aktif">Sudah divalidasi admin</span>
                            <?php else: ?>
                                <span class="badge draft">Menunggu validasi admin</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="badge <?= strtolower($r['status']) ?>" style="font-size:13px;min-width:90px;text-align:center;"><?= $r['status'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
