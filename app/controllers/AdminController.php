<?php
class AdminController extends Controller {
    public function dashboard() {
        $this->requireRole('admin');
        $stats = (new Estadistica())->resumen();
        $this->view('admin/dashboard', compact('stats'), 'admin');
    }
    public function usuarios() {
        $this->requireRole('admin');
        $users = (new Usuario())->all();
        $this->view('admin/gestion_usuarios', compact('users'), 'admin');
    }
    public function bitacora() {
        $this->requireRole('admin');
        $page=max(1,(int)Request::get('page',1)); $limit=50; $offset=($page-1)*$limit;
        $filters=['desde'=>Request::get('desde'),'hasta'=>Request::get('hasta'),'accion'=>Request::get('accion')];
        $logs  = (new Bitacora())->listar($limit,$offset,$filters);
        $total = (new Bitacora())->totalRegistros();
        $pages = ceil($total/$limit);
        $this->view('admin/logs_auditoria', compact('logs','total','pages','page','filters'), 'admin');
    }
    public function verificarUsuario() {
        $this->requireRole('admin');
        $this->csrfCheck();
        $id = (int)Request::post('id');
        if ($id > 0) {
            Database::getInstance()
                ->prepare('UPDATE usuarios SET verificado=1, token_verificacion=NULL, token_expira=NULL WHERE id=:id')
                ->execute([':id' => $id]);
            Logger::accion(Auth::id(), 'ADMIN_VERIFICAR_USUARIO', "Verificación manual usuario ID $id");
            Session::flash('success', 'Usuario verificado manualmente.');
        }
        $this->redirect('admin/usuarios');
    }

    public function toggleUsuario() {
        $this->requireRole('admin'); $this->csrfCheck();
        $id     = (int)Request::post('id');
        $activo = (int)Request::post('activo');
        (new Usuario())->update($id,['activo'=>$activo]);
        Logger::accion(Auth::id(),'USUARIO_TOGGLE',"Usuario $id activo=$activo");
        Logger::admin("Usuario $id activo=$activo por admin=".Auth::id());
        $this->json(['ok'=>true]);
    }
    public function gestionRoles() {
        $this->requireRole('admin');
        $users=(new Usuario())->all();
        $this->view('admin/gestion_roles',compact('users'),'admin');
    }
    public function cambiarRol() {
        $this->requireRole('admin'); $this->csrfCheck();
        $id=(int)Request::post('id'); $rol=Request::post('rol');
        if(!in_array($rol,['usuario','artista','curador','admin'])){$this->json(['error'=>'Rol inválido'],422);return;}
        (new Usuario())->update($id,['rol'=>$rol]);
        Logger::accion(Auth::id(),'ROL_CAMBIO',"User $id -> $rol");
        $this->json(['ok'=>true]);
    }
    public function gestionArtistas() {
        $this->requireRole('admin');
        $artistas = (new Artista())->todos();
        $this->view('admin/gestion_artistas', compact('artistas'), 'admin');
    }
    public function gestionCuradores() {
        $this->requireRole('admin');
        $curadores = (new Curador())->all();
        $this->view('admin/gestion_curadores', compact('curadores'), 'admin');
    }
    public function gestionObras() {
        $this->requireRole('admin');
        $obras = (new Obra())->all();
        $this->view('admin/gestion_obras', compact('obras'), 'admin');
    }
    public function gestionFanarts() {
        $this->requireRole('admin');
        $fanarts = (new FanArt())->all();
        $this->view('admin/gestion_fanarts', compact('fanarts'), 'admin');
    }
    public function gestionExposiciones() {
        $this->requireRole('admin');
        $exposiciones = (new Exposicion())->all();
        $this->view('admin/gestion_exposiciones', compact('exposiciones'), 'admin');
    }
    public function gestionSubastas() {
        $this->requireRole('admin');
        $subastas = (new Subasta())->all();
        $this->view('admin/gestion_subastas', compact('subastas'), 'admin');
    }
    public function gestionChat() {
        $this->requireRole('admin');
        $conversaciones = Database::getInstance()->query('SELECT c.*,u1.nombre as u1_nombre,u2.nombre as u2_nombre FROM conversaciones_chat c JOIN usuarios u1 ON u1.id=c.usuario1_id JOIN usuarios u2 ON u2.id=c.usuario2_id ORDER BY c.actualizado_en DESC LIMIT 100')->fetchAll();
        $this->view('admin/gestion_chat', compact('conversaciones'), 'admin');
    }
    public function gestionContactos() {
        $this->requireRole('admin');
        $contactos = Database::getInstance()->query('SELECT m.*,u.nombre as artista_nombre FROM mensajes_contacto_artista m JOIN usuarios u ON u.id=m.artista_id ORDER BY m.creado_en DESC LIMIT 200')->fetchAll();
        $this->view('admin/gestion_contactos', compact('contactos'), 'admin');
    }
    public function respaldos() {
        $this->requireRole('admin');
        $respaldos = (new Respaldos())->all();
        $this->view('admin/respaldos', compact('respaldos'), 'admin');
    }
    public function configuracion() {
        $this->requireRole('admin');
        $configs = (new Configuracion())->all();
        $this->view('admin/configuracion', compact('configs'), 'admin');
    }
    public function crearRespaldo() {
        $this->requireRole('admin'); $this->csrfCheck();
        $tipo = Request::post('tipo','bd_solo');
        $nombre = 'backup_'.date('Ymd_His').'_'.$tipo;
        
        Database::getInstance()->prepare('INSERT INTO respaldos(nombre,tipo,creado_por) VALUES(?,?,?)')->execute([$nombre,$tipo,Auth::id()]);
        Logger::accion(Auth::id(),'RESPALDO_CREADO',"Respaldo: $nombre");
        Logger::admin("Respaldo creado: $nombre por admin=".Auth::id());
        Session::flash('success','Respaldo registrado: '.$nombre);
        $this->redirect('admin/respaldos');
    }
    public function guardarConfiguracion() {
        $this->requireRole('admin'); $this->csrfCheck();
        $cfgs = $_POST['cfg'] ?? [];
        $db   = Database::getInstance();
        foreach ($cfgs as $clave => $valor) {
            $clave = preg_replace('/[^a-z0-9_]/','',$clave);
            $s = $db->prepare('UPDATE configuracion SET valor=:v WHERE clave=:c');
            $s->bindValue(':v',$valor); $s->bindValue(':c',$clave); $s->execute();
        }
        Logger::accion(Auth::id(),'CONFIG_ACTUALIZADA','Configuración del sistema actualizada');
        Session::flash('success','Configuración guardada.');
        $this->redirect('admin/configuracion');
    }
}