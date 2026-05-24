<?php
class Etiqueta extends Model {
    protected $table = 'etiquetas';
    public function deObra(int $obraId) {
        $s=$this->db->prepare('SELECT e.* FROM etiquetas e JOIN obra_etiquetas oe ON oe.etiqueta_id=e.id WHERE oe.obra_id=:id');
        $s->bindValue(':id',$obraId); $s->execute(); return $s->fetchAll();
    }
}