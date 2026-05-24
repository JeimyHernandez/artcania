<?php
class CuratorController extends Controller {
    public function dashboard() {
        $this->requireRole('curador');
        $pendientes = (new Obra())->pendientes();
        $comentarios= (new Comentario())->pendientes();
        $this->view('curador/dashboard', compact('pendientes','comentarios'), 'curator');
    }
    public function validar() {
        $this->requireRole('curador'); $this->csrfCheck();
        $obraId = (int)Request::post('obra_id');
        $estado = Request::post('estado');
        $nota   = Request::post('nota','');
        if (!in_array($estado,['aprobada','rechazada'])) { Session::flash('error','Estado inválido.'); $this->redirect('curador/dashboard'); return; }
        (new Obra())->update($obraId,['estado'=>$estado,'nota_curador'=>$nota]);
        Logger::accion(Auth::id(),'OBRA_VALIDADA',"Obra $obraId -> $estado");
        Session::flash('success','Obra '.$estado.' correctamente.');
        $this->redirect('curador/dashboard');
    }
    public function obrasPendientes() {
        $this->requireRole('curador');
        $pendientes = (new Obra())->pendientes();
        $this->view('curador/obras_pendientes', compact('pendientes'), 'curator');
    }
    public function historial() {
        $this->requireRole('curador');
        $historial = Database::getInstance()->query("SELECT h.*,u.nombre as curador_nombre,o.titulo FROM historial_estados_obra h JOIN usuarios u ON u.id=h.usuario_id JOIN obras o ON o.id=h.obra_id ORDER BY h.creado_en DESC LIMIT 200")->fetchAll();
        $this->view('curador/historial_validaciones', compact('historial'), 'curator');
    }
    public function gestionExposiciones() {
        $this->requireRole('curador');
        $exposiciones = (new Exposicion())->activas();
        $this->view('curador/gestion_exposiciones', compact('exposiciones'), 'curator');
    }
    public function crearExposicion() {
        $this->requireRole('curador'); $this->csrfCheck();
        $d = Request::only(['titulo','descripcion','fecha_inicio','fecha_fin','tipo']);
        $d['curador_id'] = Auth::id(); $d['publica'] = 0;
        (new Exposicion())->insert($d);
        Session::flash('success','Exposición creada.');
        $this->redirect('curador/exposiciones');
    }
    public function destacados() {
        $this->requireRole('curador');
        $obras = (new Obra())->publicadas(50);
        $this->view('curador/seleccionar_destacados', compact('obras'), 'curator');
    }
    public function destacar() {
        $this->requireRole('curador'); $this->csrfCheck();
        $obraId = (int)Request::post('obra_id');
        $dest   = (int)Request::post('destacada');
        (new Obra())->update($obraId, ['destacada'=>$dest]);
        Logger::accion(Auth::id(),'OBRA_DESTACADA',"Obra $obraId destacada=$dest");
        $this->redirect('curador/destacados');
    }
    public function moderarComentarios() {
        $this->requireRole('curador');
        $comentarios = (new Comentario())->pendientes();
        $this->view('curador/moderar_comentarios', compact('comentarios'), 'curator');
    }
    public function aprobarComentario() {
        $this->requireRole('curador'); $this->csrfCheck();
        (new Comentario())->update((int)Request::post('id'), ['estado'=>'aprobado']);
        Session::flash('success','Comentario aprobado.');
        $this->redirect('curador/comentarios');
    }
    public function rechazarComentario() {
        $this->requireRole('curador'); $this->csrfCheck();
        (new Comentario())->update((int)Request::post('id'), ['estado'=>'rechazado']);
        Session::flash('success','Comentario rechazado.');
        $this->redirect('curador/comentarios');
    }
    public function reportes() {
        $this->requireRole('curador');
        $reportes = Database::getInstance()->query("SELECT r.*,u.nombre as denunciante FROM reportes_contenido r JOIN usuarios u ON u.id=r.usuario_id ORDER BY r.creado_en DESC LIMIT 200")->fetchAll();
        $this->view('curador/reportes_contenido', compact('reportes'), 'curator');
    }
    public function metricas() {
        $this->requireRole('curador');
        $stats   = (new Estadistica())->resumen();
        $vistas  = (new Estadistica())->visualizacionesPorDia(30);
        $topObras= (new Estadistica())->obrasMasVistas(10);
        $cats    = (new Estadistica())->categoriasPopulares();
        $this->view('curador/metricas_galeria', compact('stats','vistas','topObras','cats'), 'curator');
    }
}