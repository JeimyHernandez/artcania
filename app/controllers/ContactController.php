<?php
class ContactController extends Controller {
    public function enviar() {
        $this->requireAuth(); $this->csrfCheck();
        $d = Request::only(['artista_id','asunto','mensaje']);
        $v = new Validation();
        if(!$v->check($d,['artista_id'=>'required|numeric','asunto'=>'required|min:3','mensaje'=>'required|min:10'])){
            $this->json(['error'=>$v->errors()],422); return;
        }
        $id = (new ContactoArtista())->enviar((int)$d['artista_id'],Auth::id(),$d['asunto'],$d['mensaje']);
        Logger::accion(Auth::id(), 'CONTACTO_ARTISTA', "user=".Auth::id()." artista=".$d['artista_id']." contacto=$id");
        $this->json(['ok'=>true]);
    }
}