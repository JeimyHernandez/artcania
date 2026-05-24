<?php $pageTitle = 'Artistas Destacados';
if (!isset($artistas) || !is_array($artistas)) { $artistas = []; }
?>
<div class="container py-4">
  <div class="cosmic-header">
    <div class="ornament">✦ ✦ ✦</div>
    <h1 class="cosmic-title">ARTISTAS DESTACADOS</h1>
    <p class="cosmic-subtitle">Descubre a los artistas que inspiran el cosmos.</p>
  </div>

  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div class="d-flex align-items-center gap-2">
      <label class="text-uppercase small" style="color:rgba(212,184,255,.6);letter-spacing:.1em">Categoría</label>
      <select class="input-magic" style="width:auto;min-width:200px">
        <option>Todas las categorías</option>
        <option>Ilustración Digital</option>
        <option>Arte Conceptual</option>
        <option>Pintura Fantasy</option>
        <option>Acuarela</option>
      </select>
    </div>
    <div class="d-flex align-items-center gap-2">
      <label class="text-uppercase small" style="color:rgba(212,184,255,.6);letter-spacing:.1em">Ordenar por</label>
      <select class="input-magic" style="width:auto;min-width:180px">
        <option>Más seguidores</option>
        <option>Más recientes</option>
        <option>Mejor valorados</option>
      </select>
    </div>
  </div>

  <div class="row g-4">
    <?php if(empty($artistas)):
      // demo placeholders that mirror the reference image when DB empty
      $artistas = [
        ['nombre'=>'Lunaris_Art','especialidad'=>'Ilustración Digital','followers'=>'125.8K'],
        ['nombre'=>'DarkNoir','especialidad'=>'Arte Conceptual','followers'=>'98.7K'],
        ['nombre'=>'Helia_Vesper','especialidad'=>'Pintura Fantasy','followers'=>'87.3K'],
        ['nombre'=>'Azurelle','especialidad'=>'Acuarela','followers'=>'76.4K'],
        ['nombre'=>'Mythka','especialidad'=>'Arte Tradicional','followers'=>'64.9K'],
        ['nombre'=>'Kairosh','especialidad'=>'Ilustración Digital','followers'=>'59.1K'],
        ['nombre'=>'Elyndra','especialidad'=>'Arte Fantasy','followers'=>'52.3K'],
        ['nombre'=>'Nebulae_9','especialidad'=>'Arte Sci-Fi','followers'=>'48.7K'],
        ['nombre'=>'Mirovsk','especialidad'=>'Retrato','followers'=>'43.2K'],
        ['nombre'=>'Orielle','especialidad'=>'Ilustración','followers'=>'41.6K'],
      ];
    endif; ?>
    <?php foreach($artistas as $i=>$a): ?>
    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
      <div class="magic-card text-center pt-4">
        <div class="artist-rank"><?= $i+1 ?></div>
        <div class="artist-avatar-frame">
          <img src="<?= !empty($a['avatar']) ? avatar($a['avatar']) : 'https://api.dicebear.com/7.x/lorelei/svg?seed='.urlencode($a['nombre']).'&backgroundColor=2a1458' ?>" alt="">
        </div>
        <div class="artist-name"><?= e($a['nombre']) ?></div>
        <div class="artist-spec"><?= e($a['especialidad'] ?? 'Arte') ?></div>
        <div class="artist-followers"><i class="fa fa-user me-1"></i><?= e($a['followers'] ?? '0') ?> seguidores</div>
        <a href="<?= url('buscar?q='.urlencode($a['nombre'])) ?>" class="btn-magic w-100">Seguir</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <p class="text-center mt-5" style="color:rgba(212,184,255,.6);font-family:'Cormorant Garamond',serif;font-style:italic">
    "El arte no reproduce lo visible, hace visible lo invisible." ✦
  </p>
</div>
