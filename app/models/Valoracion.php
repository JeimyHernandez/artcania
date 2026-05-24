<?php
class Valoracion extends Model {
    protected $table = 'valoraciones';
    public function valorar(int $userId, int $obraId, int $puntos) {
        $s=$this->db->prepare('INSERT INTO valoraciones (usuario_id,obra_id,puntuacion) VALUES (:u,:o,:p) ON DUPLICATE KEY UPDATE puntuacion=:p2,actualizado_en=NOW()');
        $s->bindValue(':u',$userId);$s->bindValue(':o',$obraId);$s->bindValue(':p',$puntos);$s->bindValue(':p2',$puntos);$s->execute();
        $avg=$this->db->prepare('SELECT AVG(puntuacion) FROM valoraciones WHERE obra_id=:o');
        $avg->bindValue(':o',$obraId);$avg->execute();
        $this->db->prepare('UPDATE obras SET valoracion_promedio=? WHERE id=?')->execute([$avg->fetchColumn(),$obraId]);
    }
}