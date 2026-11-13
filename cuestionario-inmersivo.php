<?php
/**
 * CaszaMosqui — Cuestionario de concientización (clon inmersivo del modo tradicional)
 * Página independiente con el lenguaje del modo inmersivo: sala clara,
 * paneles oscuros y tipografía Inter Display. Misma funcionalidad que
 * cuestionario.php; el modo tradicional queda intacto.
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Cuestionario';
$active_page = 'cuestionario';

$reportes_logueado = reportes_logueado();
$reportes_error     = consumir_error_reportes();
$reportes_csrf      = reportes_csrf_token();
$reportes_usuario   = usuario_reportes();
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cuestionario · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Cuestionario de concientización sobre prevención del dengue, zika y chikungunya en El Colorado.">
  <meta name="theme-color" content="#ece9e1">
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/inmersivo.css?v=20261113-salas8">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/mapa-riesgo.css?v=20261113-mr3">
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

  <a class="inm-saltar" href="#quiz">Saltar al cuestionario</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Cuestionario <span>·</span> El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación del cuestionario">
      <a href="<?= e(BASE_URL) ?>/inmersivo.php">Modo inmersivo</a>
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main class="mr-pagina">

    <!-- PORTADA -->
    <section class="mr-hero" aria-labelledby="cu-titulo">
      <div class="mr-copy">
        <p class="inm-sobrelinea"><span class="inm-latido" aria-hidden="true"></span> Cuestionario</p>
        <h1 class="inm-titulo mp-titulo" id="cu-titulo">
          ¿Cuánto<br><span>sabés?</span>
        </h1>
        <p class="inm-lead">
          Respondé el cuestionario y recibí tu resultado. Compartí tu puntaje con tu barrio:
          entre todos frenamos al mosquito.
        </p>
        <div class="inm-acciones">
          <a class="inm-btn inm-btn-primario" href="#quiz">
            Empezar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M6 13l6 6 6-6"/></svg>
          </a>
          <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/inmersivo.php">
            Volver al recorrido inmersivo
          </a>
        </div>
      </div>

      <figure class="inm-marco mr-marco">
        <div class="inm-marco-pantalla">
          <video class="mr-video mr-video-wide mp-video-wide"
                 src="<?= e(BASE_URL) ?>/assets/video/boton-cuestionario.mp4"
                 poster="<?= e(BASE_URL) ?>/assets/img/boton-cuestionario-poster.jpg"
                 autoplay muted loop playsinline preload="metadata"
                 aria-label="Cuestionario de prevención del dengue"></video>
        </div>
        <figcaption><span>Cuestionario</span><span>¿Cuánto sabés?</span></figcaption>
      </figure>
    </section>

    <!-- CUESTIONARIO -->
    <section id="quiz" aria-label="Cuestionario de prevención" style="scroll-margin-top: 110px; margin-top: 26px;">
      <div class="mp-quiz" data-js-quiz>
        <p class="loading" style="color: var(--inm-tenue);">Cargando cuestionario…</p>
      </div>
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
  <script src="<?= e(BASE_URL) ?>/assets/js/calles.js"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/app.js?v=20261113-ayuda"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/offline.js?v=20261111-app"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/chatbot.js"></script>
</body>
</html>
