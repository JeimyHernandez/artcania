<?php
class Estadistica extends Model {
    protected $table = 'estadisticas_diarias';
    public function resumen() {
        return [
            'usuarios'    => (int)$this->db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn(),
            'obras'       => (int)$this->db->query('SELECT COUNT(*) FROM obras WHERE estado="aprobada"')->fetchColumn(),
            'artistas'    => (int)$this->db->query('SELECT COUNT(*) FROM usuarios WHERE rol="artista"')->fetchColumn(),
            'subastas'    => (int)$this->db->query('SELECT COUNT(*) FROM subastas WHERE estado="activa"')->fetchColumn(),
            'comentarios' => (int)$this->db->query('SELECT COUNT(*) FROM comentarios')->fetchColumn(),
            'ventas'      => (float)($this->db->query('SELECT COALESCE(SUM(monto_total),0) FROM ventas')->fetchColumn()),
        ];
    }
    public function visualizacionesPorDia(int $dias=30) {
        $s=$this->db->prepare('SELECT DATE(creado_en) as fecha,COUNT(*) as total FROM visualizaciones WHERE creado_en>=DATE_SUB(NOW(),INTERVAL :d DAY) GROUP BY DATE(creado_en) ORDER BY fecha');
        $s->bindValue(':d',$dias,PDO::PARAM_INT);$s->execute();return $s->fetchAll();
    }
    public function obrasMasVistas(int $limit=10) {
        $s=$this->db->prepare('SELECT o.*,u.nombre as artista FROM obras o JOIN usuarios u ON u.id=o.artista_id WHERE o.estado="aprobada" ORDER BY o.visualizaciones DESC LIMIT :l');
        $s->bindValue(':l',$limit,PDO::PARAM_INT);$s->execute();return $s->fetchAll();
    }
    public function ventasPorMes() {
        $s=$this->db->query("SELECT DATE_FORMAT(creado_en,'%Y-%m') as mes,COUNT(*) as total,SUM(monto_total) as ingresos FROM ventas GROUP BY mes ORDER BY mes DESC LIMIT 12");
        return $s->fetchAll();
    }
    public function categoriasPopulares() {
        $s=$this->db->query('SELECT c.nombre,COUNT(o.id) as total FROM categorias c JOIN obras o ON o.categoria_id=c.id WHERE o.estado="aprobada" GROUP BY c.id ORDER BY total DESC');
        return $s->fetchAll();
    }
}