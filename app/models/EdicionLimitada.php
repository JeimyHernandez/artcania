<?php
class EdicionLimitada extends Model {
    protected $table = 'ediciones_limitadas';
    public function disponibles() {
        $s = $this->db->prepare("SELECT el.*,o.titulo as obra_titulo,u.nombre as artista FROM ediciones_limitadas el JOIN obras o ON o.id=el.obra_id JOIN usuarios u ON u.id=o.artista_id WHERE el.activa=1 AND el.stock_disponible>0 ORDER BY el.creado_en DESC");
        $s->execute(); return $s->fetchAll();
    }
}