<?php
class VideoController extends Controller {
    public function index() {
        $videos = (new Video())->all();
        $this->view('public/videos', compact('videos'), 'public');
    }
}
