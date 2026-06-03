<footer class="footer-magic mt-5">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="footer-brand mb-2">✦ Artcania</div>
        <p class="small" style="color:rgba(244,247,251,.4);line-height:1.7">
          Un mundo donde el arte no tiene límites. Descubre, conecta y colecciona arte digital extraordinario.
        </p>
        <div class="d-flex gap-2 mt-3">
          <a href="#" class="btn btn-sm" style="background:rgba(166,189,255,.08);border:1px solid rgba(166,189,255,.15);color:var(--lavender);border-radius:8px;width:34px;height:34px;display:flex;align-items:center;justify-content:center">
            <i class="fab fa-twitter" style="font-size:.8rem"></i>
          </a>
          <a href="#" class="btn btn-sm" style="background:rgba(166,189,255,.08);border:1px solid rgba(166,189,255,.15);color:var(--lavender);border-radius:8px;width:34px;height:34px;display:flex;align-items:center;justify-content:center">
            <i class="fab fa-instagram" style="font-size:.8rem"></i>
          </a>
          <a href="#" class="btn btn-sm" style="background:rgba(166,189,255,.08);border:1px solid rgba(166,189,255,.15);color:var(--lavender);border-radius:8px;width:34px;height:34px;display:flex;align-items:center;justify-content:center">
            <i class="fab fa-discord" style="font-size:.8rem"></i>
          </a>
        </div>
      </div>
      <div class="col-md-2">
        <h6 class="mb-3">Explorar</h6>
        <a href="<?=url('galeria')?>"     class="footer-link">Galería</a>
        <a href="<?=url('artistas')?>"    class="footer-link">Artistas</a>
        <a href="<?=url('subastas')?>"    class="footer-link">Subastas</a>
        <a href="<?=url('exposiciones')?>"class="footer-link">Exposiciones</a>
        <a href="<?=url('videos')?>"      class="footer-link">Videos</a>
        <a href="<?=url('fanarts')?>"     class="footer-link">Fan Art</a>
      </div>
      <div class="col-md-2">
        <h6 class="mb-3">Cuenta</h6>
        <a href="<?=url('login')?>"   class="footer-link">Iniciar sesión</a>
        <a href="<?=url('registro')?>"class="footer-link">Registrarse</a>
        <a href="<?=url('about')?>"   class="footer-link">Acerca de</a>
      </div>
      <div class="col-md-4">
        <h6 class="mb-3">Un mundo mágico del arte</h6>
        <div class="d-flex gap-2 flex-wrap">
          <span class="badge" style="background:rgba(92,77,155,.2);border:1px solid rgba(92,77,155,.3);color:var(--lavender)">✦ Magia</span>
          <span class="badge" style="background:rgba(93,214,192,.1);border:1px solid rgba(93,214,192,.25);color:var(--teal)">✧ Creatividad</span>
          <span class="badge" style="background:rgba(166,189,255,.08);border:1px solid rgba(166,189,255,.2);color:var(--lavender)">✦ Inspiración</span>
          <span class="badge" style="background:rgba(93,214,192,.1);border:1px solid rgba(93,214,192,.25);color:var(--teal)">✧ Fantasía</span>
        </div>
      </div>
    </div>
    <hr class="divider-magic">
    <p class="text-center copyright mb-0">
      &copy; <?= date('Y') ?> Artcania &nbsp;·&nbsp; Todos los derechos reservados
      &nbsp;·&nbsp; <span style="color:var(--teal)">✦</span> Hecho con magia
    </p>
  </div>
</footer>

