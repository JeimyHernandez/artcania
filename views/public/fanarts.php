<?php $pageTitle = 'Galería de Fanarts'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-end flex-wrap mb-4 gap-3">
    <div>
      <h1 class="cosmic-title" style="font-size:2.4rem;text-align:left">Galería de Fanarts ✦</h1>
      <p class="cosmic-subtitle" style="margin-top:.3rem">Explora cientos de fanarts inspirados en tus obras favoritas. ✦</p>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="position-relative">
        <i class="fa fa-search position-absolute" style="top:14px;left:16px;color:var(--lavender)"></i>
        <input type="text" class="input-magic" placeholder="Buscar fanarts, personajes, artistas..." style="padding-left:42px">
      </div>
    </div>
    <div class="col-md-3">
      <label class="small" style="color:rgba(212,184,255,.6)">Filtrar por obra original</label>
      <select class="input-magic"><option>Todas las obras</option></select>
    </div>
    <div class="col-md-3">
      <label class="small" style="color:rgba(212,184,255,.6)">Ordenar por</label>
      <select class="input-magic"><option>Más populares</option><option>Más recientes</option></select>
    </div>
  </div>

  <div class="row g-4">
    <?php $fanarts = $fanarts ?? [];
    if(empty($fanarts)): $fanarts = [
      ['titulo'=>'Demon Slayer (Kimetsu no Yaiba)','autor'=>'@Hikari_draws','likes'=>'2.4K'],
      ['titulo'=>'Sailor Moon','autor'=>'@Usagi_Art','likes'=>'1.8K'],
      ['titulo'=>'The Legend of Zelda: Breath of the Wild','autor'=>'@ZeldaPals','likes'=>'2.1K'],
      ['titulo'=>'Spirited Away (El Viaje de Chihiro)','autor'=>'@Mochi_artt','likes'=>'1.6K'],
      ['titulo'=>'Tokyo Ghoul','autor'=>'@Dark_illus','likes'=>'1.2K'],
      ['titulo'=>'One Piece','autor'=>'@Eiichiro_fan','likes'=>'2.7K'],
      ['titulo'=>'Fullmetal Alchemist: Brotherhood','autor'=>'@Alchemy_Art','likes'=>'1.5K'],
      ['titulo'=>'Kimi no Na wa. (Your Name.)','autor'=>'@kimi_art','likes'=>'1.9K'],
    ]; endif; ?>
    <?php foreach($fanarts as $i=>$f): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="fanart-card">
        <div class="img" style="background-image:linear-gradient(<?= ($i*53)%360 ?>deg,#1a0a40,#7a3df0,#c77dff)"></div>
        <div class="star">✦</div>
        <div class="body">
          <small>Fanart de</small>
          <h6><?= e($f['titulo']??'Fanart') ?></h6>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <small style="color:var(--lavender)">por <?= e($f['autor']??'@artist') ?></small>
            <small style="color:var(--magenta)"><i class="fa fa-heart"></i> <?= e($f['likes']??'0') ?></small>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <nav class="d-flex justify-content-center mt-5">
    <ul class="pagination">
      <li class="page-item"><a class="page-link pill" href="#"><i class="fa fa-chevron-left"></i></a></li>
      <li><a class="pill active mx-1" href="#">1</a></li>
      <li><a class="pill mx-1" href="#">2</a></li>
      <li><a class="pill mx-1" href="#">3</a></li>
      <li><a class="pill mx-1" href="#">4</a></li>
      <li><a class="pill mx-1" href="#">5</a></li>
      <li><span class="mx-2" style="color:var(--lavender)">…</span></li>
      <li><a class="pill mx-1" href="#">28</a></li>
      <li><a class="pill ms-1" href="#"><i class="fa fa-chevron-right"></i></a></li>
    </ul>
  </nav>
</div>
