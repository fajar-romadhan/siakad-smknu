<?php $pageTitle='Nilai'; ob_start();
$db=getDB();
require_once BASE_PATH.'/models/SiswaModel.php';
$siswa=(new SiswaModel())->whereOne('user_id',Auth::id());
$bulan=(int)($_GET['bulan']??date('m'));
$tahun=(int)($_GET['tahun']??date('Y'));
$semuaMapel=[];
if($siswa){
    $ks=$db->prepare("SELECT kelas_id FROM kelas_siswa WHERE siswa_id=? LIMIT 1"); $ks->execute([$siswa['id']]); $ksRow=$ks->fetch();
    if($ksRow){
        $st=$db->prepare("SELECT DISTINCT m.id,m.nama_mapel,g.nama as guru_nama FROM jadwal j JOIN mapel m ON j.mapel_id=m.id JOIN guru g ON j.guru_id=g.id WHERE j.kelas_id=? ORDER BY m.nama_mapel");
        $st->execute([$ksRow['kelas_id']]); $semuaMapel=$st->fetchAll();
    }
}
$nilaiByMapel=[];
foreach($nilaiList as $n) $nilaiByMapel[$n['mapel_id']]=$n;
?>
<div class="greeting">
    <h3>Nilai Saya</h3>
    <p>Nilai per bulan</p>
</div>
<div class="card" style="margin-bottom:16px;">
    <div class="card-body">
        <form method="GET" action="<?= base_url('index.php') ?>" class="filter-form">
            <input type="hidden" name="page" value="nilai">
            <div class="form-row">
                <div class="form-group"><label>Bulan</label><select name="bulan" class="form-control">
                    <?php foreach(range(1,12) as $m): ?><option value="<?= $m ?>" <?= $bulan==$m?'selected':'' ?>><?= date('F',strtotime("2020-$m-01")) ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group"><label>Tahun</label><select name="tahun" class="form-control">
                    <?php foreach(range(date('Y')-2, date('Y')) as $y): ?><option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>><?= $y ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group" style="align-self:flex-end;"><button type="submit" class="btn btn-primary">Tampilkan</button></div>
            </div>
        </form>
    </div>
</div>

<?php if(empty($semuaMapel)): ?>
    <div class="mobile-card"><p class="empty-state">Belum ada mata pelajaran. Anda mungkin belum dimasukkan ke kelas.</p></div>
<?php else: foreach($semuaMapel as $mp): 
    $n = $nilaiByMapel[$mp['id']] ?? null;
?>
<div class="mobile-card">
    <h4><?= htmlspecialchars($mp['nama_mapel']) ?></h4>
    <small style="color:var(--gray-500);">Guru: <?= htmlspecialchars($mp['guru_nama']) ?></small>
    <?php if($n && (int)($n['is_validated']??0)===1): ?>
    <div class="nilai-display">
        <div><span>Nilai Harian</span><strong><?= $n['nilai_tugas']!==null?$n['nilai_tugas']:'-' ?></strong></div>
        <div><span>Tugas</span><strong><?= $n['nilai_uts']!==null?$n['nilai_uts']:'-' ?></strong></div>
        <div><span>Kompetensi / Praktik</span><strong><?= $n['nilai_uas']!==null?$n['nilai_uas']:'-' ?></strong></div>
        <div class="final"><span>Akhir</span><strong><?= $n['nilai_akhir']!==null?$n['nilai_akhir']:'-' ?></strong></div>
    </div>
    <div style="margin-top:12px;">
        <h5 style="margin:8px 0 6px;">Capaian Kompetensi</h5>
        <?php if(!empty(trim((string)($n['capaian_kompetensi']??'')))): ?>
            <div style="padding:10px;border:1px solid var(--gray-200);border-radius:6px;"><?= nl2br(htmlspecialchars($n['capaian_kompetensi'])) ?></div>
        <?php else: ?>
            <div style="padding:10px;border:1px dashed var(--gray-200);border-radius:6px;color:var(--gray-600);">Belum ada capaian kompetensi.</div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div style="padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);text-align:center;margin-top:10px;">
        <span style="font-size:20px;">⏳</span>
        <p style="color:var(--gray-500);font-size:12.5px;font-weight:500;margin-top:4px;">Nilai masih menunggu validasi guru.</p>
    </div>
    <?php endif; ?>
</div>
<?php endforeach; endif; ?>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
