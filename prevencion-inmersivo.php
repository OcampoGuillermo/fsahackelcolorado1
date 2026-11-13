<?php
/**
 * CaszaMosqui — Prevención (clon inmersivo del modo tradicional)
 * Página independiente con el lenguaje del modo inmersivo: sala clara,
 * paneles oscuros y tipografía Inter Display. Misma funcionalidad que
 * prevencion.php; el modo tradicional queda intacto.
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Prevención';
$active_page = 'prevencion';

$reportes_logueado = reportes_logueado();
$reportes_error     = consumir_error_reportes();
$reportes_csrf      = reportes_csrf_token();
$reportes_usuario   = usuario_reportes();
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prevención · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Guía de prevención del dengue, zika y chikungunya en El Colorado: cómo cortar el ciclo del mosquito en tu casa, en tu barrio.">
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

  <a class="inm-saltar" href="#prev">Saltar a la guía de prevención</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Prevención · El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación de prevención">
      <a href="<?= e(BASE_URL) ?>/inmersivo.php">Modo inmersivo</a>
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main class="mr-pagina">

    <!-- PORTADA -->
    <section class="mr-hero" aria-labelledby="pi-titulo">
      <div class="mr-copy">
        <p class="inm-sobrelinea"><span class="inm-latido" aria-hidden="true"></span> Guía de prevención</p>
        <h1 class="inm-titulo mp-titulo" id="pi-titulo">
          Sin agua,<br><span>no hay mosquito.</span>
        </h1>
        <p class="inm-lead">
          El <strong>Aedes aegypti</strong>, que transmite dengue, zika y chikungunya, pone sus huevos en
          <strong>agua limpia y quieta</strong> cerca de las casas. Cortar su ciclo depende de cada patio.
        </p>
        <div class="inm-acciones">
          <a class="inm-btn inm-btn-primario" href="<?= e(BASE_URL) ?>/test-sintomas-inmersivo.php">
            Hacer el test de síntomas
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/inmersivo.php">
            Volver al recorrido inmersivo
          </a>
        </div>
      </div>

      <figure class="inm-marco mr-marco">
        <div class="inm-marco-pantalla">
          <video class="mr-video mr-video-wide mp-video-wide"
                 src="<?= e(BASE_URL) ?>/assets/video/boton-prevencion.mp4"
                 poster="<?= e(BASE_URL) ?>/assets/img/boton-prevencion-poster.jpg"
                 autoplay muted loop playsinline preload="metadata"
                 aria-label="Prevención de la proliferación de mosquitos"></video>
        </div>
        <figcaption><span>Prevención</span><span>Aedes aegypti</span></figcaption>
      </figure>
    </section>

    <div id="prev">

      <h2 class="mp-prev-titulo">Las 3 acciones que más sirven</h2>
      <div class="mp-claves">
        <article class="mp-clave"><span class="mp-num">01</span><span class="mp-ico">🪣</span>
          <h3>Tapá</h3>
          <p>Tanques, tachos y baldes: siempre con tapa ajustada o tela mosquitera. El agua quieta y a la sombra es la cuna perfecta.</p>
        </article>
        <article class="mp-clave"><span class="mp-num">02</span><span class="mp-ico">🔄</span>
          <h3>Vaciá y dá vuelta</h3>
          <p>Baldes, macetas, bebederos y juguetes, boca abajo o vacíos. Una vuelta cada semana corta el ciclo.</p>
        </article>
        <article class="mp-clave"><span class="mp-num">03</span><span class="mp-ico">🗑️</span>
          <h3>Tirá</h3>
          <p>Neumáticos, latas, botellas y cacharros. Menos cacharros, menos criaderos: menos mosquitos.</p>
        </article>
      </div>

      <h2 class="mp-prev-titulo">Así crece el mosquito (por eso la revisión es semanal)</h2>
      <ol class="mp-prev-ciclo">
        <li><span>🥚</span><strong>Huevo</strong><small>Pegado a la pared del recipiente; resiste meses seco</small></li>
        <li><span>🐛</span><strong>Larva</strong><small>Nace cuando el recipiente vuelve a salir</small></li>
        <li><span>⏳</span><strong>Pupa</strong><small>Última etapa bajo el agua</small></li>
        <li><span>🦟</span><strong>Mosquito</strong><small>Pica de día: al amanecer y al atardecer</small></li>
      </ol>

      <h2 class="mp-prev-titulo">Revisá tu casa</h2>
      <div class="mp-tips">
        <article class="mp-tip" style="--c:#0ea5e9"><span class="mp-tip-ico">🪣</span><h4>Descacharrá</h4><p>Tirá latas, botellas, baldes y cacharros que junten agua.</p></article>
        <article class="mp-tip" style="--c:#6366f1"><span class="mp-tip-ico">🛢️</span><h4>Tapá los tanques</h4><p>Tanques y recipientes grandes, siempre con tapa bien ajustada.</p></article>
        <article class="mp-tip" style="--c:#f59e0b"><span class="mp-tip-ico">🛞</span><h4>Neumáticos</h4><p>Guardalos bajo techo o perforalos para que no junten agua.</p></article>
        <article class="mp-tip" style="--c:#14b8a6"><span class="mp-tip-ico">💧</span><h4>Vaciá y limpiá</h4><p>Platitos de macetas, bebederos y piletas: semanal.</p></article>
        <article class="mp-tip" style="--c:#84cc16"><span class="mp-tip-ico">🧹</span><h4>Limpiá canaletas</h4><p>Hojas y tierra en desagües hacen charcos que son cuna de larvas.</p></article>
        <article class="mp-tip" style="--c:#ec4899"><span class="mp-tip-ico">🛡️</span><h4>Protegé tu casa</h4><p>Mosquiteros, espirales, repelente y ropa clara en la tarde.</p></article>
      </div>

      <div class="mp-alerta">
        <div class="mp-alerta-ico" aria-hidden="true">🚨</div>
        <div>
          <h4>Síntomas de alarma del dengue</h4>
          <ul>
            <li>Fiebre alta</li><li>Dolor detrás de los ojos</li><li>Dolor muscular y articular</li><li>Sarpullido</li>
          </ul>
          <p><strong>No te automediques</strong> (la aspirina complica el dengue): consultá al
            <a href="<?= e(BASE_URL) ?>/centros-salud-inmersivo.php" style="color:#ffb3a7;font-weight:700;">centro de salud más cercano</a>.</p>
        </div>
      </div>

    </div>

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
