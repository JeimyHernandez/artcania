<?php
class GalleryController extends Controller {
    public function index() {
        $page   = max(1,(int)Request::get('page',1));
        $limit  = 12; $offset = ($page-1)*$limit;
        $obras  = (new Obra())->publicadas($limit, $offset);
        $this->view('public/galeria', compact('obras','page'), 'public');
    }
}
