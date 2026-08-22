<?php
if (!defined('BASE_PATH')) define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH.'/core/Model.php';
class UserModel extends Model {
    protected $table = 'users';

    public function generateUsernameFromNameAndYear(string $nama, $tahun) {
        $cleanName = strtolower(str_replace(' ', '', trim($nama)));
        $cleanName = preg_replace('/[^a-z0-9]/', '', $cleanName);
        if (empty($cleanName)) {
            $cleanName = 'user';
        }
        $base = $cleanName . $tahun;
        $username = $base;
        $counter = 1;
        while ($this->whereOne('username', $username)) {
            $username = $base . $counter;
            $counter++;
        }
        return $username;
    }

    public function generateUniqueUsername(string $prefix = 'GR', int $length = 4) {
        $start = strlen($prefix) + 1;
        $stmt = $this->db->prepare(
            "SELECT MAX(CAST(SUBSTRING(username, $start) AS UNSIGNED)) AS max_num FROM {$this->table} WHERE username LIKE CONCAT(:prefix, '%')"
        );
        $stmt->execute(['prefix' => $prefix]);
        $row = $stmt->fetch();
        $next = max(1, (int)($row['max_num'] ?? 0) + 1);
        $username = $prefix . str_pad($next, $length, '0', STR_PAD_LEFT);
        while ($this->whereOne('username', $username)) {
            $next++;
            $username = $prefix . str_pad($next, $length, '0', STR_PAD_LEFT);
        }
        return $username;
    }
}
