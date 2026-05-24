<?php
class ArtworkController extends Controller {
    public function show(string $id) {
        $obra = (new Obra())->find((int)$id);
        if (!$obra) { http_response_code(404); $this->view('errors/404',[],'public'); return; }
        (new Obra())->registrarVisualizacion((int)$id, Auth::id());
        $comentarios = (new Comentario())->deObra((int)$id);
        $this->view('public/obra_detalle', compact('obra','comentarios'), 'public');
    }
    public function misObras() {
        $this->requireRole('artista');
        $obras = (new Obra())->porArtista(Auth::id());
        $this->view('artista/mis_obras', compact('obras'), 'artist');
    }
    public function guardar() {
        $this->requireRole('artista');
        $this->csrfCheck();
        $d = Request::only(['titulo','descripcion','tecnica','dimensiones','precio','categoria_id']);
        $v = new Validation();
        if (!$v->check($d,['titulo'=>'required|min:3|max:200','descripcion'=>'required|min:10'])) {
            Session::flash('errors',$v->errors()); $this->redirect('artista/obras'); return;
        }
        try {
            $d['artista_id'] = Auth::id(); $d['estado'] = 'pendiente';
            if (!empty($_FILES['imagen']['name'])) {
                $up = new Upload('Originales/imagen/Obras_digitales');
                $d['imagen_principal'] = $up->handle($_FILES['imagen']);
            }
            $id = (new Obra())->crear($d);
            Logger::accion(Auth::id(),'OBRA_CREADA',"Obra ID $id");
            Session::flash('success','Obra enviada para revisión.');
        } catch(Throwable $e) { Logger::error($e->getMessage()); Session::flash('error','Error al guardar obra.'); }
        $this->redirect('artista/obras');
    }
    public function pujar() {
        $this->requireAuth();
        if (!Request::isAjax()) { $this->json(['error'=>'AJAX only'],400); return; }
        $subastaId = (int)Request::post('subasta_id');
        $monto     = (float)Request::post('monto');
        try {
            $db = Database::getInstance();
            $s  = $db->prepare('CALL sp_pujar_subasta(:s,:u,:m)');
            $s->bindValue(':s',$subastaId); $s->bindValue(':u',Auth::id()); $s->bindValue(':m',$monto);
            $s->execute(); $r = $s->fetch();
            $this->json(['ok'=>true]);
        } catch(Throwable $e){ $this->json(['error'=>$e->getMessage()],422); }
    }
    public function editarForm(string $id): void {
        $this->requireRole('artista');
        $obra = (new Obra())->find((int)$id);
        if (!$obra || (int)$obra['artista_id'] !== Auth::id()) {
            Session::flash('error', 'Obra no encontrada o sin permiso.');
            $this->redirect('artista/obras');
        }
        $this->view('artista/editar_obra', compact('obra'), 'artist');
    }
    public function actualizar(string $id): void {
        $this->requireRole('artista');
        $this->csrfCheck();
        $obra = (new Obra())->find((int)$id);
        if (!$obra || (int)$obra['artista_id'] !== Auth::id()) {
            Session::flash('error', 'Obra no encontrada o sin permiso.');
            $this->redirect('artista/obras');
        }
        $d = Request::only(['titulo','descripcion','tecnica','dimensiones','precio']);
        $v = new Validation();
        if (!$v->check($d, ['titulo'=>'required|min:3|max:200','descripcion'=>'required|min:10'])) {
            Session::flash('errors', $v->errors());
            $this->redirect('artista/obras/'.$id.'/editar');
            return;
        }
        try {
            if (!empty($_FILES['imagen']['name'])) {
                $up = new Upload('Originales/imagen/Obras_digitales');
                $d['imagen_principal'] = $up->handle($_FILES['imagen']);
            }
            $d['estado'] = 'pendiente'; // re-submit for curation
            (new Obra())->guardar((int)$id, $d);
            Logger::accion(Auth::id(), 'OBRA_EDITADA', "Obra ID $id editada");
            Session::flash('success', 'Obra actualizada y enviada nuevamente a revisión.');
        } catch (Throwable $e) {
            Logger::error($e->getMessage());
            Session::flash('error', 'Error al actualizar la obra.');
        }
        $this->redirect('artista/obras');
    }
    public function comentar() {
        $this->requireAuth(); $this->csrfCheck();
        $obraId = (int)Request::post('obra_id');
        $texto  = trim(Request::post('texto',''));
        if (!$texto || strlen($texto)<5) { Session::flash('error','Comentario muy corto.'); $this->redirect("obra/$obraId"); return; }
        (new Comentario())->crear(Auth::id(), $obraId, $texto);
        Session::flash('success','Comentario enviado para revisión.');
        $this->redirect("obra/$obraId");
    }
}