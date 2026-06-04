<?php $pageTitle = 'Galería'; ?>
<div class="container-xl py-4">
  <div class="row g-4">

    <!-- Sidebar filtros -->
    <div class="col-lg-3">
      <div class="card-magic p-3 mb-3" style="position:sticky;top:80px">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h6 class="font-cinzel mb-0" style="color:var(--gold-light)">✦ Filtrar Obras</h6>
          <?php if(!empty($q) || !empty($categoria) || !empty($pais) || $precio_min!=='' || $precio_max!==''): ?>
            <a href="<?= url('galeria') ?>" class="btn btn-sm" style="color:var(--pearl-muted);font-size:.75rem">
              Limpiar <i class="fa fa-xmark ms-1"></i>
            </a>
          <?php endif; ?>
        </div>

        <form method="GET" action="<?= url('galeria') ?>">
          <!-- Búsqueda -->
          <div class="mb-3">
            <div class="input-group input-group-sm">
              <input type="text" name="q" class="form-control"
                     placeholder="Buscar obras..."
                     value="<?= e($q ?? '') ?>">
              <button type="submit" class="input-group-text" style="cursor:pointer">
                <i class="fa fa-magnifying-glass" style="color:var(--purple-mid)"></i>
              </button>
            </div>
          </div>

          <!-- mantener filtros activos -->
          <?php if(!empty($pais)): ?><input type="hidden" name="pais" value="<?= e($pais) ?>"><?php endif; ?>
          <?php if($precio_min!==''): ?><input type="hidden" name="precio_min" value="<?= e($precio_min) ?>"><?php endif; ?>
          <?php if($precio_max!==''): ?><input type="hidden" name="precio_max" value="<?= e($precio_max) ?>"><?php endif; ?>
          <?php if(!empty($orden)): ?><input type="hidden" name="orden" value="<?= e($orden) ?>"><?php endif; ?>

          <!-- Categorías -->
          <?php if(!empty($categorias)): ?>
          <div class="mb-3">
            <div class="sidebar-section-label px-0 mb-2">Categorías</div>
            <?php
              // construir query base preservando otros filtros
              $base = [];
              if(!empty($q))         $base['q']=$q;
              if(!empty($pais))      $base['pais']=$pais;
              if($precio_min!=='')   $base['precio_min']=$precio_min;
              if($precio_max!=='')   $base['precio_max']=$precio_max;
              if(!empty($orden))     $base['orden']=$orden;
            ?>
            <?php foreach($categorias as $cat): ?>
            <?php $qs = http_build_query(array_merge($base, ['categoria'=>$cat['id']])); ?>
            <a href="<?= url('galeria?'.$qs) ?>"
               class="d-flex align-items-center justify-content-between py-1 px-2 mb-1 text-decoration-none"
               style="border-radius:8px;transition:all .2s;<?= ($categoria==$cat['id']) ? 'background:var(--purple-soft);color:var(--pearl)' : 'color:var(--pearl-dim)' ?>"
               onmouseover="this.style.background='var(--purple-soft)'"
               onmouseout="this.style.background='<?= ($categoria==$cat['id']) ? 'var(--purple-soft)' : 'transparent' ?>'">
              <span style="font-size:.83rem">
                <i class="fa fa-circle-dot me-2" style="font-size:.5rem;color:var(--purple-mid)"></i>
                <?= e($cat['nombre']) ?>
              </span>
              <span style="font-size:.72rem;color:var(--pearl-muted)"><?= $cat['total'] ?? '' ?></span>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- País -->
          <?php if(!empty($paises)): ?>
          <div class="mb-3">
            <div class="sidebar-section-label px-0 mb-2"><i class="fa fa-globe me-1" style="color:var(--gold-light)"></i> País</div>
            <select name="pais" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="">— Todos los países —</option>
              <?php foreach($paises as $p): ?>
                <option value="<?= e($p) ?>" <?= ($pais===$p)?'selected':'' ?>><?= e($p) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php endif; ?>

          <!-- Precio -->
          <div class="mb-3">
            <div class="sidebar-section-label px-0 mb-2"><i class="fa fa-tag me-1" style="color:var(--gold-light)"></i> Precio (USD)</div>
            <div class="d-flex gap-2">
              <input type="number" min="0" step="1" name="precio_min" class="form-control form-control-sm"
                     placeholder="Mín <?= isset($rango['min'])?(int)$rango['min']:0 ?>"
                     value="<?= e($precio_min) ?>">
              <input type="number" min="0" step="1" name="precio_max" class="form-control form-control-sm"
                     placeholder="Máx <?= isset($rango['max'])?(int)$rango['max']:0 ?>"
                     value="<?= e($precio_max) ?>">
            </div>
            <button type="submit" class="btn btn-sm w-100 mt-2"
                    style="background:linear-gradient(135deg,var(--purple-mid),var(--gold));color:#fff;font-size:.78rem;letter-spacing:.5px">
              Aplicar precio
            </button>
          </div>

          <!-- Ordenar -->
          <div class="mb-3">
            <div class="sidebar-section-label px-0 mb-2">Ordenar por</div>
            <select name="orden" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="recientes"   <?= ($orden==='recientes')   ? 'selected' : '' ?>>Más recientes</option>
              <option value="populares"   <?= ($orden==='populares')   ? 'selected' : '' ?>>Más vistos</option>
              <option value="valorados"   <?= ($orden==='valorados')   ? 'selected' : '' ?>>Mejor valorados</option>
              <option value="precio_asc"  <?= ($orden==='precio_asc')  ? 'selected' : '' ?>>Precio: menor a mayor</option>
              <option value="precio_desc" <?= ($orden==='precio_desc') ? 'selected' : '' ?>>Precio: mayor a menor</option>
            </select>
          </div>
        </form>
      </div>
    </div>

    <!-- Grid obras -->
    <div class="col-lg-9">
      <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <h1 class="fs-5 mb-0 font-cinzel">
          Galería de Obras
          <span style="font-size:.75rem;font-family:'Raleway',sans-serif;color:var(--pearl-muted);font-weight:400;margin-left:.5rem">
            ✦ <?= number_format($totalObras ?? count($obras ?? [])) ?> obras encontradas
          </span>
        </h1>

        <!-- chips de filtros activos -->
        <div class="d-flex gap-1 flex-wrap">
          <?php if(!empty($pais)): ?>
            <span class="badge" style="background:var(--purple-soft);color:var(--pearl)"><i class="fa fa-globe me-1"></i><?= e($pais) ?></span>
          <?php endif; ?>
          <?php if($precio_min!=='' || $precio_max!==''): ?>
            <span class="badge" style="background:var(--purple-soft);color:var(--pearl)">
              <i class="fa fa-tag me-1"></i>
              <?= $precio_min!==''?'$'.e($precio_min):'$0' ?> – <?= $precio_max!==''?'$'.e($precio_max):'∞' ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <?php if(!empty($obras)): ?>
        <div class="gallery-grid">
          <?php foreach($obras as $o): ?>
          <div class="gallery-item" onclick="location.href='<?= url('galeria/'.$o['id']) ?>'">
            <?php if(!empty($o['imagen_principal'])): ?>
              <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>"
                   alt="<?= e($o['titulo']) ?>" loading="lazy"
                   onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
            <?php else: ?>
              <div style="height:220px;background:var(--purple-soft);display:flex;align-items:center;justify-content:center;font-size:2.5rem">🎨</div>
            <?php endif; ?>

            <?php if(Auth::check()): ?>
            <button class="gallery-fav-btn fav-btn" data-id="<?= $o['id'] ?>"
                    onclick="event.stopPropagation()" title="Favorito">
              <i class="fa fa-heart"></i>
            </button>
            <?php endif; ?>

            <div class="gallery-item-overlay">
              <div class="obra-title"><?= e(truncate($o['titulo'], 35)) ?></div>
              <div class="obra-artist">✦ <?= e($o['artista_nombre'] ?? '') ?><?= !empty($o['artista_pais'])?' · '.e($o['artista_pais']):'' ?></div>
              <div class="d-flex align-items-center justify-content-between gap-2 mt-1" style="font-size:.7rem;color:var(--pearl-muted)">
                <span>
                  <i class="fa fa-eye me-1"></i><?= number_format($o['visualizaciones'] ?? 0) ?>
                  <?php if(!empty($o['valoracion_promedio'])): ?>
                    · <i class="fa fa-star ms-1 me-1" style="color:var(--gold)"></i><?= number_format($o['valoracion_promedio'],1) ?>
                  <?php endif; ?>
                </span>
                <?php if(!empty($o['precio'])): ?>
                  <span style="color:var(--gold-light);font-weight:600">$<?= number_format($o['precio'],0) ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Paginación -->
        <?php if(isset($totalPages) && $totalPages > 1): ?>
        <?php
          $qsBase = [];
          if(!empty($q))         $qsBase['q']=$q;
          if(!empty($categoria)) $qsBase['categoria']=$categoria;
          if(!empty($pais))      $qsBase['pais']=$pais;
          if($precio_min!=='')   $qsBase['precio_min']=$precio_min;
          if($precio_max!=='')   $qsBase['precio_max']=$precio_max;
          if(!empty($orden))     $qsBase['orden']=$orden;
        ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center gap-1">
            <?php for($i=1;$i<=$totalPages;$i++): ?>
            <?php $qsBase['page']=$i; ?>
            <li class="page-item <?= ($i==($currentPage??1))?'active':'' ?>">
              <a class="page-link" href="<?= url('galeria?'.http_build_query($qsBase)) ?>">
                <?= $i ?>
              </a>
            </li>
            <?php endfor; ?>
          </ul>
        </nav>
        <?php endif; ?>

      <?php else: ?>
      <div class="text-center py-5" style="color:var(--pearl-muted)">
        <i class="fa fa-image fa-4x mb-3" style="opacity:.2"></i>
        <h5 class="font-cinzel">No hay obras disponibles</h5>
        <p style="font-size:.85rem">Prueba con otros filtros de búsqueda</p>
        <a href="<?= url('galeria') ?>" class="btn btn-outline-magic btn-sm">Ver todas</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
