<?php
class Bitacora extends Model {
    protected $timestamps = false;
    protected $table = 'bitacora';
    public function listar(int $limit=100, int $offset=0, array $filters=[]) {
        $where = '1=1';
        $params = [];
        if (!empty($filters['usuario_id'])) { $where .= ' AND b.usuario_id=:u'; $params[':u']=$filters['usuario_id']; }
        if (!empty($filters['accion'])) { $where .= ' AND b.accion=:a'; $params[':a']=$filters['accion']; }
        if (!empty($filters['desde'])) { $where .= ' AND b.creado_en>=:d'; $params[':d']=$filters['desde']; }
        if (!empty($filters['hasta'])) { $where .= ' AND b.creado_en<=:h'; $params[':h']=$filters['hasta'].' 23:59:59'; }
        $sql = "SELECT b.*, u.nombre, u.email FROM bitacora b LEFT JOIN usuarios u ON u.id=b.usuario_id WHERE $where ORDER BY b.creado_en DESC LIMIT :l OFFSET :o";
        $s = $this->db->prepare($sql);
        foreach($params as $k=>$v) $s->bindValue($k,$v);
        $s->bindValue(':l',$limit,PDO::PARAM_INT);
        $s->bindValue(':o',$offset,PDO::PARAM_INT);
        $s->execute(); return $s->fetchAll();
    }
    public function totalRegistros() {
        return (int)$this->db->query('SELECT COUNT(*) FROM bitacora')->fetchColumn();
    }
}