<?php
abstract class Model {

    /** @var PDO */
    protected $db;
    protected $table      = '';
    protected $pk         = 'id';
    protected $timestamps = true;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all() {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` ORDER BY `{$this->pk}` DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$this->pk}` = :id LIMIT 1"
        );
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $r = $stmt->fetch();
        return $r ? $r : null;
    }

    public function where($col, $val) {
        $col  = preg_replace('/[^a-zA-Z0-9_]/', '', $col); // sanitize column name
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$col}` = :v"
        );
        $stmt->bindValue(':v', $val);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insert(array $data) {
        if (empty($data)) return 0;
        $cols = array_keys($data);
        $phs  = array_map(function($c) { return ':' . $c; }, $cols);
        $sql  = "INSERT INTO `{$this->table}` (`" . implode('`,`', $cols) . "`) "
              . "VALUES (" . implode(',', $phs) . ")";
        $stmt = $this->db->prepare($sql);
        foreach ($data as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    public function update($id, array $data) {
        if (empty($data)) return false;
        $sets = implode(',', array_map(function($c) {
            return "`{$c}` = :{$c}";
        }, array_keys($data)));

        $ts  = $this->timestamps ? ", `actualizado_en` = NOW()" : '';
        $stmt = $this->db->prepare(
            "UPDATE `{$this->table}` SET {$sets}{$ts} WHERE `{$this->pk}` = :__pk"
        );
        foreach ($data as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':__pk', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare(
            "DELETE FROM `{$this->table}` WHERE `{$this->pk}` = :id"
        );
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM `{$this->table}`");
        return (int)$stmt->fetchColumn();
    }

    public function paginate($limit = 12, $offset = 0) {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` ORDER BY `{$this->pk}` DESC LIMIT :l OFFSET :o"
        );
        $stmt->bindValue(':l', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':o', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}