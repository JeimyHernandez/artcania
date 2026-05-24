<?php
class Subasta extends Model {
    protected $table = 'subastas';
    public function activas() {
        $s=$this->db->prepare('SELECT s.*,o.titulo,o.imagen_principal,u.nombre as artista FROM subastas s JOIN obras o ON o.id=s.obra_id JOIN usuarios u ON u.id=o.artista_id WHERE s.estado="activa" AND s.fecha_fin>NOW() ORDER BY s.fecha_fin ASC');
        $s->execute();return $s->fetchAll();
    }
    public function pujar(int $subastaId, int $userId, float $monto) {
        try {
            $this->db->beginTransaction();
            $s=current($this->where('id',$subastaId));
            if(!$s||$monto<=$s['precio_actual']) throw new RuntimeException('Puja inválida.');
            $this->db->prepare('INSERT INTO pujas (subasta_id,usuario_id,monto) VALUES (?,?,?)')->execute([$subastaId,$userId,$monto]);
            $this->db->prepare('UPDATE subastas SET precio_actual=?,ganador_id=? WHERE id=?')->execute([$monto,$userId,$subastaId]);
            $this->db->commit(); return true;
        } catch(Throwable $e){$this->db->rollBack();return false;}
    }
    public function contarCompletadas(): int {
        $r = $this->db->query("SELECT COUNT(*) FROM subastas WHERE estado='finalizada'")->fetchColumn();
        return (int)$r;
    }
}
