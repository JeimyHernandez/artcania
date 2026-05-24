<?php $pageTitle = 'Chat'; ?>
<div class="row g-0 shadow rounded overflow-hidden" style="height:70vh">
  <div class="col-md-4 border-end bg-light">
    <div class="p-3 border-bottom bg-white fw-bold"><i class="fa fa-comments me-2"></i>Conversaciones</div>
    <div id="convList" style="overflow-y:auto;height:calc(70vh - 56px)">
      <?php foreach($convs as $c): ?>
      <div class="d-flex align-items-center gap-2 p-3 border-bottom conv-item" data-id="<?= $c['id'] ?>" style="cursor:pointer">
        <img src="<?= avatar($c['otro_avatar']??'') ?>" class="rounded-circle" width="40" height="40" style="object-fit:cover">
        <div class="flex-grow-1">
          <strong><?= e($c['otro_nombre']) ?></strong>
          <?php if($c['no_leidos']>0): ?><span class="badge bg-danger ms-1"><?= $c['no_leidos'] ?></span><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(empty($convs)): ?><div class="p-4 text-center text-muted"><i class="fa fa-comments fa-2x mb-2 d-block"></i>No tienes conversaciones</div><?php endif; ?>
    </div>
  </div>
  <div class="col-md-8 d-flex flex-column">
    <div class="p-3 bg-white border-bottom fw-bold" id="chatHeader">Selecciona una conversación</div>
    <div id="chatMessages" class="flex-grow-1 p-3" style="overflow-y:auto;background:#f8f9fa"></div>
    <div class="p-3 border-top bg-white">
      <div class="input-group">
        <input type="text" id="msgInput" class="form-control" placeholder="Escribe un mensaje..." disabled>
        <button class="btn btn-primary" id="sendBtn" disabled onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div>
<script>
let currentConv = null;
const csrf = '<?= csrf_token() ?>';
document.querySelectorAll('.conv-item').forEach(el=>{
  el.addEventListener('click',()=>{ currentConv=el.dataset.id; loadMessages(); setInterval(loadMessages,3000); document.getElementById('msgInput').disabled=false; document.getElementById('sendBtn').disabled=false; el.style.background='#e8d5f5'; });
});
function loadMessages(){
  if(!currentConv)return;
  fetch('<?= url('chat/mensajes/') ?>'+currentConv,{headers:{'X-Requested-With':'XMLHttpRequest'}})
  .then(r=>r.json()).then(msgs=>{
    const box=document.getElementById('chatMessages');
    box.innerHTML=msgs.map(m=>`<div class="mb-2 d-flex ${m.remitente_id==<?= Auth::id() ?>?'justify-content-end':'justify-content-start'}"><div class="rounded-3 px-3 py-2 ${m.remitente_id==<?= Auth::id() ?>?'bg-primary text-white':'bg-white border'}" style="max-width:70%"><small class="d-block fw-bold mb-1">${m.remitente_nombre}</small>${m.mensaje}<small class="d-block mt-1 opacity-75" style="font-size:.7em">${m.creado_en}</small></div></div>`).join('');
    box.scrollTop=box.scrollHeight;
  });
}
function sendMessage(){
  const inp=document.getElementById('msgInput');
  const msg=inp.value.trim();
  if(!msg||!currentConv)return;
  fetch('<?= url('chat/enviar') ?>',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},body:`_csrf=${csrf}&conv_id=${currentConv}&mensaje=${encodeURIComponent(msg)}`})
  .then(r=>r.json()).then(d=>{ if(d.ok){inp.value='';loadMessages();} });
}
document.getElementById('msgInput').addEventListener('keypress',e=>{ if(e.key==='Enter')sendMessage(); });
</script>
