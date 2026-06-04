<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 – Artcania</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <?php $cfg = artcania_config(); ?>
  <link rel="stylesheet" href="<?= rtrim($cfg['url'],'/') ?>/resources/css/main.css">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; text-align:center; padding:2rem; }
  </style>
</head>
<body>
  <div style="position:relative;z-index:1;max-width:500px">
    <div style="font-size:8rem;font-family:'Cinzel',serif;font-weight:700;
                background:linear-gradient(135deg,var(--gold-light),var(--purple-mid),var(--gold-light));
                -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
                filter:drop-shadow(0 0 30px var(--purple-glow));line-height:1;margin-bottom:1rem">
      404
    </div>
    <div class="divider-magic justify-content-center mb-3">La obra se ha perdido en el cosmos</div>
    <p style="color:var(--pearl-muted);margin-bottom:2rem">
      A veces, incluso en el infinito, las obras encuentran su camino de vuelta.
    </p>
    <a href="<?= rtrim($cfg['url'],'/') ?>/" class="btn btn-teal px-4 py-2">
      <i class="fa fa-compass me-2"></i>Volver al inicio
    </a>
  </div>
</body>
</html>
