<?php
/**
 * CaszaMosqui — Mapa de riesgo (clon inmersivo)
 * Página independiente con el lenguaje del modo inmersivo: sala clara,
 * paneles oscuros y tipografía Inter Display. Funcionalmente idéntica
 * al mapa de riesgo del modo tradicional (hero + clima + mapa + KPIs).
 * El modo tradicional queda intacto en tradicional.php.
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Mapa de riesgo';
$active_page = 'mapa';

$reportes_logueado = reportes_logueado();
$reportes_error     = consumir_error_reportes();
$reportes_csrf      = reportes_csrf_token();
$reportes_usuario   = usuario_reportes();

// Evitar que el contenido protegido pueda quedar en la caché del navegador.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mapa de riesgo · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Mapa interactivo de los criaderos de mosquitos de El Colorado: semáforo por barrio, datos en vivo, alerta climática y accesos directos a emergencia.">
  <meta name="theme-color" content="#ece9e1">
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/inmersivo.css?v=20261113-salas5">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/leaflet.css">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/clima.css">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/chatbot.css">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/mapa-riesgo.css?v=20261113-mr3">
</head>
<body class="inm mr">

  <div class="inm-ambiente" aria-hidden="true">
    <div class="inm-ambiente-techo"></div>
    <div class="inm-ambiente-piso"></div>
    <div class="inm-ambiente-luz"></div>
    <div class="inm-ambiente-vineta"></div>
  </div>
  <div class="inm-grano" aria-hidden="true"></div>

  <a class="inm-saltar" href="#mapa">Saltar al mapa de riesgo</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Mapa de riesgo <span>·</span> El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación del mapa de riesgo">
      <a href="<?= e(BASE_URL) ?>/inmersivo.php">Modo inmersivo</a>
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main class="mr-pagina">

    <!-- PORTADA: mismas secciones que tradicional.php -->
    <section class="mr-hero" aria-labelledby="mr-hero-titulo">
      <div class="mr-copy">
        <p class="inm-sobrelinea"><span class="inm-latido" aria-hidden="true"></span> Índice en vivo</p>
        <h1 class="inm-titulo mr-titulo" id="mr-hero-titulo">
          ¿Dónde se crían<br>
          <span>los mosquitos?</span>
        </h1>
        <p class="inm-lead">
          Detectá y reportá los criaderos de tu barrio. Entre todos evitamos el
          <strong>dengue, zika y chikungunya</strong>. Un reporte = un criadero menos.
        </p>
        <div class="inm-acciones">
          <a class="inm-btn inm-btn-primario" href="<?= e(BASE_URL) ?>/reportar.php">
            Reportar un criadero
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/inmersivo.php">
            Volver al recorrido inmersivo
          </a>
        </div>
        <p class="mr-hero-kpis" data-js-hero-kpis aria-live="polite"></p>
      </div>

      <figure class="inm-marco mr-marco">
        <div class="inm-marco-pantalla">
          <video class="mr-video"
                 src="<?= e(BASE_URL) ?>/assets/video/mapa-riesgo-loop.mp4"
                 poster="<?= e(BASE_URL) ?>/assets/img/mapa-riesgo-poster.jpg"
                 autoplay muted loop playsinline preload="metadata"
                 aria-label="Relieve 3D de Formosa con los focos de riesgo de dengue"></video>
        </div>
        <figcaption><span>Mapa de riesgo</span><span>Relieve de Formosa</span></figcaption>
      </figure>
    </section>

    <!-- ALERTA CLIMÁTICA -->
    <section class="inm-ventana mr-clima" id="clima" aria-label="Alerta climática">
      <p class="loading">Cargando datos climáticos…</p>
    </section>

    <!-- MAPA DE RIESGO -->
    <section class="inm-ventana mr-mapa" id="mapa" aria-labelledby="mr-mapa-titulo">
      <div class="mr-mapa-head">
        <h2 id="mr-mapa-titulo">🗺️ Mapa de riesgo por barrio</h2>
        <div class="mr-toggle" role="group" aria-label="Tipo de mapa">
          <button type="button" data-vista="plano" class="activo" aria-pressed="true">📐 Plano municipal</button>
          <button type="button" data-vista="real" aria-pressed="false">🌎 Mapa real</button>
        </div>
        <div class="mapa-filtros mr-filtros" role="group" aria-label="Filtrar barrios por nivel de riesgo">
          <button type="button" data-nivel="todos" class="activo">Todos</button>
          <button type="button" data-nivel="alto"><i class="dot alto"></i>Alto</button>
          <button type="button" data-nivel="medio"><i class="dot medio"></i>Medio</button>
          <button type="button" data-nivel="bajo"><i class="dot bajo"></i>Bajo</button>
        </div>
        <div class="mapa-controles mr-controles" role="group" aria-label="Controles de zoom del mapa">
          <button type="button" id="zoom-in" aria-label="Acercar">🔍+</button>
          <button type="button" id="zoom-out" aria-label="Alejar">🔍−</button>
          <button type="button" id="zoom-reset" aria-label="Restablecer vista">⌂</button>
        </div>
      </div>
      <div class="mapa-contenedor mr-contenedor">
        <aside class="mapa-sidebar mr-sidebar" aria-label="Lista de barrios">
          <h3>📍 Barrios</h3>
          <ul data-js-barra-barrios>
            <li><button type="button" data-barrio="todos" class="activo">Todos los barrios</button></li>
          </ul>
        </aside>
        <div class="mapa-wrapper mr-mapa-wrapper">
          <div class="mapa-lienzo" data-js-mapa-lienzo>
            <img class="mapa-imagen" data-js-mapa-imagen src="<?= e(BASE_URL) ?>/assets/img/mapa-el-colorado.svg"
                 alt="Plano de barrios de El Colorado" draggable="false" decoding="async">
            <div class="mapa-burbujas" data-js-mapa-burbujas aria-label="Barrios y nivel de riesgo"></div>
          </div>
        </div>
        <!-- Mapa real (OpenStreetMap): se crea al abrir la pestaña -->
        <div class="mapa-real" data-js-mapa-real hidden aria-label="Mapa real de El Colorado"></div>
        <p class="mapa-nota mapa-nota-real" hidden>Mapa real de <strong>OpenStreetMap</strong>. La ubicación de cada barrio es aproximada (±50 m), calculada a partir del plano municipal.
          Si falta el nombre de una calle, activá <strong>“Mostrar plano municipal encima”</strong> o volvé al <strong>Plano municipal</strong>.</p>
        <p class="mapa-nota mapa-nota-plano">Plano oficial de barrios de El Colorado. Usá <strong>🔍+ / 🔍−</strong> o la ruedita del mouse para zoom, arrastrá para moverte y <strong>⌂</strong> para volver. <strong>Clic en un barrio</strong> (lista o nombre en el mapa) para acercarte y ver sus criaderos.</p>
      </div>
    </section>

    <!-- KPIs -->
    <div class="mr-kpis" aria-hidden="false">
      <?php require __DIR__ . '/inc/kpis.php'; ?>
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
