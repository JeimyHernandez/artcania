<?php if($flash_success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fa fa-check-circle me-2"></i><?= e($flash_success) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if($flash_error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="fa fa-exclamation-circle me-2"></i><?= e($flash_error) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if(!empty($flash_errors)): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <ul class="mb-0">
    <?php foreach($flash_errors as $errs): foreach($errs as $err): ?>
    <li><?= e($err) ?></li>
    <?php endforeach; endforeach; ?>
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

