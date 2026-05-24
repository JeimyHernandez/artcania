<?php
class PublicController extends Controller {
    public function home() {
        $obras         = (new Obra())->publicadas(8);
        $artistas      = (new Artista())->destacados(4);
        $subastas      = (new Subasta())->activas();
        $stat_obras    = (new Obra())->contarPublicadas();
        $stat_artistas = (new Artista())->contarActivos();
        $stat_usuarios = (new Usuario())->contarUsuarios();
        $stat_subastas = (new Subasta())->contarCompletadas();
        $this->view('public/home', compact(
            'obras','artistas','subastas',
            'stat_obras','stat_artistas','stat_usuarios','stat_subastas'
        ), 'public');
    }
    public function subastas() {
        $subastas = (new Subasta())->activas();
        $this->view('public/subastas', compact('subastas'), 'public');
    }
    public function about() { $this->view('public/about', [], 'public'); }
    public function subastaDetalle(string $id): void {
        $subasta = (new Subasta())->find((int)$id);
        if (!$subasta || $subasta['estado'] !== 'activa') {
            $this->redirect('subastas');
        }
        $this->view('public/subastas', ['subastas' => (new Subasta())->activas()], 'public');
    }
}

