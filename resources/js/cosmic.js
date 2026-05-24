/* ═══════════════════════════════════════════════════════════════
   ARTCANIA – Cosmic background: estrellas, lluvia y polvo de hadas
   PHP + Bootstrap + jQuery + JS puro (sin frameworks)
   ═══════════════════════════════════════════════════════════════ */
(function () {
  'use strict';

  // Crea el canvas si no existe
  let canvas = document.getElementById('cosmicCanvas');
  if (!canvas) {
    canvas = document.createElement('canvas');
    canvas.id = 'cosmicCanvas';
    document.body.appendChild(canvas);
  }
  const ctx = canvas.getContext('2d');

  let W = 0, H = 0, DPR = Math.min(window.devicePixelRatio || 1, 2);
  function resize() {
    W = window.innerWidth;
    H = window.innerHeight;
    canvas.width  = W * DPR;
    canvas.height = H * DPR;
    canvas.style.width  = W + 'px';
    canvas.style.height = H + 'px';
    ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
  }
  resize();
  window.addEventListener('resize', resize);

  // Densidad adaptativa
  const baseArea = 1920 * 1080;
  const factor   = () => (W * H) / baseArea;

  // ── Estrellas titilantes ────────────────────────────────
  const STAR_COUNT = Math.round(160 * Math.min(1.2, Math.max(.4, factor())));
  const stars = Array.from({ length: STAR_COUNT }, () => ({
    x: Math.random() * W,
    y: Math.random() * H,
    r: Math.random() * 1.4 + .2,
    a: Math.random() * Math.PI * 2,
    s: Math.random() * .02 + .005,
    hue: Math.random() < .25 ? 290 : (Math.random() < .5 ? 270 : 250)
  }));

  // ── Estrellas fugaces ───────────────────────────────────
  const shooters = [];
  function spawnShooter() {
    const fromLeft = Math.random() < .5;
    shooters.push({
      x: fromLeft ? -50 : W + 50,
      y: Math.random() * H * .6,
      vx: (fromLeft ? 1 : -1) * (8 + Math.random() * 6),
      vy: 3 + Math.random() * 3,
      life: 0,
      max: 80 + Math.random() * 40
    });
  }
  setInterval(spawnShooter, 2400);

  // ── Polvo de hadas (partículas que ascienden) ───────────
  const FAIRY_COUNT = Math.round(70 * Math.min(1.2, Math.max(.4, factor())));
  const fairies = Array.from({ length: FAIRY_COUNT }, () => spawnFairy(true));
  function spawnFairy(initial) {
    return {
      x: Math.random() * W,
      y: initial ? Math.random() * H : H + 10,
      vx: (Math.random() - .5) * .4,
      vy: -(.3 + Math.random() * .8),
      r: Math.random() * 1.8 + .6,
      life: 0,
      max: 220 + Math.random() * 180,
      hue: 270 + Math.random() * 40,
      pulse: Math.random() * Math.PI * 2
    };
  }

  // Loop
  let last = performance.now();
  function frame(now) {
    const dt = Math.min(50, now - last); last = now;

    // Fondo translúcido: deja estela suave en estrellas fugaces
    ctx.clearRect(0, 0, W, H);

    // Estrellas
    for (const s of stars) {
      s.a += s.s;
      const tw = (Math.sin(s.a) + 1) * .5;        // 0..1
      const alpha = .25 + tw * .75;
      ctx.beginPath();
      ctx.fillStyle = `hsla(${s.hue}, 90%, ${70 + tw * 20}%, ${alpha})`;
      ctx.shadowBlur = 8;
      ctx.shadowColor = `hsla(${s.hue}, 100%, 80%, ${alpha})`;
      ctx.arc(s.x, s.y, s.r * (.8 + tw * .6), 0, Math.PI * 2);
      ctx.fill();
    }
    ctx.shadowBlur = 0;

    // Fugaces
    for (let i = shooters.length - 1; i >= 0; i--) {
      const sh = shooters[i];
      sh.x += sh.vx;
      sh.y += sh.vy;
      sh.life++;
      const t = 1 - sh.life / sh.max;
      if (t <= 0 || sh.x < -120 || sh.x > W + 120 || sh.y > H + 80) {
        shooters.splice(i, 1); continue;
      }
      const tailX = sh.x - sh.vx * 8;
      const tailY = sh.y - sh.vy * 8;
      const grad = ctx.createLinearGradient(sh.x, sh.y, tailX, tailY);
      grad.addColorStop(0, `rgba(244,236,255,${.95 * t})`);
      grad.addColorStop(.4, `rgba(199,125,255,${.55 * t})`);
      grad.addColorStop(1, 'rgba(122,61,240,0)');
      ctx.strokeStyle = grad;
      ctx.lineWidth = 2.2;
      ctx.lineCap = 'round';
      ctx.beginPath();
      ctx.moveTo(sh.x, sh.y);
      ctx.lineTo(tailX, tailY);
      ctx.stroke();

      // cabeza
      ctx.fillStyle = `rgba(255,255,255,${t})`;
      ctx.shadowBlur = 18;
      ctx.shadowColor = 'rgba(199,125,255,.9)';
      ctx.beginPath();
      ctx.arc(sh.x, sh.y, 2.2, 0, Math.PI * 2);
      ctx.fill();
      ctx.shadowBlur = 0;
    }

    // Polvo de hadas
    for (let i = 0; i < fairies.length; i++) {
      const f = fairies[i];
      f.life++;
      f.pulse += .08;
      f.x += f.vx + Math.sin(f.pulse) * .35;
      f.y += f.vy;
      const lifeT = 1 - f.life / f.max;
      if (lifeT <= 0 || f.y < -10) { fairies[i] = spawnFairy(false); continue; }
      const a = Math.max(0, Math.min(1, lifeT)) * (.5 + .5 * Math.sin(f.pulse));
      ctx.beginPath();
      ctx.fillStyle = `hsla(${f.hue}, 100%, 80%, ${a * .9})`;
      ctx.shadowBlur = 14;
      ctx.shadowColor = `hsla(${f.hue}, 100%, 75%, ${a})`;
      ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2);
      ctx.fill();
    }
    ctx.shadowBlur = 0;

    requestAnimationFrame(frame);
  }
  requestAnimationFrame(frame);

  // Pausa al ocultar pestaña
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) last = performance.now();
  });
})();
