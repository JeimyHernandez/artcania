<?php $pageTitle = 'Artistas'; if(!isset($artistas)) $artistas = []; ?>
<div class="admin-page-header">
  <h2><i class="fa fa-palette me-2"></i>Gestión de Artistas</h2>
  <span class="badge-magic"><?= count($artistas) ?></span>
</div>
<div class="card-magic p-0 overflow-hidden">
  <div class="p-3 border-bottom" style="border-color:var(--border)!important">
    <input type="text" id="artSearch" class="form-control" placeholder="Buscar artista...">
  </div>
  <div class="table-responsive">
    <table class="table table-magic mb-0">
      <thead><tr><th>Artista</th><th>Especialidad</th><th>País</th><th>Verificado</th><th>Destacado</th></tr></thead>
      <tbody id="artTable">
      <?php foreach($artistas as $a): ?>
        <tr data-search="<?= strtolower(e($a['nombre'])) ?>">
          <td><div class="d-flex align-items-center gap-2">
            <img src="<?= avatar($a['avatar'] ?? '') ?>" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;border:1px solid var(--border)" alt="">
            <span style="color:var(--pearl);font-size:.875rem"><?= e($a['nombre']) ?></span>
          </div></td>
          <td style="font-size:.82rem"><?= e($a['especialidad'] ?? '-') ?></td>
          <td style="font-size:.82rem"><?= e($a['pais'] ?? '-') ?></td>
          <td><?php if(!empty($a['verificado'])): ?><i class="fa fa-circle-check" style="color:#4ade80"></i><?php else: ?><i class="fa fa-circle-xmark" style="color:#f87171"></i><?php endif; ?></td>
          <td><?php if(!empty($a['destacado'])): ?><i class="fa fa-star" style="color:var(--gold-light)"></i><?php else: ?><span style="color:var(--pearl-muted)">—</span><?php endif; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script>$('#artSearch').on('input',function(){var q=$(this).val().toLowerCase();$('#artTable tr').each(function(){$(this).toggle(!q||$(this).data('search').includes(q));});});</script>
