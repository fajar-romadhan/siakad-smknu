<?php
if (!defined('BASE_PATH')) define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH.'/core/Model.php';
class GuruModel extends Model {
    protected $table = 'guru';
}
