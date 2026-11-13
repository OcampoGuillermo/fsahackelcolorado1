<?php
/**
 * CaszaMosqui — Reportar un criadero (clon inmersivo del modo tradicional)
 * Página independiente con el lenguaje del modo inmersivo: sala clara,
 * paneles oscuros y tipografía Inter Display. Misma funcionalidad que
 * reportar.php; el modo tradicional queda intacto.
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Reportar un criadero';
$active_page = 'reportar';

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
  <title>Reportar un criadero · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Reportá un criadero de mosquitos en El Colorado: tu reporte se suma al mapa de riesgo al instante y guía la fumigación del barrio.">
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

  <a class="inm-saltar" href="#nuevo">Saltar al formulario de reporte</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Reportar un criadero <span>·</span> El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación del reporte">
      <a href="<?= e(BASE_URL) ?>/inmersivo.php">Modo inmersivo</a>
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main class="mr-pagina">

    <!-- PORTADA -->
    <section class="mr-hero" aria-labelledby="ri-titulo">
      <div class="mr-copy">
        <p class="inm-sobrelinea"><span class="inm-latido" aria-hidden="true"></span> + Reportar criadero</p>
        <h1 class="inm-titulo mp-titulo" id="ri-titulo">
          Sumalo<br><span>al mapa.</span>
        </h1>
        <p class="inm-lead">
          Elegí el tipo de situación y el barrio. Tu reporte se suma al mapa de riesgo
          <strong>al instante</strong> y guía la fumigación y limpieza del barrio.
        </p>
        <div class="inm-acciones">
          <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/inmersivo.php">
            Volver al recorrido inmersivo
          </a>
        </div>
      </div>

      <figure class="inm-marco mr-marco">
        <div class="inm-marco-pantalla">
          <video class="mr-video"
                 src="<?= e(BASE_URL) ?>/assets/video/boton-reporte-criadero.mp4"
                 poster="<?= e(BASE_URL) ?>/assets/img/boton-reporte-criadero-poster.jpg"
                 autoplay muted loop playsinline preload="metadata"
                 aria-label="Alarma del mapa de criaderos"></video>
        </div>
        <figcaption><span>+ Reportar criadero</span><span>Relieve de Formosa</span></figcaption>
      </figure>
    </section>

    <!-- FORMULARIO -->
    <section class="inm-ventana mp-form" id="nuevo" aria-labelledby="ri-form-titulo">
      <h2 class="mp-prev-titulo" id="ri-form-titulo">📢 Reportá tu criadero</h2>
      <form data-js-form>
        <div class="mp-form-grid">
          <label>
            Tipo de criadero <span class="mp-req">*</span>
            <select name="tipo_id" required><option value="">Elegí…</option></select>
          </label>
          <label>
            Barrio <span class="mp-req">*</span>
            <select name="barrio_id" required><option value="">Elegí…</option></select>
          </label>
          <label>
            Referencia
            <input type="text" name="referencia" placeholder="Ej.: Pueyrredón y Cayo Novoa Gil">
          </label>
        </div>
        <div class="mp-form-row">
          <label>
            Título <span class="mp-req">*</span>
            <input type="text" name="titulo" maxlength="120" required placeholder="Ej.: Baldes con agua en obra abandonada">
          </label>
        </div>
        <div class="mp-form-row">
          <label>
            Descripción <span class="mp-req">*</span>
            <textarea name="descripcion" rows="4" required placeholder="Contanos qué viste y dónde… puede haber larvas o mosquitos."></textarea>
          </label>
        </div>

        <!-- Opcionales: ubicación GPS y foto (solo las ve el administrador) -->
        <div class="mp-extras">
          <fieldset class="mp-extra">
            <legend>📍 Ubicación exacta <span class="opcional">(opcional)</span></legend>
            <p class="ayuda">Ayuda a encontrar el criadero. Solo la ve el administrador.</p>
            <div class="mp-extra-botones">
              <button type="button" class="mp-ghost" data-js-gps>📍 Usar mi ubicación</button>
              <button type="button" class="mp-ghost" data-js-marcar>🗺️ Marcar en el mapa</button>
              <button type="button" class="mp-quitar" data-js-quitar-ubic hidden>✕ Quitar</button>
            </div>
            <p class="mp-extra-estado" data-js-gps-estado aria-live="polite"></p>
            <div class="mp-mini-mapa mini-mapa" data-js-mini-mapa hidden></div>
          </fieldset>
          <fieldset class="mp-extra">
            <legend>📷 Foto <span class="opcional">(opcional)</span></legend>
            <p class="ayuda">Sacá una foto del criadero o elegí una de tu galería. Solo la ve el administrador.</p>
            <div class="mp-extra-botones">
              <button type="button" class="mp-ghost" data-js-foto-camara>📷 Sacar foto</button>
              <button type="button" class="mp-ghost" data-js-foto-galeria>🖼️ Elegir de la galería</button>
            </div>
            <input type="file" accept="image/*" capture="environment" data-js-foto-input-camara hidden>
            <input type="file" accept="image/*" data-js-foto-input-galeria hidden>
            <p class="mp-extra-estado" data-js-foto-estado aria-live="polite"></p>
            <div class="mp-foto-preview foto-preview" data-js-foto-preview hidden>
              <img alt="Vista previa de la foto del criadero" data-js-foto-img>
              <button type="button" class="mp-quitar" data-js-quitar-foto>✕ Quitar foto</button>
            </div>
          </fieldset>
        </div>

        <button type="submit" class="mp-enviar">Reportar criadero</button>
        <p class="form-msg" data-js-form-msg aria-live="polite"></p>
      </form>
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
