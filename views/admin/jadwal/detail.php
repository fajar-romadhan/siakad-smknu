<?php $pageTitle='Jadwal '.$kelas['nama_kelas']; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Jadwal Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?></h3>
        <div style="display:flex;gap:12px;">
            <a href="<?= base_url('index.php?page=jadwal&action=create&kelas_id='.$kelas['id']) ?>" class="btn btn-primary">+ Tambah Jadwal</a>
            <a href="<?= base_url('index.php?page=jadwal') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <?php foreach($hariList as $hari): $jadwalHari = array_filter($jadwal, function($j) use ($hari){ return $j['hari']===$hari; }); ?>
            <h4 class="day-title"><?= $hari ?></h4>
            <?php if(empty($jadwalHari)): ?>
                <p class="text-muted" style="padding:8px 12px;background:var(--gray-50);border-radius:var(--radius-sm);">Jadwal belum diinput untuk hari <?= $hari ?></p>
            <?php else: ?>
            <div class="table-responsive"><table class="table table-sm"><thead><tr><th style="width:150px;">Jam</th><th>Mata Pelajaran</th><th>Guru Pengajar</th><th style="width:150px;">Aksi</th></tr></thead><tbody>
                <?php foreach($jadwalHari as $j): ?>
                <tr>
                    <td><span class="role-badge"><?= substr($j['jam_mulai'],0,5).' - '.substr($j['jam_selesai'],0,5) ?></span></td>
                    <td><?= htmlspecialchars($j['nama_mapel']) ?></td>
                    <td><?= htmlspecialchars($j['guru_nama']) ?></td>
                    <td class="actions">
                        <a href="<?= base_url('index.php?page=jadwal&action=edit&id='.$j['id']) ?>" class="btn btn-sm btn-success">Edit</a>
                        <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=jadwal&action=destroy&id='.$j['id']) ?>','Hapus jadwal <?= htmlspecialchars($j['nama_mapel']) ?> hari <?= $hari ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody></table></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
