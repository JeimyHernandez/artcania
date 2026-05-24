<?php $pageTitle = 'Perfil de Artista';
$artist = $artist ?? ['nombre'=>'Lunareth','bio'=>'Transformo la imaginación en universos. Me inspira lo etéreo, lo mágico y lo desconocido. Cada obra es un portal a lo imposible.','tags'=>'Artista digital • Creadora de mundos • Soñadora cósmica','obras'=>128,'seguidores'=>'24.7K','valoracion'=>'4.9','resenas'=>312,'extra'=>'+230 esta semana'];
?>
<div class="container py-4">
  <div class="profile-hero">
    <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a0a40 0%,#7a3df0 50%,#c77dff 100%)"></div>
    <img class="profile-avatar-lg" src="<?= !empty($artist['avatar'])?avatar($artist['avatar']):'https://api.dicebear.com/7.x/lorelei/svg?seed='.urlencode($artist['nombre']).'&backgroundColor=2a1458' ?>" alt="">
  </div>

  <div class="row g-4 align-items-center mb-4" style="padding-left:220px">
    <div class="col-lg-6">
      <h1 style="font-family:'Cinzel',serif;color:var(--moon);font-size:2.5rem;letter-spacing:.05em;margin:0">
        <?= e($artist['nombre']) ?> <span style="color:var(--magenta);font-size:1.2rem">✦</span>
      </h1>
      <p class="mb-2" style="color:var(--magenta);font-family:'Cormorant Garamond',serif;font-style:italic"><?= e($artist['tags']) ?></p>
      <p style="color:rgba(212,184,255,.85);max-width:520px;font-family:'Cormorant Garamond',serif;font-size:1.05rem"><?= e($artist['bio']) ?></p>
      <div class="d-flex gap-3 flex-wrap mt-3" style="color:var(--lavender)">
        <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
        <a href="#"><i class="fab fa-twitter"></i> X (Twitter)</a>
        <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
        <a href="#"><i class="fa fa-palette"></i> ArtStation</a>
        <a href="#"><i class="fa fa-globe"></i> lunareth.art</a>
      </div>
    </div>
    <div class="col-lg-3 d-flex gap-2 justify-content-lg-end">
      <button class="btn-magic btn-magic-solid"><i class="fa fa-user-plus"></i> Seguir</button>
      <button class="btn-magic"><i class="fa fa-envelope"></i> Contactar</button>
    </div>
    <div class="col-lg-3">
      <div class="d-flex gap-3 justify-content-around">
        <div class="stat-block"><div style="color:var(--magenta)"><i class="fa fa-palette"></i> Obras</div><div class="num"><?= e($artist['obras']) ?></div><small style="color:var(--magenta)">Ver todas</small></div>
        <div class="stat-block"><div style="color:var(--magenta)"><i class="fa fa-users"></i> Seguidores</div><div class="num"><?= e($artist['seguidores']) ?></div><small style="color:rgba(212,184,255,.6)"><?= e($artist['extra']) ?></small></div>
        <div class="stat-block"><div style="color:var(--magenta)"><i class="fa fa-star"></i> Valoración</div><div class="num"><?= e($artist['valoracion']) ?></div><small style="color:var(--gold)">★★★★★ (<?= e($artist['resenas']) ?>)</small></div>
      </div>
    </div>
  </div>

  <div class="tabs-magic">
    <a href="#" class="active"><i class="fa fa-image me-2"></i>Obras</a>
    <a href="#"><i class="fa fa-users me-2"></i>Colaboraciones</a>
    <a href="#"><i class="fa fa-trophy me-2"></i>Premios</a>
    <a href="#"><i class="fa fa-info-circle me-2"></i>Sobre</a>
  </div>

  <div class="gallery-grid">
    <?php for($i=0;$i<10;$i++): ?>
    <div class="item" style="background-image:linear-gradient(<?= ($i*40)%360 ?>deg,#1a0a40,#7a3df0,#c77dff);<?= $i%4==1?'aspect-ratio:1/1.4':'' ?>"></div>
    <?php endfor; ?>
  </div>
</div>
