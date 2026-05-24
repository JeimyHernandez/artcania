<?php
class SearchController extends Controller {
    public function buscar() {
        $q = trim(Request::get('q',''));
        $obras = $q ? (new Obra())->buscar($q) : [];
        $this->view('public/galeria', compact('obras','q'), 'public');
    }
}
