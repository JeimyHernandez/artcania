<?php
$user          = Auth::user();
$flash_success = isset($flash_success) ? $flash_success : (Session::getFlash('success') ?: '');
$flash_error   = isset($flash_error)   ? $flash_error   : (Session::getFlash('error') ?: '');
$csrf_token    = $_SESSION['csrf_token'] ?? '';
?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($pageTitle) ? e($pageTitle) . ' – ' : '' ?>Artcania</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?= asset('css/main.css') ?>">
</head>
<body class="artcania-body">
<?php require BASE_PATH . '/views/partials/navbar.php'; ?>

<?php if ($flash_success): ?>
<div class="container mt-3">
  <div class="alert alert-success alert-dismissible fade show">
    <i class="fa fa-check-circle me-2"></i><?= e($flash_success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>
<?php if ($flash_error): ?>
<div class="container mt-3">
  <div class="alert alert-danger alert-dismissible fade show">
    <i class="fa fa-exclamation-circle me-2"></i><?= e($flash_error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>

<main><?= $content ?></main>
<?php require BASE_PATH . '/views/partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>var BASE_URL = <?= json_encode(rtrim(artcania_config()['url'], '/')) ?>;</script>
<script src="<?= asset('js/main.js') ?>"></script>
<script src="<?= asset('js/cosmic.js') ?>"></script>
</body>
</html>
