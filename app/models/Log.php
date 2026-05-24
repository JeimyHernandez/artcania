<?php
class Log extends Model {
    protected $table = 'errores_sistema';
    public function registrar(string $nivel, string $msg, string $archivo='', int $linea=0, $userId=null) {
        try {
            $this->insert(['nivel'=>$nivel,'mensaje'=>$msg,'archivo'=>$archivo,'linea'=>$linea,'usuario_id'=>$userId,'url'=>$_SERVER['REQUEST_URI']??'']);
        } catch(Throwable $e){}
    }
    public function recientes(string $nivel='', int $limit=50) {
        $where = $nivel ? "WHERE nivel=:n" : "";
        $sql="SELECT * FROM errores_sistema $where ORDER BY creado_en DESC LIMIT :l";
        $s=$this->db->prepare($sql);
        if($nivel) $s->bindValue(':n',$nivel);
        $s->bindValue(':l',$limit,PDO::PARAM_INT);
        $s->execute(); return $s->fetchAll();
    }
}