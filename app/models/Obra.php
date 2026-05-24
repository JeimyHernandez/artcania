<?php
class Obra extends Model {
    protected $table = 'obras';
    public function porArtista(int $artistaId) {
        $s = $this->db->prepare('SELECT o.*,u.nombre as artista_nombre FROM obras o JOIN usuarios u ON u.id=o.artista_id WHERE o.artista_id=:a ORDER BY o.creado_en DESC');
        $s->bindValue(':a',$artistaId); $s->execute(); return $s->fetchAll();
    }
    public function publicadas(int $limit=12, int $offset=0) {
        $s=$this->db->prepare('SELECT o.*,u.nombre as artista_nombre FROM obras o JOIN usuarios u ON u.id=o.artista_id WHERE o.estado="aprobada" ORDER BY o.creado_en DESC LIMIT :l OFFSET :o');
        $s->bindValue(':l',$limit,PDO::PARAM_INT);$s->bindValue(':o',$offset,PDO::PARAM_INT);$s->execute();return $s->fetchAll();
    }
    public function buscar(string $q) {
        $like = "%$q%";
        $s = $this->db->prepare('SELECT o.*,u.nombre as artista_nombre FROM obras o JOIN usuarios u ON u.id=o.artista_id WHERE o.estado="aprobada" AND (o.titulo LIKE :q1 OR o.descripcion LIKE :q2 OR o.tecnica LIKE :q3)');
        $s->bindValue(':q1', $like);
        $s->bindValue(':q2', $like);
        $s->bindValue(':q3', $like);
        $s->execute();
        return $s->fetchAll();
    }
    public function pendientes() {
        $s=$this->db->prepare('SELECT o.*,u.nombre as artista_nombre FROM obras o JOIN usuarios u ON u.id=o.artista_id WHERE o.estado="pendiente" ORDER BY o.creado_en');
        $s->execute(); return $s->fetchAll();
    }
    public function registrarVisualizacion(int $obraId, $userId) {
        try {
            $s=$this->db->prepare('INSERT INTO visualizaciones (obra_id, usuario_id, ip) VALUES (:o,:u,:ip)');
            $s->bindValue(':o',$obraId); $s->bindValue(':u',$userId); $s->bindValue(':ip',$_SERVER['REMOTE_ADDR']??'');
            $s->execute();
            $this->db->prepare('UPDATE obras SET visualizaciones=visualizaciones+1 WHERE id=:id')->execute([':id'=>$obraId]);
        } catch(Throwable $e){}
    }
    public function estadisticas() {
        $s=$this->db->query('SELECT DATE(creado_en) as fecha, COUNT(*) as total, SUM(visualizaciones) as views FROM obras GROUP BY DATE(creado_en) ORDER BY fecha DESC LIMIT 30');
        return $s->fetchAll();
    }
    public function crear(array $d) { return $this->insert($d); }
    public function guardar(int $id, array $d) { return $this->update($id,$d); }
    public function contarPublicadas(): int {
        $r = $this->db->query("SELECT COUNT(*) FROM obras WHERE estado='aprobada'")->fetchColumn();
        return (int)$r;
    }
}
