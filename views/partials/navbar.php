<?php $u = Auth::user(); ?>
<nav class="navbar navbar-expand-lg artcania-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('/') ?>">
      <img src="<?= asset('img/logo_artcadia.png') ?>" alt="Artcadia" class="brand-logo-img">
      <span>ARTCADIA</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
            style="color:rgba(166,189,255,.7)">
      <i class="fa fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="<?=url('galeria')?>"><i class="fa fa-images me-1"></i>Galería</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=url('artistas')?>"><i class="fa fa-palette me-1"></i>Artistas</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=url('subastas')?>"><i class="fa fa-gavel me-1"></i>Subastas</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=url('exposiciones')?>"><i class="fa fa-building me-1"></i>Exposiciones</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=url('videos')?>"><i class="fa fa-play me-1"></i>Videos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?=url('fanarts')?>"><i class="fa fa-star me-1"></i>Fan Art</a></li>
      </ul>

      <form class="d-flex me-3" action="<?=url('buscar')?>">
        <div class="input-group input-group-sm">
          <input class="form-control" name="q" placeholder="Buscar obras, artistas..."
                 value="<?=e($_GET['q']??'')?>" style="min-width:180px;border-radius:20px 0 0 20px">
          <button class="btn btn-outline-teal btn-sm" type="submit"
                  style="border-radius:0 20px 20px 0;border-left:none;padding:.3rem .8rem">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </form>

      <?php if($u): ?>
      <div class="dropdown">
        <button class="btn btn-nav-login dropdown-toggle d-flex align-items-center gap-2"
                data-bs-toggle="dropdown">
          <?php if(!empty($u['avatar'])): ?>
            <img src="<?=media_url('Originales/imagen/Avatares/'.$u['avatar'])?>"
                 style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid rgba(166,189,255,.3)">
          <?php else: ?>
            <i class="fa fa-user-circle" style="font-size:1.1rem;color:var(--lavender)"></i>
          <?php endif; ?>
          <span style="font-size:.85rem"><?= e($u['nombre']) ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <?php if($u['rol']==='admin'): ?>
            <li><a class="dropdown-item" href="<?=url('admin/dashboard')?>">
              <i class="fa fa-cog me-2" style="color:var(--teal)"></i>Panel Admin</a></li>
          <?php elseif($u['rol']==='artista'): ?>
            <li><a class="dropdown-item" href="<?=url('artista/dashboard')?>">
              <i class="fa fa-palette me-2" style="color:var(--teal)"></i>Mi Studio</a></li>
          <?php elseif($u['rol']==='curador'): ?>
            <li><a class="dropdown-item" href="<?=url('curador/dashboard')?>">
              <i class="fa fa-eye me-2" style="color:var(--teal)"></i>Panel Curador</a></li>
          <?php endif; ?>
          <li><a class="dropdown-item" href="<?=url('perfil')?>">
            <i class="fa fa-user me-2" style="color:var(--lavender)"></i>Mi Perfil</a></li>
          <li><a class="dropdown-item" href="<?=url('notificaciones')?>">
            <i class="fa fa-bell me-2" style="color:var(--lavender)"></i>Notificaciones</a></li>
          <li><a class="dropdown-item" href="<?=url('chat')?>">
            <i class="fa fa-comments me-2" style="color:var(--lavender)"></i>Chat</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="<?=url('logout')?>">
            <i class="fa fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
        </ul>
      </div>
      <?php else: ?>
        <a href="<?=url('login')?>" class="btn btn-nav-login me-2">Ingresar</a>
        <a href="<?=url('registro')?>" class="btn-nav-register btn">Registrarse</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
