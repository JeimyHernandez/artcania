<?php
class Categoria extends Model {
    protected $table = 'categorias';
    public function activas() {
        $s=$this->db->query('SELECT * FROM categorias WHERE activa=1 ORDER BY orden');
        return $s->fetchAll();
    }
}
