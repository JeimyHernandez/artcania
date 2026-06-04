<?php $pageTitle = 'Subastas'; if(!isset($subastas)) $subastas = []; ?>
<div class="admin-page-header">
  <h2><i class="fa fa-gavel me-2"></i>Gestión de Subastas</h2>
  <span class="badge-magic"><?= count($subastas) ?></span>
</div>

<div class="card-magic p-0 overflow-hidden">
  <div class="table-responsive">
    <table class="table table-magic mb-0">
      <thead><tr><th>Obra</th><th>Estado</th><th>P. Inicial</th><th>P. Actual</th><th>Pujas</th><th>Cierre</th></tr></thead>
      <tbody>
      <?php foreach($subastas as $s):
        $stmt = Database::getInstance()->prepare('SELECT COUNT(*) FROM pujas WHERE subasta_id=:id');
        $stmt->execute([':id'=>$s['id']]);
        $npujas = (int)$stmt->fetchColumn();
        $ec=['activa'=>'badge-teal','programada'=>'badge-warning','finalizada'=>'badge-magic','cancelada'=>'badge-danger'];
        $e=$ec[$s['estado']]??' badge-magic';
      ?>
      
        <tr>
          <td style="color:var(--pearl);font-size:.83rem"><?= e(truncate($s['titulo'] ?? '',30)) ?></td>
          <td><span class="<?= $e ?>" style="font-size:.7rem"><?= ucfirst($s['estado']) ?></span></td>
          <td style="font-size:.82rem">$<?= number_format($s['precio_inicial'] ?? 0,0) ?></td>
          <td style="font-size:.82rem;color:var(--gold-light)">$<?= number_format($s['precio_actual'] ?? 0,0) ?></td>
          <td style="font-size:.82rem"><?= $npujas ?></td>
          <td style="font-size:.75rem"><?= format_date($s['fecha_fin']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
