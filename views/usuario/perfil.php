<?php $pageTitle = 'Mi Perfil';
$u = $user ?? Auth::user();
$favoritos = $favoritos ?? [];
?>
<div class="container-fluid py-3">
  <div class="profile-hero" style="height:240px;border-radius:24px">
    <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a0a40 0%,#3a1880 40%,#7a3df0 70%,#c77dff 100%)"></div>
    <img class="profile-avatar-lg" src="<?= !empty($u['avatar'])?avatar($u['avatar']):'https://api.dicebear.com/7.x/lorelei/svg?seed='.urlencode($u['nombre']??'user').'&backgroundColor=2a1458' ?>" alt="">
  </div>

  <div class="row g-4 align-items-end mb-4" style="padding-left:220px">
    <div class="col-lg-6">
      <h1 style="font-family:'Cinzel',serif;color:var(--moon);font-size:3rem;letter-spacing:.1em;margin:0">
        <?= strtoupper(e($u['nombre']??'USUARIO')) ?>
        <?php if(($u['rol']??'')=='artista'): ?><span class="badge ms-2" style="background:linear-gradient(135deg,#5eead4,#14b8a6);color:#06030f;font-size:.7rem">✦ ARTISTA</span><?php endif; ?>
      </h1>
      <p style="color:var(--magenta);font-family:'Cormorant Garamond',serif;font-size:1.1rem">@<?= e(strtolower($u['nombre']??'usuario')) ?>.art</p>
      <p style="color:rgba(212,184,255,.85);font-family:'Cormorant Garamond',serif;font-size:1.1rem;max-width:520px"><?= e($u['biografia'] ?? 'Tejedora de sueños y fragmentos de luz. Exploro lo etéreo, lo ancestral y lo mágico a través del arte.') ?></p>
      <div class="d-flex gap-3" style="color:rgba(212,184,255,.7)"><span><i class="fa fa-map-marker-alt"></i> Arcadia Astral</span><span><i class="fa fa-calendar"></i> Se unió en Mayo 2023</span></div>
    </div>
    <div class="col-lg-6">
      <div class="glass-panel d-flex justify-content-around" style="border:1px solid rgba(255,213,138,.25)">
        <div class="stat-block"><i class="fa fa-heart fa-lg" style="color:var(--magenta)"></i><div class="num"><?= e($u['favoritos']??412) ?></div><div class="lbl">FAVORITOS</div></div>
        <div class="stat-block"><i class="fa fa-star fa-lg" style="color:var(--magenta)"></i><div class="num"><?= e($u['valoraciones']??256) ?></div><div class="lbl">VALORACIONES</div></div>
        <div class="stat-block"><i class="fa fa-user-plus fa-lg" style="color:var(--magenta)"></i><div class="num"><?= e($u['seguidos']??389) ?></div><div class="lbl">SEGUIDOS</div></div>
      </div>
    </div>
  </div>

  <div class="tabs-magic">
    <a href="<?= url('mis-favoritos') ?>" class="active">Mis Favoritos</a>
    <a href="<?= url('mis-valoraciones') ?>">Valoraciones</a>
    <a href="<?= url('notificaciones') ?>">Notificaciones <span class="badge rounded-pill ms-1" style="background:var(--magenta)">3</span></a>
    <a href="<?= url('mis-conversaciones') ?>">Conversaciones <span class="badge rounded-pill ms-1" style="background:var(--magenta)">2</span></a>
  </div>

  <div class="row g-4">
    <?php if(empty($favoritos)): $favoritos=[
      ['titulo'=>'Ciudad Entre Nubes','autor'=>'Aeloria','stars'=>98,'comments'=>12],
      ['titulo'=>'Guardián del Bosque Luminal','autor'=>'Erathion','stars'=>124,'comments'=>8],
      ['titulo'=>'La Sacerdotisa de la Luna','autor'=>'Nyxara','stars'=>76,'comments'=>6],
      ['titulo'=>'Pacto de Cenizas','autor'=>'Drakhelm','stars'=>159,'comments'=>15],
    ]; endif; foreach($favoritos as $i=>$f): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="fanart-card">
        <div class="img" style="background-image:linear-gradient(<?= ($i*65)%360 ?>deg,#1a0a40,#7a3df0,#c77dff);position:relative">
          <span class="star" style="background:rgba(199,125,255,.85);width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff"><i class="fa fa-heart"></i></span>
        </div>
        <div class="body">
          <h6><?= e($f['titulo']) ?></h6>
          <div class="d-flex justify-content-between mt-1">
            <small style="color:var(--lavender)">por <?= e($f['autor']) ?></small>
            <small style="color:rgba(212,184,255,.7)"><i class="fa fa-star" style="color:var(--magenta)"></i> <?= e($f['stars']) ?> &nbsp;<i class="fa fa-comment"></i> <?= e($f['comments']) ?></small>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
