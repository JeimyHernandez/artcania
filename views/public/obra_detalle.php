
<?php $pageTitle = e($obra['titulo'] ?? 'Obra'); ?>
<div class="container-xl py-4">
  <div class="row g-4">

    <!-- Imagen principal -->
    <div class="col-lg-7">
      <div style="border-radius:20px;overflow:hidden;border:1px solid var(--border);position:relative">
        <?php if(!empty($obra['imagen_principal'])): ?>
          <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$obra['imagen_principal']) ?>"
               class="w-100" alt="<?= e($obra['titulo']) ?>"
               style="max-height:70vh;object-fit:contain;background:var(--deep)"
               onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
        <?php else: ?>
          <div style="height:400px;display:flex;align-items:center;justify-content:center;font-size:4rem;background:var(--deep)">🎨</div>
        <?php endif; ?>

        <!-- Acciones flotantes -->
        <div class="d-flex align-items-center gap-3 px-4 py-3"
             style="background:linear-gradient(to top,rgba(10,6,18,.9),transparent 0);position:absolute;bottom:0;left:0;right:0">
          <?php if(Auth::check()): ?>
          <button class="fav-btn d-flex align-items-center gap-1 btn-sm"
                  data-id="<?= $obra['id'] ?>"
                  style="background:none;border:none;color:var(--pearl-dim);font-size:.85rem;cursor:pointer">
            <i class="fa fa-heart me-1" style="font-size:1rem"></i>
            <span id="favCount"><?= $obra['favoritos'] ?? 0 ?></span>
          </button>
          <?php endif; ?>
          <span class="d-flex align-items-center gap-1" style="font-size:.83rem;color:var(--pearl-muted)">
            <i class="fa fa-eye" style="color:var(--teal)"></i>
            <?= number_format($obra['visualizaciones'] ?? 0) ?>
          </span>
          <button onclick="navigator.clipboard.writeText(window.location.href)"
                  class="ms-auto d-flex align-items-center gap-1"
                  style="background:rgba(255,255,255,.08);border:1px solid var(--border);color:var(--pearl-dim);border-radius:8px;padding:.3rem .8rem;font-size:.78rem;cursor:pointer">
            <i class="fa fa-link me-1"></i>Compartir
          </button>
        </div>
      </div>

      <!-- Comentarios -->
      <div class="card-magic p-4 mt-3">
        <h6 class="font-cinzel mb-3" style="color:var(--gold-light)">
          ✦ Comentarios (<?= count($comentarios ?? []) ?>)
        </h6>

        <?php if(Auth::check()): ?>
        <form method="POST" action="<?= url('comentario') ?>" class="mb-4">
          <?= csrf_field() ?>
          <input type="hidden" name="obra_id" value="<?= $obra['id'] ?>">
          <div class="d-flex gap-2">
            <img src="<?= avatar(Auth::user()['avatar'] ?? '') ?>"
                 class="rounded-circle flex-shrink-0"
                 style="width:36px;height:36px;border:2px solid var(--border);object-fit:cover" alt="">
            <div class="flex-grow-1">
              <textarea name="texto" class="form-control" rows="2"
                        placeholder="Escribe un comentario..." required
                        style="resize:none;font-size:.85rem"></textarea>
              <button type="submit" class="btn btn-magic btn-sm mt-2 px-3">
                <i class="fa fa-paper-plane me-1"></i>Publicar
              </button>
            </div>
          </div>
        </form>
        <?php endif; ?>

        <?php if(!empty($comentarios)): ?>
          <?php foreach($comentarios as $c): ?>
          <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid var(--border)">
            <img src="<?= avatar($c['avatar'] ?? '') ?>"
                 class="rounded-circle flex-shrink-0"
                 style="width:38px;height:38px;object-fit:cover;border:2px solid var(--border)" alt="">
            <div>
              <div class="d-flex align-items-center gap-2 mb-1">
                <span style="font-size:.85rem;font-weight:600;color:var(--pearl)"><?= e($c['nombre']) ?></span>
                <span style="font-size:.72rem;color:var(--pearl-muted)"><?= format_date($c['creado_en']) ?></span>
              </div>
              <p style="font-size:.85rem;color:var(--pearl-dim);margin:0;line-height:1.6">
                <?= nl2br(e($c['contenido'])) ?>
              </p>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:var(--pearl-muted);font-size:.85rem">Sé el primero en comentar.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Info lateral -->
    <div class="col-lg-5">
      <!-- Artista -->
      <div class="card-magic p-3 mb-3 d-flex align-items-center gap-3">
        <img src="<?= avatar($artista['avatar'] ?? '') ?>"
             class="rounded-circle" style="width:52px;height:52px;object-fit:cover;border:2px solid var(--gold)" alt="">
        <div>
          <div style="font-weight:600;color:var(--pearl)"><?= e($artista['nombre'] ?? '') ?></div>
          <div style="font-size:.78rem;color:var(--pearl-muted)"><?= e($artista['especialidad'] ?? 'Artista') ?></div>
          <?php if(!empty($artista['verificado'])): ?>
            <span class="badge-teal mt-1" style="font-size:.65rem"><i class="fa fa-circle-check me-1"></i>Verificado</span>
          <?php endif; ?>
        </div>
        <a href="<?= url('artistas/'.$artista['usuario_id']) ?>" class="btn btn-outline-magic btn-sm ms-auto">
          Ver perfil
        </a>
      </div>

      <!-- Detalles obra -->
      <div class="card-magic p-4 mb-3">
        <h2 style="font-size:1.4rem;margin-bottom:.5rem"><?= e($obra['titulo']) ?></h2>
        <div class="d-flex align-items-center gap-2 mb-3" style="font-size:.78rem;color:var(--pearl-muted)">
          <?php if(!empty($obra['ano'])): ?><span><?= $obra['ano'] ?></span><span>·</span><?php endif; ?>
          <?php if(!empty($obra['tecnica'])): ?><span><?= e($obra['tecnica']) ?></span><span>·</span><?php endif; ?>
          <?php if(!empty($obra['dimensiones'])): ?><span><?= e($obra['dimensiones']) ?></span><?php endif; ?>
        </div>

        <?php if(!empty($obra['descripcion'])): ?>
          <p style="font-size:.875rem;color:var(--pearl-dim);line-height:1.7;margin-bottom:1rem">
            <?= nl2br(e($obra['descripcion'])) ?>
          </p>
        <?php endif; ?>

        <!-- Tags -->
        <?php if(!empty($tags)): ?>
        <div class="d-flex flex-wrap gap-2 mb-3">
          <?php foreach($tags as $tag): ?>
            <span class="badge-magic"><?= e($tag['nombre']) ?></span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Valoración -->
        <?php if(!empty($obra['valoracion_promedio'])): ?>
        <div class="d-flex align-items-center gap-2 mb-3">
          <?php $v = round($obra['valoracion_promedio']); for($i=1;$i<=5;$i++): ?>
            <i class="fa fa-star" style="color:<?= $i<=$v ? 'var(--gold-light)' : 'rgba(201,162,39,.25)' ?>;font-size:1.1rem"></i>
          <?php endfor; ?>
          <span style="font-size:.83rem;color:var(--pearl-muted)"><?= number_format($obra['valoracion_promedio'],1) ?></span>
        </div>
        <?php endif; ?>

        <!-- Precio -->
        <?php if(!empty($obra['precio'])): ?>
        <div class="d-flex align-items-center justify-content-between p-3 mb-3"
             style="background:rgba(201,162,39,.08);border:1px solid var(--border-gold);border-radius:12px">
          <span style="font-size:.83rem;color:var(--pearl-muted)">Precio</span>
          <span class="badge-gold" style="font-size:.95rem">$<?= number_format($obra['precio'],2) ?></span>
        </div>
        <?php endif; ?>

        <!-- CTA contactar -->
        <?php if(Auth::check() && Auth::id() !== (int)($obra['artista_id'] ?? 0)): ?>
          <a href="<?= url('chat') ?>" class="btn btn-magic w-100">
            <i class="fa fa-paper-plane me-2"></i>Contactar artista
          </a>
        <?php elseif(!Auth::check()): ?>
          <a href="<?= url('login') ?>" class="btn btn-magic w-100">
            <i class="fa fa-paper-plane me-2"></i>Contactar artista
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Obras relacionadas -->
  <?php if(!empty($relacionadas)): ?>
  <div class="mt-5">
    <h5 class="font-cinzel mb-3">✦ Obras Relacionadas</h5>
    <div class="row g-3">
      <?php foreach(array_slice($relacionadas, 0, 4) as $r): ?>
      <div class="col-6 col-md-3">
        <a href="<?= url('galeria/'.$r['id']) ?>" class="card-magic d-block text-decoration-none">
          <?php if(!empty($r['imagen_principal'])): ?>
            <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$r['imagen_principal']) ?>"
                 class="card-img-top" style="height:140px;object-fit:cover"
                 alt="<?= e($r['titulo']) ?>"
                 onerror="this.src='<?= url('resources/img/placeholder.png') ?>'">
          <?php endif; ?>
          <div class="card-body">
            <div class="card-title" style="font-size:.82rem"><?= e(truncate($r['titulo'],30)) ?></div>
            <div style="font-size:.73rem;color:var(--pearl-muted)"><?= e($r['artista_nombre'] ?? '') ?></div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>


