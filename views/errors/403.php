<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>403 – Artcania</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <?php if(function_exists('asset')): ?><link rel="stylesheet" href="<?= asset('css/main.css') ?>"><?php endif; ?>
  <style>body{min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem}</style>
</head>
<body>
  <div>
    <div style="font-size:4rem;margin-bottom:.5rem">🔮</div>
    <h1 style="font-family:'Cinzel',serif;font-size:3rem;background:linear-gradient(135deg,#c9a227,#9d5cf5);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">403</h1>
    <h2 style="font-family:'Cinzel',serif;font-size:1.2rem;margin:.8rem 0 .5rem">Acceso Prohibido</h2>
    <p style="color:rgba(240,234,255,.5);max-width:340px;margin:0 auto 1.5rem;font-size:.88rem">No tienes permiso para entrar a este reino.</p>
    <a href="<?= function_exists('url') ? url('') : '/' ?>"
       style="display:inline-flex;align-items:center;gap:.6rem;background:linear-gradient(135deg,#7c3aed,#9d5cf5);color:white;font-weight:600;padding:.65rem 1.8rem;border-radius:20px;text-decoration:none;font-size:.88rem">
      ✦ Volver al inicio
    </a>
  </div>
</body>
</html>
