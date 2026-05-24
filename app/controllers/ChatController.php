<?php
class ChatController extends Controller {
    public function index() {
        $this->requireAuth();
        $convs = (new ConversacionChat())->deUsuario(Auth::id());
        $this->view('usuario/chat', compact('convs'), 'user');
    }
    public function enviar() {
        $this->requireAuth();
        if (!Request::isAjax()) { $this->json(['error'=>'AJAX only'],400); return; }
        $convId = (int)Request::post('conv_id');
        $msg    = trim(Request::post('mensaje',''));
        if (!$msg) { $this->json(['error'=>'Mensaje vacío'],400); return; }
        $id = (new MensajeChat())->enviar($convId, Auth::id(), $msg);
        $this->db_touchConv($convId);
        Logger::chat("user=".Auth::id()." conv=$convId msg_id=$id");
        $this->json(['ok'=>true,'id'=>$id,'ts'=>date('H:i')]);
    }
    public function mensajes(string $convId) {
        $this->requireAuth();
        $msgs = (new MensajeChat())->deConversacion((int)$convId);
        (new MensajeChat())->marcarLeidos((int)$convId, Auth::id());
        $this->json($msgs);
    }
    public function conversaciones() {
        $this->requireAuth();
        $convs = (new ConversacionChat())->deUsuario(Auth::id());
        $this->view('usuario/mis_conversaciones', compact('convs'), 'user');
    }
    private function db_touchConv(int $id) {
        Database::getInstance()->prepare('UPDATE conversaciones_chat SET actualizado_en=NOW() WHERE id=?')->execute([$id]);
    }
}
