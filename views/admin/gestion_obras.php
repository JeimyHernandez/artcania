<?php $pageTitle = 'Obras'; if(!isset($obras)) $obras = []; ?>
<div class="admin-page-header">
  <h2><i class="fa fa-images me-2"></i>Gestión de Obras</h2>
  <span class="badge-magic"><?= count($obras) ?></span>
</div>

<div class="card-magic p-0 overflow-hidden">
  <div class="table-responsive">
    <table class="table table-magic mb-0">
      <thead><tr><th>Obra</th><th>Artista</th><th>Estado</th><th>Precio</th><th>Vistas</th><th>Fecha</th></tr></thead>
      <tbody>
      <?php foreach($obras as $o):
        $ec=['aprobada'=>'badge-teal','pendiente'=>'badge-warning','rechazada'=>'badge-danger'];
        $e=$ec[$o['estado']]??' badge-magic';
      ?>
      
        <tr>
          <td><div class="d-flex align-items-center gap-2">
            <?php if(!empty($o['imagen_principal'])): ?>
              <img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>" style="width:36px;height:36px;object-fit:cover;border-radius:6px;border:1px solid var(--border)" alt="" onerror="this.style.display='none'">
            <?php endif; ?>
            <span style="color:var(--pearl);font-size:.83rem"><?= e(truncate($o['titulo'],30)) ?></span>
          </div></td>
          <td style="font-size:.8rem"><?= e($o['artista_nombre'] ?? '') ?></td>
          <td><span class="<?= $e ?>" style="font-size:.7rem"><?= ucfirst($o['estado']) ?></span></td>
          <td style="font-size:.82rem"><?= !empty($o['precio']) ? '$'.number_format($o['precio'],0) : '—' ?></td>
          <td style="font-size:.82rem"><?= number_format($o['visualizaciones'] ?? 0) ?></td>
          <td style="font-size:.75rem"><?= format_date($o['creado_en']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
