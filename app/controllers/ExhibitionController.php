<?php
class ExhibitionController extends Controller {
    public function index() {
        $exposiciones = (new Exposicion())->all();
        $this->view('public/exposiciones_virtuales', compact('exposiciones'), 'public');
    }
}
