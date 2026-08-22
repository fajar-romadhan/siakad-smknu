<?php Auth::requireLogin(); $__role = Auth::role(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<div class="app-wrapper">
    <?php
        if ($__role === 'kepala_sekolah') {
            require VIEW_PATH.'/components/sidebar-kepsek.php';
        } elseif ($__role === 'guru') {
            require VIEW_PATH.'/components/sidebar-guru.php';
        } else {
            require VIEW_PATH.'/components/sidebar.php';
        }
    ?>
    <div class="main-content">
        <?php require VIEW_PATH.'/components/header.php'; ?>
        <div class="content-area">
            <?php $__flashMsg = flash('success'); ?>
            <?= $content ?? '' ?>
        </div>
    </div>
</div>
<?php require VIEW_PATH.'/components/modal.php'; ?>
<script src="<?= base_url('js/app.js') ?>"></script>
<?php if(!empty($__flashMsg)): ?>
<script>window.addEventListener('DOMContentLoaded',function(){ if(window.showModal) showModal(<?= json_encode($__flashMsg) ?>); });</script>
<?php endif; ?>
</body>
</html>
