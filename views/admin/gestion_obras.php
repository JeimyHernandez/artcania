<?php $pageTitle = 'Gestión de Obras'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold"><i class="fa fa-images me-2"></i>Gestión de Obras</h2>
</div>
<?php
if(!isset($obras)) $obras = [];
?>
<div class="card shadow">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 tabla-dt">
        <thead class="table-dark"><tr>
          <th>#</th><th>Imagen</th><th>Título</th><th>Artista</th><th>Estado</th><th>Precio</th><th>Vistas</th><th>Fecha</th>
        </tr></thead>
        <tbody>
          <?php foreach($obras as $o): ?>
          <tr>
            <td><?= e($o['id']) ?></td>
            <td><?php if($o['imagen_principal']): ?><img src="<?= media_url('Originales/imagen/Obras_digitales/'.$o['imagen_principal']) ?>" width="48" height="48" style="object-fit:cover;border-radius:4px"><?php endif; ?></td>
            <td><?= e(truncate($o['titulo'],30)) ?></td>
            <td><?= e($o['artista_id']) ?></td>
            <td><span class="badge bg-<?= $o['estado']==='aprobada'?'success':($o['estado']==='pendiente'?'warning':'danger') ?>"><?= e($o['estado']) ?></span></td>
            <td><?= $o['precio'] ? money($o['precio']) : '-' ?></td>
            <td><?= number_format($o['visualizaciones']) ?></td>
            <td class="small"><?= format_date($o['creado_en'],'d/m/Y') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
