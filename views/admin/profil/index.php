<?php $pageTitle='Profil'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Profil Admin</h3>
        <div style="display:flex;gap:8px;">
            <a href="<?= base_url('index.php?page=profil&action=edit') ?>" class="btn btn-primary">✏️ Edit Profil</a>
            <a href="<?= base_url('index.php?page=profil&action=password') ?>" class="btn btn-success">🔑 Ganti Password</a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:20px;padding:20px;background:var(--primary-soft);border-radius:var(--radius);margin-bottom:20px;">
            <div class="profile-avatar" style="width:88px;height:88px;font-size:36px;"><?= strtoupper(substr($profil['nama'],0,1)) ?></div>
            <div>
                <h3 style="font-size:20px;color:var(--gray-800);font-weight:700;margin-bottom:4px;"><?= htmlspecialchars($profil['nama']) ?></h3>
                <span class="role-badge"><?= ucfirst($profil['role']) ?></span>
                <p style="color:var(--gray-500);font-size:13px;margin-top:6px;">Bergabung sejak <?= $profil['created_at']?date('d F Y', strtotime($profil['created_at'])):'-' ?></p>
            </div>
        </div>
        <h4 style="color:var(--primary);font-size:15px;font-weight:600;margin-bottom:12px;">Informasi Akun</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Nama Lengkap</label><span><?= htmlspecialchars($profil['nama']) ?></span></div>
            <div class="detail-item"><label>Username</label><span><?= htmlspecialchars($profil['username']) ?></span></div>
            <div class="detail-item"><label>Role</label><span><?= ucfirst($profil['role']) ?></span></div>
            <div class="detail-item"><label>Status Akun</label><span><span class="badge <?= $profil['status'] ?>"><?= ucfirst($profil['status']) ?></span></span></div>
            <div class="detail-item"><label>Terakhir Login</label><span><?= $profil['last_login']?date('d/m/Y H:i',strtotime($profil['last_login'])):'-' ?></span></div>
        </div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
