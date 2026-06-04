<?php $pageTitle = 'Gestion Chat'; ?>
<div class="admin-page-header mb-4">
  <h4 class="font-cinzel mb-0" style="color:var(--gold-light)"><i class="fa fa-gear me-2"></i><?php echo $pageTitle ?></h4>
</div>

<div class="card-magic p-4">
  <p style="color:var(--pearl-muted);font-size:.875rem">
    <i class="fa fa-circle-info me-2" style="color:var(--teal)"></i>
    Esta sección carga datos del controlador. Los datos están disponibles en la variable <code>$<?php echo 'gestion_chat' ?></code>.
  </p>
  <pre style="background:var(--bg);color:var(--pearl-muted);padding:1rem;border-radius:8px;font-size:.8rem;overflow:auto"><?php print_r($<?= 'gestion_chat' ?> ?? []); ?></pre>
</div>
