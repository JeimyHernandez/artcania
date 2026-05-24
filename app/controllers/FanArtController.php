<?php
class FanArtController extends Controller {
    public function index() {
        $fanarts = (new FanArt())->all();
        $this->view('public/fanarts', compact('fanarts'), 'public');
    }
}
