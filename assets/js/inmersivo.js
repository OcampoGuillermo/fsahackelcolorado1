/* ============================================================
   CaszaMosqui — Modo inmersivo
   Fondo animado (canvas), datos en vivo, apariciones y carrusel.
   Sin dependencias externas.
   ============================================================ */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var BASE = window.BASE_URL || '';

  document.body.classList.add('inm-js');

  /* ---------- 1. Fondo: polvo suspendido + curvas de nivel ---------- */
  var canvas = document.getElementById('inm-fondo');
  var ctx = canvas && canvas.getContext ? canvas.getContext('2d') : null;
  var particulas = [];
  var ancho = 0;
  var alto = 0;
  var dpr = 1;
  var pointer = { x: 0.5, y: 0.5 };
  var rafId = null;
  var t0 = performance.now();

  function medir() {
    if (!canvas) return;
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    ancho = window.innerWidth;
    alto = window.innerHeight;
    canvas.width = Math.round(ancho * dpr);
    canvas.height = Math.round(alto * dpr);
    canvas.style.width = ancho + 'px';
    canvas.style.height = alto + 'px';
    if (ctx) ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    crearParticulas();
  }

  function crearParticulas() {
    var cantidad = Math.min(110, Math.max(28, Math.round((ancho * alto) / 19000)));
    particulas = [];
    for (var i = 0; i < cantidad; i++) {
      particulas.push({
        x: Math.random() * ancho,
        y: Math.random() * alto,
        r: Math.random() * 1.5 + 0.4,
        z: Math.random() * 0.8 + 0.2,
        vx: (Math.random() - 0.5) * 0.12,
        vy: -(Math.random() * 0.12 + 0.03),
        fase: Math.random() * Math.PI * 2
      });
    }
  }

  function dibujar(t) {
    if (!ctx) return;
    var tiempo = (t - t0) / 1000;
    ctx.clearRect(0, 0, ancho, alto);

    /* Polvo */
    var px = (pointer.x - 0.5) * 30;
    var py = (pointer.y - 0.5) * 20;

    for (var i = 0; i < particulas.length; i++) {
      var p = particulas[i];
      if (!reduceMotion) {
        p.x += p.vx;
        p.y += p.vy;
        if (p.y < -8) { p.y = alto + 8; p.x = Math.random() * ancho; }
        if (p.x < -8) p.x = ancho + 8;
        if (p.x > ancho + 8) p.x = -8;
      }
      var brillo = 0.22 + 0.18 * Math.sin(tiempo * 0.7 + p.fase);
      var ox = px * p.z;
      var oy = py * p.z;
      ctx.beginPath();
      ctx.arc(p.x + ox, p.y + oy, p.r * p.z, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(245, 242, 234, ' + brillo.toFixed(3) + ')';
      ctx.fill();
    }

    /* Curvas de nivel (horizonte topográfico) */
    var base = alto * 0.72;
    for (var j = 0; j < 4; j++) {
      ctx.beginPath();
      var yBase = base + j * 46;
      var amp = 14 + j * 5;
      for (var x = -20; x <= ancho + 20; x += 8) {
        var y = yBase
          + Math.sin(x * 0.0035 + tiempo * 0.12 + j * 0.9) * amp
          + Math.sin(x * 0.0012 - tiempo * 0.05 + j) * (amp * 0.7);
        if (x === -20) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
      }
      ctx.strokeStyle = 'rgba(245, 242, 234, ' + (0.05 - j * 0.008).toFixed(3) + ')';
      ctx.lineWidth = 1;
      ctx.stroke();
    }

    /* Halo cálido inferior */
    var grad = ctx.createRadialGradient(
      ancho * 0.72, alto * 0.98, 0,
      ancho * 0.72, alto * 0.98, Math.max(ancho, alto) * 0.55
    );
    grad.addColorStop(0, 'rgba(255, 107, 44, 0.10)');
    grad.addColorStop(1, 'rgba(255, 107, 44, 0)');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, ancho, alto);
  }

  function animar(t) {
    dibujar(t);
    rafId = window.requestAnimationFrame(animar);
  }

  if (canvas && ctx) {
    medir();
    if (reduceMotion) {
      dibujar(performance.now());
    } else {
      rafId = window.requestAnimationFrame(animar);
    }

    var resizeTimer = null;
    window.addEventListener('resize', function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(function () {
        medir();
        if (reduceMotion) dibujar(performance.now());
      }, 160);
    });

    window.addEventListener('pointermove', function (e) {
      pointer.x = e.clientX / Math.max(1, window.innerWidth);
      pointer.y = e.clientY / Math.max(1, window.innerHeight);
    }, { passive: true });

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        if (rafId) { window.cancelAnimationFrame(rafId); rafId = null; }
      } else if (!reduceMotion && !rafId) {
        rafId = window.requestAnimationFrame(animar);
      }
    });
  }

  /* ---------- 2. Apariciones al hacer scroll ---------- */
  var revelables = document.querySelectorAll(
    '.inm-cab, .inm-dato, .inm-mapa, .inm-paso, .inm-107, .inm-centro, .inm-cierre > *'
  );

  if ('IntersectionObserver' in window && !reduceMotion) {
    var vistos = new WeakSet();
    var obs = new IntersectionObserver(function (entradas) {
      entradas.forEach(function (entrada) {
        if (!entrada.isIntersecting) return;
        var el = entrada.target;
        el.classList.add('is-visible');
        vistos.add(el);
        obs.unobserve(el);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    revelables.forEach(function (el) {
      if (!vistos.has(el)) obs.observe(el);
    });
  } else {
    revelables.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* ---------- 3. Datos en vivo ---------- */
  var seccionDatos = document.querySelector('.inm-datos');
  var estado = document.querySelector('[data-js-estado]');
  var valores = { total: 0, controlados: 0, riesgo: 0, barrios: 0 };
  var animado = false;
  var datosListos = false;
  var datosVisibles = false;

  function animarNumero(el, destino, sufijo) {
    var duracion = 1200;
    var inicio = null;

    if (reduceMotion) {
      el.textContent = destino + (sufijo || '');
      return;
    }

    function paso(ts) {
      if (inicio === null) inicio = ts;
      var p = Math.min(1, (ts - inicio) / duracion);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(destino * eased) + (sufijo || '');
      if (p < 1) window.requestAnimationFrame(paso);
      else el.textContent = destino + (sufijo || '');
    }
    window.requestAnimationFrame(paso);
  }

  function pintarContadores() {
    if (animado || !datosListos) return;
    animado = true;
    document.querySelectorAll('[data-contador]').forEach(function (el) {
      var clave = el.getAttribute('data-contador');
      if (Object.prototype.hasOwnProperty.call(valores, clave)) {
        animarNumero(el, valores[clave], el.getAttribute('data-sufijo') || '');
      }
    });
  }

  if (seccionDatos && 'IntersectionObserver' in window) {
    var obsDatos = new IntersectionObserver(function (entradas) {
      entradas.forEach(function (e) {
        if (e.isIntersecting) {
          datosVisibles = true;
          pintarContadores();
          obsDatos.disconnect();
        }
      });
    }, { threshold: 0.2 });
    obsDatos.observe(seccionDatos);
  } else {
    datosVisibles = true;
    pintarContadores();
  }

  function pintarMapa(barrios) {
    var contenedor = document.querySelector('[data-js-puntos]');
    var pie = document.querySelector('[data-js-mapa-pie]');
    if (!contenedor) return;

    var conPunto = 0;
    barrios.forEach(function (b, i) {
      var x = parseFloat(b.x);
      var y = parseFloat(b.y);
      if (!isFinite(x) || !isFinite(y)) return;
      conPunto++;

      var punto = document.createElement('span');
      punto.className = 'inm-punto nivel-' + (b.nivel || 'bajo');
      punto.style.left = x + '%';
      punto.style.top = y + '%';
      punto.title = b.nombre + ' · ' + (b.indice || 0) + ' focos activos';
      contenedor.appendChild(punto);

      window.setTimeout(function () {
        punto.classList.add('visible');
      }, reduceMotion ? 0 : 80 + i * 45);
    });

    if (pie) pie.textContent = String(conPunto);
  }

  function pintarTipos(tipos) {
    var lista = document.querySelector('[data-js-tipos]');
    if (!lista) return;
    lista.innerHTML = '';

    tipos
      .filter(function (t) { return Number(t.total) > 0; })
      .slice(0, 5)
      .forEach(function (t) {
        var li = document.createElement('li');
        var nombre = document.createElement('span');
        var total = document.createElement('b');
        nombre.textContent = t.tipo;
        total.textContent = t.total;
        li.appendChild(nombre);
        li.appendChild(total);
        lista.appendChild(li);
      });
  }

  function cargarDatos() {
    if (!window.fetch) return;

    fetch(BASE + '/api/?route=stats', {
      headers: { 'Accept': 'application/json' },
      cache: 'no-store'
    })
      .then(function (r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then(function (json) {
        if (!json || !json.ok || !json.data) throw new Error('Respuesta inválida');

        var d = json.data;
        var barrios = Array.isArray(d.riesgo_barrios) ? d.riesgo_barrios : [];
        var enAlerta = barrios.filter(function (b) {
          return b.nivel === 'alto' || b.nivel === 'medio';
        }).length;

        valores.total = Number(d.total) || 0;
        valores.controlados = Number(d.controlados_pct) || 0;
        valores.riesgo = enAlerta;
        valores.barrios = barrios.length;
        datosListos = true;
        pintarContadores();
        // Respaldo: si la pestaña está en segundo plano, los números quedan listos igual.
        window.setTimeout(pintarContadores, 2200);

        if (animado) {
          document.querySelectorAll('[data-contador]').forEach(function (el) {
            var clave = el.getAttribute('data-contador');
            if (Object.prototype.hasOwnProperty.call(valores, clave)) {
              el.textContent = valores[clave] + (el.getAttribute('data-sufijo') || '');
            }
          });
        }

        pintarMapa(barrios);
        pintarTipos(Array.isArray(d.por_tipo) ? d.por_tipo : []);

        if (estado) {
          var hora = new Date().toLocaleTimeString('es-AR', {
            hour: '2-digit',
            minute: '2-digit'
          });
          estado.textContent =
            'Actualizado ' + hora + ' · ' + valores.total +
            ' reportes cargados por vecinos de El Colorado.';
        }
      })
      .catch(function () {
        if (estado) {
          estado.setAttribute('data-error', '1');
          estado.textContent =
            'No pudimos conectar con el mapa en vivo. Podés ver todos los datos en el modo tradicional.';
        }
        var pie = document.querySelector('[data-js-mapa-pie]');
        if (pie) pie.textContent = '—';
      });
  }

  cargarDatos();

  /* ---------- 4. Carrusel de prevención ---------- */
  var carrusel = document.querySelector('[data-js-carrusel]');
  var btnPrev = document.querySelector('[data-js-prev]');
  var btnNext = document.querySelector('[data-js-next]');

  if (carrusel) {
    function pasoAncho() {
      var primera = carrusel.querySelector('.inm-paso');
      if (!primera) return 320;
      return primera.getBoundingClientRect().width + 1;
    }

    if (btnNext) {
      btnNext.addEventListener('click', function () {
        carrusel.scrollBy({ left: pasoAncho(), behavior: reduceMotion ? 'auto' : 'smooth' });
      });
    }
    if (btnPrev) {
      btnPrev.addEventListener('click', function () {
        carrusel.scrollBy({ left: -pasoAncho(), behavior: reduceMotion ? 'auto' : 'smooth' });
      });
    }

    carrusel.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowRight') {
        e.preventDefault();
        carrusel.scrollBy({ left: pasoAncho(), behavior: 'smooth' });
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        carrusel.scrollBy({ left: -pasoAncho(), behavior: 'smooth' });
      }
    });
  }
})();
