<?php $pageTitle = 'Sobre nosotros'; ?>
<div class="container-xl py-5">
  <div class="text-center mb-5">
    <div class="divider-magic justify-content-center mb-3">✦</div>
    <h1 class="font-cinzel" style="font-size:clamp(2rem,4vw,3rem)">Sobre Artcania</h1>
    <p class="hero-sub mx-auto mt-3">
      Un mundo donde el arte no tiene límites. Exploramos, conectamos e inspiramos a una comunidad
      apasionada por la creatividad y la imaginación.
    </p>
  </div>

  <div class="row g-4 justify-content-center mb-5">
    <?php
    $values = [
      ['icon'=>'✨','title'=>'Creatividad sin límites','desc'=>'Cada obra es un portal a un universo único. Aquí la imaginación es el único límite.'],
      ['icon'=>'🌟','title'=>'Comunidad mágica',     'desc'=>'Artistas, coleccionistas y amantes del arte unidos en un espacio sagrado de creación.'],
      ['icon'=>'🛡️','title'=>'Arte protegido',        'desc'=>'Cada creación tiene un hogar seguro. Protegemos la obra y el legado de cada artista.'],
    ];
    foreach($values as $v): ?>
    <div class="col-md-4">
      <div class="category-card text-center p-4">
        <div class="cat-icon" style="font-size:3rem"><?= $v['icon'] ?></div>
        <h5 class="font-cinzel mb-2" style="font-size:.95rem"><?= $v['title'] ?></h5>
        <p style="font-size:.83rem;color:var(--pearl-dim);line-height:1.6"><?= $v['desc'] ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="stats-banner text-center">
    <h2 class="font-cinzel mb-4" style="font-size:1.1rem;color:var(--gold-light)">✦ Artcania en números</h2>
    <div class="row g-3">
      <div class="col-6 col-md-3 stat-item">
        <h3>10K+</h3><small>Artistas</small>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <h3>50K+</h3><small>Obras</small>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <h3>200K+</h3><small>Usuarios</small>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <h3>15+</h3><small>Países</small>
      </div>
    </div>
  </div>
</div>
