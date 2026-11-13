<?php
/**
 * CaszaMosqui — Centros de salud (clon inmersivo del modo tradicional)
 * Página independiente con el lenguaje del modo inmersivo: sala clara,
 * paneles oscuros y tipografía Inter Display. Misma funcionalidad que
 * centros-salud.php; el modo tradicional queda intacto.
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Centros de salud';
$active_page = 'salud';

$reportes_logueado = reportes_logueado();
$reportes_error     = consumir_error_reportes();
$reportes_csrf      = reportes_csrf_token();
$reportes_usuario   = usuario_reportes();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Centros de salud · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Centros de salud y salas de primeros auxilios de El Colorado: teléfonos, horarios y cómo llegar desde donde estás.">
  <meta name="theme-color" content="#ece9e1">
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/inmersivo.css?v=20261113-salas8">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/mapa-riesgo.css?v=20261113-mr3">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/leaflet.css">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/chatbot.css">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/clones.css?v=20261113-cl2">
</head>
<body class="inm mr">

  <div class="inm-ambiente" aria-hidden="true">
    <div class="inm-ambiente-techo"></div>
    <div class="inm-ambiente-piso"></div>
    <div class="inm-ambiente-luz"></div>
    <div class="inm-ambiente-vineta"></div>
  </div>
  <div class="inm-grano" aria-hidden="true"></div>

  <a class="inm-saltar" href="#salud">Saltar a los centros de salud</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Centros de salud <span>·</span> El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación de centros de salud">
      <a href="<?= e(BASE_URL) ?>/inmersivo.php">Modo inmersivo</a>
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main class="mr-pagina">

    <!-- PORTADA -->
    <section class="mr-hero" aria-labelledby="cs-titulo">
      <div class="mr-copy">
        <p class="inm-sobrelinea"><span class="inm-latido" aria-hidden="true"></span> Centros de salud</p>
        <h1 class="inm-titulo mp-titulo" id="cs-titulo">
          Si hay fiebre,<br><span>no esperes.</span>
        </h1>
        <p class="inm-lead">
          Fiebre alta, dolor detrás de los ojos, sarpullido o dolor muscular:
          <strong>acercate al centro más cercano</strong>. No te automediques.
        </p>
        <div class="inm-acciones">
          <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/inmersivo.php">
            Volver al recorrido inmersivo
          </a>
        </div>
      </div>

      <figure class="inm-marco mr-marco">
        <div class="inm-marco-pantalla">
          <video class="mr-video mr-video-wide mp-video-wide"
                 src="<?= e(BASE_URL) ?>/assets/video/boton-centros-salud.mp4"
                 poster="<?= e(BASE_URL) ?>/assets/img/boton-centros-salud-poster.jpg"
                 autoplay muted loop playsinline preload="metadata"
                 aria-label="Hospital y relieve de El Colorado"></video>
        </div>
        <figcaption><span>Centros de salud</span><span>El Colorado · 24 h</span></figcaption>
      </figure>
    </section>

    <!-- CENTROS -->
    <section class="inm-ventana mr-mapa" id="salud" aria-labelledby="cs-subtitulo" style="margin-top: 8px;">
      <div class="mr-mapa-head">
        <h2 id="cs-subtitulo">🏥 Centros de salud de El Colorado</h2>
      </div>
      <p style="margin: 0 0 14px; color: var(--inm-panel-tenue); font-size: 0.82rem; line-height: 1.55;">
        Si tenés fiebre o síntomas, <strong style="color:var(--inm-panel-tinta)">no te automediques</strong>: consultá.
        Tocá <strong style="color:var(--inm-panel-tinta)">📞</strong> para llamar o
        <strong style="color:var(--inm-panel-tinta)">📍 ¿Cuál me queda más cerca?</strong> para ver el camino desde donde estás.
      </p>

      <a class="mp-107" href="tel:107">
        <span class="mp-107-ico" aria-hidden="true">🚑</span>
        <span style="flex:1;">
          <strong>Emergencias · 107</strong>
          <small>Signos de alarma: llamá o andá a la guardia</small>
        </span>
        <span aria-hidden="true" style="font-size:1.6rem;">→</span>
      </a>

      <div class="mp-cerca-barra">
        <button type="button" class="mp-cerca" data-js-mas-cerca>📍 ¿Cuál me queda más cerca?</button>
        <p class="mp-cerca-aviso" data-js-cerca-aviso aria-live="polite"></p>
      </div>

      <div class="mp-salud-grid">
        <div class="mp-salud-lista" data-js-centros></div>
        <div class="mp-salud-lateral">
          <div class="mp-mapa" data-js-salud-mapa aria-label="Mapa de los centros de salud"></div>
          <div class="ruta-panel" data-js-ruta hidden aria-live="polite"></div>
        </div>
      </div>

      <p class="mp-salud-pie">Datos verificados por el equipo de CaszaMosqui (noviembre 2026). Las salas de primeros auxilios de los barrios se suman cuando confirmemos sus datos.
        Antes de ir a un consultorio o clínica privada, llamá para confirmar el horario.</p>
    </section>

  </main>

  <footer class="mr-pie">
    <div>
      <p class="mr-pie-marca">CASZAMOSQUI</p>
      <p>Formosa Hack 2026 · El Colorado, Formosa</p>
    </div>
    <div class="mr-pie-logos">
      <img src="<?= e(BASE_URL) ?>/assets/img/logo-ipf.png" alt="Instituto Politécnico Formosa" class="mr-pie-logo-ancho" loading="lazy">
      <img src="<?= e(BASE_URL) ?>/assets/img/logo-rfa.png" alt="RFA El Colorado" loading="lazy">
      <img src="<?= e(BASE_URL) ?>/assets/img/logo-ia.png" alt="Inteligencia Artificial" loading="lazy">
    </div>
    <nav class="mr-pie-enlaces" aria-label="Ir a">
      <a href="<?= e(BASE_URL) ?>/inmersivo.php">Modo inmersivo</a>
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a href="<?= e(BASE_URL) ?>/index.php">Portada</a>
    </nav>
  </footer>

  <script>
    window.BASE_URL = <?= json_encode(BASE_URL, JSON_UNESCAPED_SLASHES) ?>;
    window.REPORTES_ACCESS = <?= !empty($reportes_logueado) ? 'true' : 'false' ?>;
    window.CENTROS_SALUD = <?= json_encode(require __DIR__ . '/inc/centros-salud.php', JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register(window.BASE_URL + '/sw.js').catch(function () {});
      });
    }
  </script>
  <script src="<?= e(BASE_URL) ?>/assets/js/clima.js?v=20260925-vivo"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/calles.js"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/app.js?v=20261113-ayuda"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/leaflet.js"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/geo.js?v=20261019-gps"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/mapa-real.js?v=20261113-ayuda"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/centros-salud.js?v=20261113-ayuda"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/reporte-extra.js?v=20261019-gps"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/offline.js?v=20261111-app"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/chatbot.js"></script>
</body>
</html>
