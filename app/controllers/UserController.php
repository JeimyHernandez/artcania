<?php
class UserController extends Controller {
    public function perfil() {
        $this->requireAuth();
        $user  = (new Usuario())->find(Auth::id());
        $favs  = (new Favorito())->deUsuario(Auth::id());
        $notif = (new Notificacion())->deUsuario(Auth::id());
        $this->view('usuario/perfil', compact('user','favs','notif'), 'user');
    }
    public function actualizar() {
        $this->requireAuth(); $this->csrfCheck();
        $d = Request::only(['nombre','bio']);
        try {
            if (!empty($_FILES['avatar']['name'])) {
                $up = new Upload('Originales/imagen/Avatares');
                $d['avatar'] = $up->handle($_FILES['avatar']);
            }
            (new Usuario())->update(Auth::id(), $d);
            $_SESSION['user']['nombre'] = $d['nombre'] ?? $_SESSION['user']['nombre'];
            Logger::accion(Auth::id(),'PERFIL_ACTUALIZADO','Perfil actualizado');
            Session::flash('success','Perfil actualizado.');
        } catch(Throwable $e){ Logger::error($e->getMessage()); Session::flash('error','Error.'); }
        $this->redirect('perfil');
    }
    public function editarPerfil() {
        $this->requireAuth();
        $user = (new Usuario())->find(Auth::id());
        $this->view('usuario/editar_perfil', compact('user'), 'user');
    }
    public function misFavoritos() {
        $this->requireAuth();
        $favs = (new Favorito())->deUsuario(Auth::id());
        $this->view('usuario/mis_favoritos', compact('favs'), 'user');
    }
    public function misValoraciones() {
        $this->requireAuth();
        $stmt = Database::getInstance()->prepare(
            'SELECT v.*,o.titulo,o.imagen_principal FROM valoraciones v JOIN obras o ON o.id=v.obra_id WHERE v.usuario_id=:u ORDER BY v.actualizado_en DESC'
        );
        $stmt->execute([':u' => Auth::id()]);
        $valoraciones = $stmt->fetchAll();
        $this->view('usuario/mis_valoraciones', compact('valoraciones'), 'user');
    }
    public function notificaciones() {
        $this->requireAuth();
        $notifs = (new Notificacion())->deUsuario(Auth::id());
        $this->view('usuario/notificaciones', compact('notifs'), 'user');
    }
    public function notifCount() {
        if (!Auth::check()) { $this->json(['count'=>0]); return; }
        $this->json(['count'=>(new Notificacion())->noLeidas(Auth::id())]);
    }
    public function toggleFavorito() {
        $this->requireAuth();
        $this->csrfCheck();
        $obraId = (int)Request::post('obra_id');
        if ($obraId <= 0) { $this->json(['error'=>'ID inválido.'], 400); return; }
        $added  = (new Favorito())->toggle(Auth::id(), $obraId);
        $this->json(['ok'=>true, 'added'=>$added]);
    }
    public function marcarLeida() {
        $this->requireAuth();
        $id = (int)Request::post('id');
        (new Notificacion())->marcarLeida($id);
        $this->json(['ok'=>true]);
    }
}