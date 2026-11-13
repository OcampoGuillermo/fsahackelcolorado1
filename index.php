<?php
/**
 * CaszaMosqui — Portada de acceso
 * Permite elegir entre el modo tradicional (sitio completo) y el modo inmersivo.
 */
require_once __DIR__ . '/inc/helpers.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CaszaMosqui · Elegí tu modo</title>
  <meta name="description" content="Elegí cómo entrar a CaszaMosqui: modo tradicional con mapa, reportes y prevención, o modo inmersivo con una experiencia visual.">
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <meta name="theme-color" content="#070707">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/portal.css?v=20261113-modos2">
</head>
<body class="portal">
  <div class="portal-grano" aria-hidden="true"></div>

  <header class="portal-top">
    <span class="portal-marca">CASZAMOSQUI</span>
    <span class="portal-evento">Formosa Hack 2026 · El Colorado, Formosa</span>
  </header>

  <main class="portal-modos" aria-label="Elegí cómo ingresar">

    <a class="modo modo-tradicional" href="<?= e(BASE_URL) ?>/tradicional.php"
       aria-label="Ingresar Modo Tradicional: mapa, reportes y guía de prevención">
      <span class="modo-visual">
        <img src="<?= e(BASE_URL) ?>/assets/img/logo-casza.jpeg"
             alt="Logo de CaszaMosqui sobre el mapa de Formosa" width="1172" height="896" fetchpriority="high">
      </span>
      <span class="modo-info">
        <span class="modo-num" aria-hidden="true">01</span>
        <span class="modo-textos">
          <span class="modo-titulo">Ingresar Modo Tradicional</span>
          <span class="modo-desc">Mapa de riesgo, reportes y guía de prevención</span>
        </span>
        <span class="modo-flecha" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </span>
      </span>
    </a>

    <a class="modo modo-inmersivo" href="<?= e(BASE_URL) ?>/inmersivo.php"
       aria-label="Ingresar Modo Inmersivo: experiencia visual sobre el mapa y los datos">
      <span class="modo-visual">
        <img src="<?= e(BASE_URL) ?>/assets/img/logo-modo-inmersivo.jpg"
             alt="Emblema de CaszaMosqui sobre un relieve 3D de Formosa" width="1824" height="1376" fetchpriority="high">
      </span>
      <span class="modo-info">
        <span class="modo-num" aria-hidden="true">02</span>
        <span class="modo-textos">
          <span class="modo-titulo">Ingresar Modo Inmersivo</span>
          <span class="modo-desc">Una experiencia visual sobre el mapa y los datos</span>
        </span>
        <span class="modo-flecha" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </span>
      </span>
    </a>

  </main>

  <footer class="portal-pie">
    <span>Elegí un modo para comenzar</span>
    <span class="portal-pie-punto" aria-hidden="true"></span>
  </footer>

  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register('<?= e(BASE_URL) ?>/sw.js').catch(function () {});
      });
    }
  </script>
</body>
</html>
