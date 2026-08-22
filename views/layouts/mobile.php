<?php Auth::requireLogin(); $__role = Auth::role(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/mobile.css') ?>">
</head>
<body class="mobile-body">
<?php require VIEW_PATH.'/components/header-mobile.php'; ?>
<div class="mobile-content">
    <?php $__flashMsg = flash('success'); ?>
    <?= $content ?? '' ?>
</div>
<?php
    // Navbar sesuai role
    if ($__role === 'guru') {
        require VIEW_PATH.'/components/navbar-guru.php';
    } else {
        require VIEW_PATH.'/components/navbar-siswa.php';
    }
?>
<?php require VIEW_PATH.'/components/modal.php'; ?>
<script src="<?= base_url('js/app.js') ?>"></script>
<?php if(!empty($__flashMsg)): ?>
<script>window.addEventListener('DOMContentLoaded',function(){ if(window.showModal) showModal(<?= json_encode($__flashMsg) ?>); });</script>
<?php endif; ?>
</body>
</html>
