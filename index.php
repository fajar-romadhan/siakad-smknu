<?php
/**
 * Root entry point — redirect ke public/
 * Fallback jika .htaccess/mod_rewrite mati.
 */
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
header("Location: {$base}/public/");
exit;
