<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Error 500 – Artcania</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{background:#0d1117;color:#f0f0f0;font-family:'Segoe UI',monospace;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:2rem}
    .box{background:#1a1f2e;border:1px solid #2d3748;border-radius:16px;padding:2.5rem;max-width:640px;width:100%}
    h1{color:#f87171;font-size:3rem;margin-bottom:.5rem}
    h2{color:#fbbf24;font-size:1.1rem;margin-bottom:1.5rem;font-weight:400}
    .msg{background:#0d1117;border:1px solid #374151;border-radius:8px;padding:1rem;font-size:.85rem;color:#d1d5db;word-break:break-word;margin-bottom:1.5rem}
    .hint{font-size:.8rem;color:#6b7280;margin-bottom:1.5rem;line-height:1.6}
    code{color:#fbbf24;background:#111827;padding:1px 5px;border-radius:4px}
    .btns{display:flex;gap:.75rem;flex-wrap:wrap}
    a{background:#4f46e5;color:#fff;padding:.5rem 1.2rem;border-radius:8px;text-decoration:none;font-size:.85rem}
    a.sec{background:transparent;border:1px solid #374151;color:#9ca3af}
  </style>
</head>
<body>
<div class="box">
  <h1>500</h1>
  <h2>Error interno del servidor</h2>
  <?php if(!empty($error_message)): ?>
  <div class="msg"><?= htmlspecialchars($error_message) ?></div>
  <?php endif; ?>
  <p class="hint">
    Revisa los logs en <code>artcania/logs/app.log</code> o en <code>C:\xampp\apache\logs\error.log</code>.<br>
    También puedes visitar <code><a href="<?= isset($cfg) ? rtrim($cfg['url'],'/') : '' ?>/debug.php" style="background:none;color:#7b9ef9;padding:0">debug.php</a></code> para diagnóstico.
  </p>
  <div class="btns">
    <a href="javascript:history.back()">← Volver</a>
    <a href="<?= isset($cfg) ? $cfg['url'] : '/' ?>" class="sec">Inicio</a>
  </div>
</div>
</body>
</html>
