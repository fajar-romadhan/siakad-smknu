<?php $pageTitle='Raport Bulanan'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Raport Bulanan</h2>
    <a href="<?= base_url('index.php?page=raport') ?>" class="btn btn-secondary">← Kembali</a>
</div>
<div class="card">
    <div class="card-header"><h3>1. Pilih Kelas</h3></div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('index.php') ?>" class="filter-form">
            <input type="hidden" name="page" value="raport">
            <input type="hidden" name="action" value="bulanan">
            <div class="form-row">
                <div class="form-group"><label>Kelas</label>
                    <select name="kelas_id" class="form-control" onchange="this.form.submit()">
                        <option value="">- Pilih Kelas -</option>
                        <?php foreach($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $kelasId==$k['id']?'selected':'' ?>><?= htmlspecialchars($k['nama_kelas']) ?> (<?= htmlspecialchars($k['tahun_ajaran']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <?php foreach(range(1,12) as $m): ?>
                        <option value="<?= $m ?>" <?= $bulan==$m?'selected':'' ?>><?= date('F',strtotime("2020-$m-01")) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                    <div class="form-group"><label>Tahun</label>
                        <select name="tahun" class="form-control">
                            <?php foreach(range(date('Y')-2, date('Y')) as $y): ?>
                            <option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>><?= $y ?></option>
                            <?php endforeach; ?>
                        </select>
                </div>
                <div class="form-group"><label>Cari Siswa</label>
                    <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="Nama atau NISN...">
                </div>
                <div class="form-group" style="align-self:flex-end;">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
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
                        <td><a href="<?= base_url('index.php?page=raport&action=siswa&kelas_id='.$kelas['id'].'&siswa_id='.$s['id'].'&mode=bulanan&bulan='.$bulan.'&tahun='.$tahun) ?>" class="btn btn-primary btn-sm">Lihat Nilai →</a></td>
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