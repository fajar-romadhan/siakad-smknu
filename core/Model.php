<?php
class Model {
    protected $db;
    protected $table;
    public function __construct() { $this->db = getDB(); }
    public function all($order='id DESC') {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY $order")->fetchAll();
    }
    public function find($id) {
        $st=$this->db->prepare("SELECT * FROM {$this->table} WHERE id=?");
        $st->execute([$id]); return $st->fetch();
    }
    public function where($col,$val) {
        $st=$this->db->prepare("SELECT * FROM {$this->table} WHERE $col=?");
        $st->execute([$val]); return $st->fetchAll();
    }
    public function whereOne($col,$val) {
        $st=$this->db->prepare("SELECT * FROM {$this->table} WHERE $col=? LIMIT 1");
        $st->execute([$val]); return $st->fetch();
    }
    public function findOneBy(array $conditions, array $params = []) {
        $parts=[];
        foreach ($conditions as $col => $val) {
            if ($val === null) {
                $parts[] = "$col IS NULL";
            } else {
                $parts[] = "$col=?";
            }
        }
        $sql = "SELECT * FROM {$this->table}";
        if ($parts) {
            $sql .= " WHERE " . implode(' AND ', $parts);
        }
        $st = $this->db->prepare($sql . " LIMIT 1");
        $values = [];
        foreach ($conditions as $val) {
            if ($val !== null) {
                $values[] = $val;
            }
        }
        if ($params) {
            $values = $params;
        }
        $st->execute($values);
        return $st->fetch();
    }
    public function insert($data) {
        $cols=implode(',',array_keys($data));
        $phs=implode(',',array_fill(0,count($data),'?'));
        $st=$this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($phs)");
        try {
            $st->execute(array_values($data));
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "doesn't have a default value") || str_contains($e->getMessage(), "1364")) {
                try {
                    $this->db->exec("SET FOREIGN_KEY_CHECKS=0");
                    $this->db->exec("ALTER TABLE `{$this->table}` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT");
                    $this->db->exec("SET FOREIGN_KEY_CHECKS=1");
                } catch (Exception $ex) {
                    try { $this->db->exec("SET FOREIGN_KEY_CHECKS=1"); } catch (Exception $ex2) {}
                }
                if (!isset($data['id'])) {
                    $maxId = (int)$this->db->query("SELECT COALESCE(MAX(id), 0) FROM `{$this->table}`")->fetchColumn();
                    $data['id'] = $maxId + 1;
                }
                $cols=implode(',',array_keys($data));
                $phs=implode(',',array_fill(0,count($data),'?'));
                $st=$this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($phs)");
                $st->execute(array_values($data));
                return $this->db->lastInsertId() ?: $data['id'];
            }
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'Integrity constraint violation')) {
                if (function_exists('ensureNilaiMonthlySchema')) {
                    ensureNilaiMonthlySchema($this->db);
                }
                if ($this->table === 'nilai' && isset($data['siswa_id'], $data['mapel_id'], $data['kelas_id'], $data['bulan'], $data['tahun'])) {
                    $conditions = [
                        'siswa_id' => (int)$data['siswa_id'],
                        'mapel_id' => (int)$data['mapel_id'],
                        'kelas_id' => (int)$data['kelas_id'],
                        'bulan' => (int)$data['bulan'],
                        'tahun' => (int)$data['tahun'],
                    ];
                    if (isset($data['tahun_ajaran_id'])) {
                        $conditions['tahun_ajaran_id'] = (int)$data['tahun_ajaran_id'];
                    }
                    $existing = $this->findOneBy($conditions);
                    if ($existing) {
                        $this->update($existing['id'], $data);
                        return $existing['id'];
                    }
                }
                $st=$this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($phs)");
                $st->execute(array_values($data));
                return $this->db->lastInsertId();
            }
            throw $e;
        }
    }
    public function update($id,$data) {
        $set=implode('=?,',array_keys($data)).'=?';
        $vals=array_values($data); $vals[]=$id;
        $this->db->prepare("UPDATE {$this->table} SET $set WHERE id=?")->execute($vals);
    }
    public function insertOrUpdate(array $data, array $conditions, array $params = []) {
        $existing = $this->findOneBy($conditions, $params);
        if ($existing) {
            $this->update($existing['id'], $data);
            return $existing['id'];
        }
        return $this->insert($data);
    }
    public function delete($id) {
        $this->db->prepare("DELETE FROM {$this->table} WHERE id=?")->execute([$id]);
    }
    public function count($where='',$params=[]) {
        $sql="SELECT COUNT(*) as total FROM {$this->table}";
        if($where) $sql.=" WHERE $where";
        $st=$this->db->prepare($sql); $st->execute($params);
        return $st->fetch()['total'];
    }
    public function query($sql,$params=[]) {
        $st=$this->db->prepare($sql); $st->execute($params); return $st->fetchAll();
    }
    public function exec($sql,$params=[]) {
        $st=$this->db->prepare($sql); $st->execute($params); return $st;
    }
    public function generateKode($prefix) {
        $last=$this->db->query("SELECT MAX(id) as m FROM {$this->table}")->fetch();
        $num=($last['m']??0)+1;
        return $prefix.str_pad($num,4,'0',STR_PAD_LEFT);
    }
}
