<?php
/** @var int $convId */
/** @var array $msgs */
?>
<div class="chat-window d-flex flex-column" style="height:400px;border:1px solid #dee2e6;border-radius:8px;overflow:hidden">
  <div class="chat-header bg-light border-bottom p-2 fw-bold small">
    <i class="fa fa-comments me-1 text-purple"></i>Chat
  </div>
  <div class="chat-body flex-grow-1 p-3" style="overflow-y:auto;background:#f9f6ff" id="chatBody_<?= $convId ?>">
    <?php foreach($msgs as $m): ?>
    <div class="mb-2 d-flex <?= $m['remitente_id']==Auth::id()?'justify-content-end':'justify-content-start' ?>">
      <div class="px-3 py-2 rounded-3 <?= $m['remitente_id']==Auth::id()?'bg-primary text-white':'bg-white border' ?>" style="max-width:70%;font-size:.88rem">
        <?= nl2br(e($m['mensaje'])) ?>
        <small class="d-block mt-1 opacity-75" style="font-size:.68em"><?= format_date($m['creado_en'],'H:i') ?></small>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="chat-footer border-top p-2">
    <div class="input-group input-group-sm">
      <input type="text" id="cwInput_<?= $convId ?>" class="form-control" placeholder="Mensaje...">
      <button class="btn btn-primary" onclick="cwSend(<?= $convId ?>)"><i class="fa fa-paper-plane"></i></button>
    </div>
  </div>
</div>
<script>
function cwSend(cid){
  const inp = document.getElementById('cwInput_'+cid);
  const msg = inp.value.trim(); if(!msg) return;
  fetch('<?= url('chat/enviar') ?>',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
    body:`_csrf=<?=csrf_token()?>&conv_id=${cid}&mensaje=${encodeURIComponent(msg)}`})
  .then(r=>r.json()).then(d=>{ if(d.ok){ inp.value=''; } });
}
</script>

