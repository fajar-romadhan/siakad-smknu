<div class="mobile-navbar">
    <?php $p=$_GET['page']??'dashboard'; ?>
    <a href="<?= base_url('index.php?page=dashboard') ?>" class="<?= $p==='dashboard'?'active':'' ?>">🏠<span>Home</span></a>
    <a href="<?= base_url('index.php?page=kelas') ?>" class="<?= $p==='kelas'?'active':'' ?>">🏫<span>Kelas</span></a>
    <a href="<?= base_url('index.php?page=absensi') ?>" class="<?= $p==='absensi'?'active':'' ?>">✅<span>Absen</span></a>
    <a href="<?= base_url('index.php?page=nilai') ?>" class="<?= $p==='nilai'?'active':'' ?>">📝<span>Nilai</span></a>
    <a href="<?= base_url('index.php?page=catatan') ?>" class="<?= $p==='catatan'?'active':'' ?>">📋<span>Catatan</span></a>
    <a href="<?= base_url('index.php?page=pengumuman') ?>" class="<?= $p==='pengumuman'?'active':'' ?>">📢<span>Info</span></a>
</div>
