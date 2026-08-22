<?php $pageTitle='Cetak Raport'; ob_start(); ?>
<div class="dash-header-row"><h2 class="page-title">Cetak Raport Siswa</h2></div>

<div class="card">
    <div class="card-header"><h3>1. Pilih Kelas</h3></div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('index.php') ?>" class="filter-form">
            <input type="hidden" name="page" value="raport">
            <div class="form-row">
                <div class="form-group"><label>Kelas</label>
                    <select name="kelas_id" class="form-control" onchange="this.form.submit()">
                        <option value="">- Pilih Kelas -</option>
                        <?php foreach($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $kelasId==$k['id']?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?> (<?= htmlspecialchars($k['tahun_ajaran']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Cari Siswa (nama / NISN)</label>
                    <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="ketik nama atau NISN...">
                </div>
                <div class="form-group"><label>&nbsp;</label><button type="submit" class="btn btn-primary">Cari</button></div>
            </div>
        </form>
    </div>
</div>

<?php if($kelas): ?>
<div class="card">
    <div class="card-header"><h3>2. Pilih Siswa — Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?></h3></div>
    <div class="card-body">
        <?php if(empty($siswaList)): ?>
            <p class="empty-state">Tidak ada siswa <?= $search?'yang cocok dengan pencarian':'di kelas ini' ?>.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th style="width:60px;">No</th><th>NISN</th><th>Nama Siswa</th><th style="width:180px;">Aksi</th></tr></thead>
                <tbody>
                <?php foreach($siswaList as $i=>$s): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($s['nisn']) ?></td>
                        <td><strong><?= htmlspecialchars($s['nama']) ?></strong></td>
                        <td><a href="<?= base_url('index.php?page=raport&action=siswa&kelas_id='.$kelas['id'].'&siswa_id='.$s['id']) ?>" class="btn btn-primary btn-sm">Lihat Nilai →</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
