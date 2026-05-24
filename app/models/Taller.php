<?php
class Taller extends Model {
    protected $table = 'talleres';
    public function disponibles() {
        $s=$this->db->query('SELECT t.*,u.nombre as instructor FROM talleres t JOIN usuarios u ON u.id=t.instructor_id WHERE t.activo=1 AND t.inscritos<t.capacidad ORDER BY t.fecha_inicio');
        return $s->fetchAll();
    }
    public function inscribir(int $tallerId, int $userId) {
        try {
            $this->db->beginTransaction();
            $this->db->prepare('INSERT INTO inscripciones_taller(taller_id,usuario_id) VALUES(?,?)')->execute([$tallerId,$userId]);
            $this->db->prepare('UPDATE talleres SET inscritos=inscritos+1 WHERE id=?')->execute([$tallerId]);
            $this->db->commit(); return true;
        } catch(Throwable $e){ $this->db->rollBack(); return false; }
    }
}
