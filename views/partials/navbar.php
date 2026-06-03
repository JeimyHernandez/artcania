<?php $u = Auth::user(); ?>
<nav class="navbar navbar-expand-lg artcania-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= url('') ?>">
      ✦ Artcania
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
      <?php
        // Badge color por rol
        $rolBadge = [
          'admin'   => ['label'=>'Admin',   'bg'=>'rgba(248,113,113,.2)',  'color'=>'#f87171'],
          'artista' => ['label'=>'Artista', 'bg'=>'rgba(167,139,250,.2)',  'color'=>'#a78bfa'],
          'curador' => ['label'=>'Curador', 'bg'=>'rgba(251,191,36,.2)',   'color'=>'#fbbf24'],
          'usuario' => ['label'=>'Usuario', 'bg'=>'rgba(52,211,153,.2)',   'color'=>'#34d399'],
        ];
        $rb = $rolBadge[$u['rol']] ?? $rolBadge['usuario'];
      ?>
      <div class="dropdown">
        <button class="navbar-user-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <?php if(!empty($u['avatar'])): ?>
            <img src="<?=media_url('Originales/imagen/Avatares/'.$u['avatar'])?>"
                 class="user-avatar" alt="<?= e($u['nombre']) ?>">
          <?php else: ?>
            <div class="user-avatar-icon">
              <i class="fa fa-user"></i>
            </div>
          <?php endif; ?>
          <span><?= e($u['nombre']) ?></span>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">

          <!-- Cabecera del dropdown -->
          <li>
            <div style="padding:.6rem .85rem .5rem;border-bottom:1px solid rgba(124,58,237,.2);margin-bottom:.35rem">
              <div style="font-size:.82rem;color:var(--pearl);font-weight:600"><?= e($u['nombre']) ?></div>
              <div style="font-size:.74rem;color:var(--pearl-muted)"><?= e($u['email'] ?? '') ?></div>
              <span class="dropdown-role-badge mt-1 d-inline-block"
                    style="background:<?= $rb['bg'] ?>;color:<?= $rb['color'] ?>">
                <?= $rb['label'] ?>
              </span>
            </div>
          </li>

          <!-- Panel según rol -->
          <?php if($u['rol']==='admin'): ?>
            <li><a class="dropdown-item" href="<?=url('admin/dashboard')?>">
              <i class="fa fa-gauge me-2" style="color:var(--teal);width:16px"></i>
              Panel Admin
            </a></li>
          <?php elseif($u['rol']==='artista'): ?>
            <li><a class="dropdown-item" href="<?=url('artista/dashboard')?>">
              <i class="fa fa-palette me-2" style="color:var(--teal);width:16px"></i>
              Mi Studio
            </a></li>
          <?php elseif($u['rol']==='curador'): ?>
            <li><a class="dropdown-item" href="<?=url('curador/dashboard')?>">
              <i class="fa fa-eye me-2" style="color:var(--teal);width:16px"></i>
              Panel Curador
            </a></li>
          <?php endif; ?>

          <li><a class="dropdown-item" href="<?=url('perfil')?>">
            <i class="fa fa-user me-2" style="color:#a78bfa;width:16px"></i>
            Mi Perfil
          </a></li>
          <li><a class="dropdown-item" href="<?=url('notificaciones')?>">
            <i class="fa fa-bell me-2" style="color:#a78bfa;width:16px"></i>
            Notificaciones
          </a></li>
          <li><a class="dropdown-item" href="<?=url('mis-favoritos')?>">
            <i class="fa fa-heart me-2" style="color:#f87171;width:16px"></i>
            Mis Favoritos
          </a></li>
          <li><a class="dropdown-item" href="<?=url('chat')?>">
            <i class="fa fa-comments me-2" style="color:#34d399;width:16px"></i>
            Chat
          </a></li>

          <li><hr class="dropdown-divider"></li>

          <li><a class="dropdown-item text-danger" href="<?=url('logout')?>">
            <i class="fa fa-right-from-bracket me-2" style="width:16px"></i>
            Cerrar sesión
          </a></li>
        </ul>
      </div>

      <?php else: ?>
        <a href="<?=url('login')?>" class="btn btn-nav-login me-2">Ingresar</a>
        <a href="<?=url('registro')?>" class="btn-nav-register btn">Registrarse</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

