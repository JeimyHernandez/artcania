<?php $pageTitle = 'Subastas';
if (!isset($subastas)) { $subastas = []; }
$featured = $subastas[0] ?? ['titulo'=>'Celestial Dreamweaver','artista'=>'Lyria Moonshadow','precio_actual'=>2750,'desc'=>'Celestial Dreamweaver weaves the threads of stardust and fate, crafting realities in the loom of the cosmos.'];
?>
<div class="container py-4">
  <div class="row g-4">
    <div class="col-lg-7">
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="live-badge"><span class="dot"></span> LIVE AUCTION</span>
        <h2 style="font-family:'Cinzel',serif;color:var(--gold);margin:0;font-size:1.8rem;letter-spacing:.08em">✦ <?= strtoupper(e($featured['titulo'])) ?></h2>
      </div>
      <p style="color:var(--lavender);font-style:italic;font-family:'Cormorant Garamond',serif;margin-bottom:1rem">by <?= e($featured['artista']??'Artist') ?></p>
      <div class="glass-panel p-0" style="border:1px solid rgba(255,213,138,.3);border-radius:18px;overflow:hidden">
        <div style="aspect-ratio:4/5;background:linear-gradient(135deg,#1a0a40,#7a3df0,#c77dff)"></div>
      </div>
      <div class="mt-4">
        <h5 style="font-family:'Cinzel',serif;color:var(--gold);letter-spacing:.1em">✦ ABOUT THIS ARTWORK</h5>
        <p style="color:rgba(212,184,255,.85);font-family:'Cormorant Garamond',serif;font-size:1.05rem">
          <?= e($featured['desc'] ?? 'A vision of serenity, power, and infinite possibilities woven through stardust.') ?>
        </p>
        <div class="d-flex flex-wrap gap-2">
          <span class="pill">2024</span><span class="pill">DIGITAL ART</span><span class="pill">FANTASY</span><span class="pill">COSMIC</span><span class="pill">PURPLE COLLECTION</span>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="glass-panel mb-3">
        <small style="color:rgba(212,184,255,.65);letter-spacing:.15em">CURRENT BID</small>
        <div class="current-bid"><?= number_format(($featured['precio_actual']??0)/1000,3) ?> ETH</div>
        <small style="color:rgba(212,184,255,.65)">≈ $<?= number_format(($featured['precio_actual']??0)*2.5,2) ?> USD</small>
        <hr style="border-color:rgba(212,184,255,.15);margin:1rem 0">
        <small style="color:rgba(212,184,255,.65);letter-spacing:.15em">⏳ AUCTION ENDS IN</small>
        <div class="countdown-box">
          <div class="cell"><div class="num">00</div><div class="lbl">DAYS</div></div>
          <div class="cell"><div class="num">12</div><div class="lbl">HOURS</div></div>
          <div class="cell"><div class="num">47</div><div class="lbl">MINUTES</div></div>
          <div class="cell"><div class="num">33</div><div class="lbl">SECONDS</div></div>
        </div>
        <hr style="border-color:rgba(212,184,255,.15);margin:1rem 0">
        <small style="color:rgba(212,184,255,.65);letter-spacing:.15em">PLACE YOUR BID</small>
        <div class="input-group mt-2 mb-3" style="background:rgba(12,6,30,.6);border:1px solid rgba(212,184,255,.22);border-radius:12px">
          <input class="input-magic border-0" placeholder="Enter amount in ETH" style="background:transparent">
          <span class="px-3 d-flex align-items-center" style="color:var(--lavender)">ETH</span>
        </div>
        <button class="btn-magic w-100" style="background:linear-gradient(135deg,#5eead4,#14b8a6);color:#06030f;border:none;font-size:1rem;padding:.8rem">✦ PUJAR ✦</button>
        <small class="d-block text-center mt-2" style="color:rgba(212,184,255,.55)">Minimum next bid: 2.800 ETH</small>
      </div>

      <div class="glass-panel">
        <h6 style="font-family:'Cinzel',serif;color:var(--gold);letter-spacing:.1em">BID HISTORY</h6>
        <?php $bids=[['StarGazer','2.750','2 min ago'],['MoonCollector','2.500','7 min ago'],['ArcaneDreamer','2.250','15 min ago'],['VisionSeeker','2.000','28 min ago'],['EtherealMuse','1.750','45 min ago']];
        foreach($bids as $b): ?>
        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(212,184,255,.08)">
          <div class="d-flex align-items-center gap-2"><span style="color:var(--magenta)">✦</span><span style="color:var(--moon)"><?= $b[0] ?></span></div>
          <div style="color:#5eead4;font-weight:600"><?= $b[1] ?> ETH</div>
          <small style="color:rgba(212,184,255,.55)"><?= $b[2] ?></small>
        </div>
        <?php endforeach; ?>
        <a href="#" class="btn-magic w-100 mt-3">VIEW FULL HISTORY</a>
      </div>
    </div>
  </div>

  <h3 class="mt-5 mb-3" style="font-family:'Cinzel',serif;color:var(--gold);letter-spacing:.1em">✦ OTHER ACTIVE AUCTIONS</h3>
  <div class="row g-3">
    <?php $others=[['Voidlight Citadel','Evanor Skye','1.950','01:08:22'],['Spirit of the Glen','Faelin Whisper','1.420','02:35:10'],['Astral Leviathan','Kael Norwen','3.100','05:22:45'],['The Luminous Sanctuary','Mirae Sol','2.600','06:47:18'],['Stardust Embrace','Orion Vale','1.250','09:11:33']];
    foreach($others as $i=>$o): ?>
    <div class="col-md-6 col-lg-4 col-xl">
      <a class="magic-card d-block text-decoration-none">
        <div style="aspect-ratio:1/1;border-radius:10px;background:linear-gradient(<?= ($i*60)%360 ?>deg,#1a0a40,#7a3df0);margin-bottom:.7rem"></div>
        <h6 style="font-family:'Cinzel',serif;color:var(--gold);font-size:.95rem"><?= e($o[0]) ?></h6>
        <small class="d-block" style="color:var(--lavender)">by <?= e($o[1]) ?></small>
        <div class="d-flex justify-content-between mt-2"><b style="color:#5eead4"><?= e($o[2]) ?> ETH</b><small style="color:rgba(212,184,255,.6)">⏳ <?= e($o[3]) ?></small></div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
</div>
