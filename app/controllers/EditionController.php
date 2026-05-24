<?php
class EditionController extends Controller {
    public function index() {
        $ediciones = (new EdicionLimitada())->all();
        $this->view('public/ediciones_limitadas', compact('ediciones'), 'public');
    }
}