<?php
class ArtistController extends Controller {
    public function destacados() {
        $artistas = (new Artista())->todos();
        $this->view('public/artistas_destacados', compact('artistas'), 'public');
    }
    public function dashboard() {
        $this->requireRole('artista');
        $obras = (new Obra())->porArtista(Auth::id());
        $stats = (new Estadistica())->resumen();
        $this->view('artista/dashboard', compact('obras','stats'), 'artist');
    }
    public function metricas() {
        $this->requireRole('artista');
        $obras = (new Obra())->porArtista(Auth::id());
        $this->view('artista/metricas_arte', compact('obras'), 'artist');
    }
    public function estadisticas() {
        $this->requireRole('artista');
        $vistas = (new Estadistica())->visualizacionesPorDia(30);
        $obras  = (new Obra())->porArtista(Auth::id());
        $this->view('artista/estadisticas_detalladas', compact('vistas','obras'), 'artist');
    }
    public function perfilPublico() {
        $this->requireRole('artista');
        $artista = array_merge(
            (new Artista())->conUsuario(Auth::id()) ?? [],
            Auth::user()
        );
        $obras = (new Obra())->porArtista(Auth::id());
        $this->view('artista/perfil_publico', compact('artista','obras'), 'artist');
    }
    public function editarPerfil() {
        $this->requireRole('artista');
        $artista = (new Artista())->conUsuario(Auth::id()) ?? [];
        $this->view('artista/editar_perfil_artista', compact('artista'), 'artist');
    }
    public function guardarPerfil() {
        $this->requireRole('artista'); $this->csrfCheck();
        $d = Request::only(['nombre','especialidad','pais','ciudad','sitio_web','instagram','bio']);
        try {
            if (!empty($_FILES['avatar']['name'])) {
                $up = new Upload('Originales/imagen/Avatares');
                $d['avatar'] = $up->handle($_FILES['avatar']);
                (new Usuario())->update(Auth::id(), ['avatar'=>$d['avatar']]);
            }
            (new Usuario())->update(Auth::id(), ['nombre'=>$d['nombre'],'bio'=>$d['bio']??'']);
            $art = (new Artista())->conUsuario(Auth::id());
            if ($art) (new Artista())->update($art['id'], array_intersect_key($d,array_flip(['especialidad','pais','ciudad','sitio_web','instagram','descripcion'])));
            Logger::accion(Auth::id(),'PERFIL_ARTISTA','Perfil de artista actualizado');
            Session::flash('success','Perfil actualizado.');
        } catch(Throwable $e){ Logger::error($e->getMessage()); Session::flash('error','Error al guardar.'); }
        $this->redirect('artista/editar-perfil');
    }
    public function misFanarts() {
        $this->requireRole('artista');
        $stmt = Database::getInstance()->prepare('SELECT f.*,u.nombre as creador FROM fanarts f JOIN usuarios u ON u.id=f.usuario_id WHERE f.artista_id=:id ORDER BY f.creado_en DESC');
        $stmt->execute([':id' => Auth::id()]);
        $fanarts = $stmt->fetchAll();
        $this->view('artista/mis_fanarts', compact('fanarts'), 'artist');
    }
    public function colaboraciones() {
        $this->requireRole('artista');
        $colaboraciones = (new Colaboracion())->deArtista(Auth::id());
        $this->view('artista/colaboraciones', compact('colaboraciones'), 'artist');
    }
    public function premios() {
        $this->requireRole('artista');
        $stmt = Database::getInstance()->prepare('SELECT * FROM premios WHERE artista_id=:id ORDER BY anio DESC');
        $stmt->execute([':id' => Auth::id()]);
        $premios = $stmt->fetchAll();
        $this->view('artista/premios', compact('premios'), 'artist');
    }
    public function misContactos() {
        $this->requireRole('artista');
        $contactos = (new ContactoArtista())->recibidos(Auth::id());
        $this->view('artista/mis_contactos', compact('contactos'), 'artist');
    }
    public function perfilPublicoById(string $id): void {
        $artista = (new Artista())->conUsuario((int)$id);
        if (!$artista) { http_response_code(404); $this->view('errors/404', [], 'public'); return; }
        $obras = (new Obra())->porArtista((int)$id);
        // Only show approved works on public profile
        $obras = array_filter($obras, function($o){ return $o['estado'] === 'aprobada'; });
        $this->view('artista/perfil_publico', compact('artista', 'obras'), 'public');
    }
}
