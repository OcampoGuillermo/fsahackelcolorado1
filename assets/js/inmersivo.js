/* ============================================================
   CaszaMosqui — Modo inmersivo · recorrido horizontal
   Salas 3D, scroll lateral, datos en vivo y accesibilidad.
   Sin dependencias externas.
   ============================================================ */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var BASE = window.BASE_URL || '';

  document.body.classList.add('inm-js');

  var pista = document.querySelector('[data-js-pista]');
  var slides = Array.prototype.slice.call(document.querySelectorAll('.inm-slide'));
  if (!pista || !slides.length) return;

  var dots = Array.prototype.slice.call(document.querySelectorAll('[data-js-dots] button'));
  var actualEl = document.querySelector('[data-js-actual]');
  var progresoEl = document.querySelector('[data-js-progreso]');
  var deslizaEl = document.querySelector('[data-js-desliza]');
  var ambiente = document.querySelector('.inm-ambiente');
  var btnPrev = document.querySelector('[data-js-prev]');
  var btnNext = document.querySelectorAll('[data-js-next]');

  var total = slides.length;
  var actual = -1;
  var inicializando = true;
  var suprimircClick = false;
  var drag = null;
  var rafPendiente = false;
  var bloqueoRueda = 0;

  function anchoPista() {
    return pista.clientWidth || window.innerWidth;
  }

  function irA(indice, suave) {
    var i = Math.max(0, Math.min(total - 1, indice));
    var comportamiento = suave && !reduceMotion ? 'smooth' : 'auto';
    pista.scrollTo({ left: Math.round(anchoPista() * i), behavior: comportamiento });
  }

  function indiceActual() {
    return Math.max(0, Math.min(total - 1, Math.round(pista.scrollLeft / anchoPista())));
  }

  function actualizarHash(indice) {
    if (inicializando || !window.history || !history.replaceState) return;
    var hash = indice === 0 ? '' : '#' + slides[indice].id;
    var url = window.location.pathname + window.location.search + hash;
    if (window.location.pathname + window.location.search + window.location.hash !== url) {
      history.replaceState(null, '', url);
    }
  }

  function activar(indice) {
    if (indice === actual) return;
    actual = indice;

    slides.forEach(function (slide, i) {
      var activo = i === indice;
      slide.classList.toggle('is-active', activo);
      if (activo) slide.classList.add('inm-visto');

      if (activo) {
        slide.removeAttribute('inert');
        slide.removeAttribute('aria-hidden');
      } else {
        slide.setAttribute('inert', '');
        slide.setAttribute('aria-hidden', 'true');
      }
    });

    dots.forEach(function (dot, i) {
      var activo = i === indice;
      dot.classList.toggle('activo', activo);
      if (activo) dot.setAttribute('aria-current', 'true');
      else dot.removeAttribute('aria-current');
    });

    if (actualEl) actualEl.textContent = String(indice + 1).padStart(2, '0');

    if (btnPrev) btnPrev.disabled = indice === 0;
    Array.prototype.forEach.call(btnNext, function (btn) {
      btn.disabled = indice === total - 1;
    });

    /* Reproducir el video de la sala activa; pausar los demás */
    slides.forEach(function (slide, i) {
      var vids = slide.querySelectorAll('video');
      Array.prototype.forEach.call(vids, function (v) {
        if (i === indice) { var p = v.play(); if (p && p.catch) p.catch(function () {}); }
        else { try { v.pause(); } catch (err) {} }
      });
    });

    actualizarHash(indice);
  }

  function actualizar() {
    rafPendiente = false;

    var vw = anchoPista();
    var sl = pista.scrollLeft;

    slides.forEach(function (slide) {
      var d = (slide.offsetLeft - sl) / vw;
      var ad = Math.min(Math.abs(d), 1.2);
      slide.style.setProperty('--d', d.toFixed(4));
      slide.style.setProperty('--ad', ad.toFixed(4));
    });

    var idx = indiceActual();
    activar(idx);

    var max = Math.max(1, (total - 1) * vw);
    var p = Math.max(0, Math.min(1, sl / max));
    if (progresoEl) progresoEl.style.transform = 'scaleX(' + p.toFixed(4) + ')';

    if (deslizaEl) deslizaEl.classList.toggle('oculto', sl > vw * 0.1);
    if (ambiente) ambiente.style.setProperty('--p', (sl / vw).toFixed(2));
  }

  function pedirActualizar() {
    if (rafPendiente) return;
    rafPendiente = true;
    window.requestAnimationFrame(actualizar);
  }

  pista.addEventListener('scroll', pedirActualizar, { passive: true });

  /* ---------- Flechas, puntos y botones ---------- */
  if (btnPrev) btnPrev.addEventListener('click', function () { irA(indiceActual() - 1, true); });
  Array.prototype.forEach.call(btnNext, function (btn) {
    btn.addEventListener('click', function () { irA(indiceActual() + 1, true); });
  });
  dots.forEach(function (dot) {
    dot.addEventListener('click', function () {
      var i = parseInt(dot.getAttribute('data-ir'), 10);
      if (isFinite(i)) irA(i, true);
    });
  });

  /* ---------- Rueda: vertical → horizontal ---------- */
  function tieneScrollVertical(el) {
    while (el && el !== pista) {
      var estilo = window.getComputedStyle(el);
      if ((estilo.overflowY === 'auto' || estilo.overflowY === 'scroll') &&
          el.scrollHeight > el.clientHeight + 3) {
        return true;
      }
      el = el.parentElement;
    }
    return false;
  }

  pista.addEventListener('wheel', function (e) {
    if (e.ctrlKey) return;
    if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) return;
    if (!e.deltaY) return;
    if (tieneScrollVertical(e.target)) return;

    e.preventDefault();

    var dy = e.deltaY;
    if (e.deltaMode === 1) dy *= 16;
    else if (e.deltaMode === 2) dy *= window.innerHeight;

    var ahora = Date.now();
    if (ahora < bloqueoRueda) return;
    if (Math.abs(dy) < 8) return;
    bloqueoRueda = ahora + 760;

    irA(indiceActual() + (dy > 0 ? 1 : -1), true);
  }, { passive: false });

  /* ---------- Arrastrar con el mouse ---------- */
  pista.addEventListener('pointerdown', function (e) {
    if (e.pointerType !== 'mouse' || e.button !== 0) return;
    if (e.target.closest('a, button, input, textarea, select, [contenteditable="true"]')) return;

    drag = {
      x: e.clientX,
      y: e.clientY,
      scroll: pista.scrollLeft,
      id: e.pointerId,
      movido: false
    };
  });

  pista.addEventListener('pointermove', function (e) {
    if (!drag) return;
    var dx = e.clientX - drag.x;
    var dy = e.clientY - drag.y;

    if (!drag.movido) {
      if (Math.abs(dx) < 7 || Math.abs(dx) < Math.abs(dy)) return;
      drag.movido = true;
      pista.classList.add('is-drag');
      try { pista.setPointerCapture(e.pointerId); } catch (err) {}
    }

    pista.scrollLeft = drag.scroll - dx;
  });

  function terminarArrastre() {
    if (!drag) return;
    if (drag.movido) {
      suprimircClick = true;
      window.setTimeout(function () { suprimircClick = false; }, 0);
    }
    drag = null;
    pista.classList.remove('is-drag');
  }

  window.addEventListener('pointerup', terminarArrastre);
  window.addEventListener('pointercancel', terminarArrastre);

  pista.addEventListener('click', function (e) {
    if (!suprimircClick) return;
    e.preventDefault();
    e.stopPropagation();
  }, true);

  /* ---------- Arrastrar con el dedo ---------- */
  var toque = null;

  pista.addEventListener('touchstart', function (e) {
    if (e.touches.length !== 1) { toque = null; return; }
    var t = e.touches[0];
    toque = {
      x: t.clientX,
      y: t.clientY,
      scroll: pista.scrollLeft,
      horizontal: null,
      movido: false
    };
  }, { passive: true });

  pista.addEventListener('touchmove', function (e) {
    if (!toque || e.touches.length !== 1) return;
    var t = e.touches[0];
    var dx = t.clientX - toque.x;
    var dy = t.clientY - toque.y;

    if (toque.horizontal === null) {
      if (Math.abs(dx) < 7 && Math.abs(dy) < 7) return;
      toque.horizontal = Math.abs(dx) > Math.abs(dy);
    }
    if (!toque.horizontal) return;

    toque.movido = true;
    e.preventDefault();
    pista.scrollLeft = toque.scroll - dx;
  }, { passive: false });

  function terminarToque() {
    if (!toque) return;
    if (toque.horizontal && toque.movido) {
      suprimircClick = true;
      window.setTimeout(function () { suprimircClick = false; }, 0);
      irA(Math.round(pista.scrollLeft / anchoPista()), true);
    }
    toque = null;
  }

  pista.addEventListener('touchend', terminarToque);
  pista.addEventListener('touchcancel', terminarToque);

  /* ---------- Teclado ---------- */
  window.addEventListener('keydown', function (e) {
    var target = e.target;
    if (target && target.closest && target.closest('input, textarea, select, [contenteditable="true"]')) return;
    if (e.altKey || e.ctrlKey || e.metaKey) return;

    if (e.key === 'ArrowRight' || e.key === 'PageDown') {
      e.preventDefault();
      irA(indiceActual() + 1, true);
    } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
      e.preventDefault();
      irA(indiceActual() - 1, true);
    } else if (e.key === 'Home') {
      e.preventDefault();
      irA(0, true);
    } else if (e.key === 'End') {
      e.preventDefault();
      irA(total - 1, true);
    }
  });

  /* ---------- Hash de sección ---------- */
  function indiceDelHash() {
    if (!window.location.hash) return 0;
    var id = window.location.hash.slice(1);
    for (var i = 0; i < slides.length; i++) {
      if (slides[i].id === id) return i;
    }
    return 0;
  }

  window.addEventListener('hashchange', function () {
    var i = indiceDelHash();
    if (i !== indiceActual()) irA(i, true);
  });

  /* ---------- Redimensionado ---------- */
  var timerResize = null;
  window.addEventListener('resize', function () {
    window.clearTimeout(timerResize);
    timerResize = window.setTimeout(function () {
      pista.scrollLeft = anchoPista() * Math.max(0, actual);
      pedirActualizar();
    }, 140);
  });

  /* ---------- Arranque ---------- */
  var inicial = indiceDelHash();

  window.addEventListener('load', function () {
    pista.scrollLeft = anchoPista() * inicial;
    actualizar();
    inicializando = false;

    /* Respaldo: si el navegador recuperó el scroll. */
    window.setTimeout(function () {
      pista.scrollLeft = anchoPista() * indiceActual();
      actualizar();
    }, 220);
  });

  window.requestAnimationFrame(function () {
    pista.scrollLeft = anchoPista() * inicial;
    actualizar();
  });
})();