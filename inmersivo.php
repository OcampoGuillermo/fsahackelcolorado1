<?php
/**
 * CaszaMosqui — Modo inmersivo (recorrido horizontal)
 * Galería 3D invertida: sala clara, paneles oscuros y desplazamiento lateral.
 * Inspirada en la entrada de Panasonic DSR, adaptada a El Colorado.
 *
 * Estructura: 01 Portada con el video de mapa → 02–06 botones de video
 * que llevan a los clones inmersivos de cada sección.
 */
require_once __DIR__ . '/inc/helpers.php';

$slides = [
    'inicio'        => 'Portada',
    'reportar'      => 'Reportar un criadero',
    'prevencion'    => 'Prevención',
    'centros'       => 'Centros de salud',
    'cuestionario'  => 'Cuestionario',
    'test-sintomas' => 'Test de síntomas',
];
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modo inmersivo · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Recorrido horizontal en 3D por CaszaMosqui: reportá criaderos, guías de prevención, centros de salud, cuestionario y test de síntomas.">
  <meta name="theme-color" content="#ece9e1">
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/inmersivo.css?v=20261113-salas8">
</head>
<body class="inm">

  <div class="inm-ambiente" aria-hidden="true">
    <div class="inm-ambiente-techo"></div>
    <div class="inm-ambiente-piso"></div>
    <div class="inm-ambiente-luz"></div>
    <div class="inm-ambiente-vineta"></div>
  </div>
  <div class="inm-grano" aria-hidden="true"></div>

  <a class="inm-saltar" href="#contenido">Saltar al contenido</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Sala inmersiva <span>·</span> El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación del modo inmersivo">
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main class="inm-pista" id="contenido" data-js-pista tabindex="0" aria-label="Recorrido horizontal por las secciones">

    <!-- 01 · PORTADA -->
    <section class="inm-slide is-active" id="inicio" data-slide="0" aria-labelledby="inm-titulo">
      <div class="inm-slide-inner inm-inicio">
        <div class="inm-copy">
          <p class="inm-sobrelinea inm-anim"><span class="inm-latido" aria-hidden="true"></span> Vigilancia comunitaria en vivo</p>
          <h1 class="inm-titulo inm-anim" id="inm-titulo">
            El enemigo
            <span>es invisible.</span>
          </h1>
          <p class="inm-lead inm-anim">
            El <strong>Aedes aegypti</strong> cría en el agua que queda en tu patio.
            Este es el mapa vivo de los criaderos de El Colorado: cada punto es una casa,
            una esquina, un barrio que puede cambiar hoy.
          </p>
          <div class="inm-acciones inm-anim">
            <a class="inm-btn-video" href="<?= e(BASE_URL) ?>/mapa-riesgo.php" aria-label="Mapa de riesgo: abrir el mapa interactivo de El Colorado">
              <span class="inm-btn-video-marco" aria-hidden="true">
                <video src="<?= e(BASE_URL) ?>/assets/video/mapa-riesgo-loop.mp4"
                       poster="<?= e(BASE_URL) ?>/assets/img/mapa-riesgo-poster.jpg"
                       autoplay muted loop playsinline preload="metadata" tabindex="-1"></video>
              </span>
              <span class="inm-btn-video-ficha">
                <span class="inm-btn-video-etiqueta">Mapa de riesgo</span>
                <span class="inm-btn-video-sub">Video · Abrir el mapa interactivo</span>
              </span>
              <span class="inm-btn-video-flecha" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </span>
            </a>
            <a class="inm-btn inm-btn-primario" href="<?= e(BASE_URL) ?>/reportar-inmersivo.php">
              Reportar un criadero
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
            <button class="inm-btn inm-btn-sec" type="button" data-js-next>
              Explorar el recorrido
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
          </div>
        </div>

        <figure class="inm-marco inm-marco-hero inm-anim">
          <div class="inm-marco-pantalla">
            <img src="<?= e(BASE_URL) ?>/assets/img/logo-modo-inmersivo.jpg"
                 alt="Emblema de CaszaMosqui sobre un relieve 3D de Formosa" width="1824" height="1376" fetchpriority="high">
          </div>
          <figcaption><span>El Colorado · Formosa</span><span>Mapa comunitario</span></figcaption>
        </figure>
      </div>
      <span class="inm-slide-num" aria-hidden="true">01</span>
    </section>

    <!-- 02 · + REPORTAR CRIADERO (botón 2) -->
    <a class="inm-slide inm-slide-btn" id="reportar" data-slide="1" href="<?= e(BASE_URL) ?>/reportar-inmersivo.php" aria-label="Botón 2: ir a Reportar un criadero">
      <div class="inm-slide-inner inm-btn-slide">
        <figure class="inm-marco inm-btn-slide-video inm-btn-marco-cuad inm-anim">
          <div class="inm-marco-pantalla">
            <video src="<?= e(BASE_URL) ?>/assets/video/boton-reporte-criadero.mp4"
                   poster="<?= e(BASE_URL) ?>/assets/img/boton-reporte-criadero-poster.jpg"
                   autoplay muted loop playsinline preload="metadata" tabindex="-1"></video>
          </div>
          <figcaption><span>Botón 2 / 06</span><span>+ Reportar criadero</span></figcaption>
        </figure>
        <div class="inm-btn-slide-copy inm-anim">
          <p class="inm-btn-slide-kicker"><i aria-hidden="true"></i> Botón 2 / 06 — + Reportar criadero</p>
          <h1 class="inm-titulo inm-btn-slide-titulo">Reportá<br><span>un criadero.</span></h1>
          <p class="inm-lead">Elegí el tipo de situación y el barrio. Tu reporte se suma al mapa de riesgo al instante y guía la fumigación del barrio.</p>
          <span class="inm-btn-slide-cta">Tocá para abrir
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </span>
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">02</span>
    </a>

    <!-- 03 · PREVENCIÓN (botón 3) -->
    <a class="inm-slide inm-slide-btn" id="prevencion" data-slide="2" href="<?= e(BASE_URL) ?>/prevencion-inmersivo.php" aria-label="Botón 3: ir a Prevención">
      <div class="inm-slide-inner inm-btn-slide">
        <figure class="inm-marco inm-btn-slide-video inm-anim">
          <div class="inm-marco-pantalla">
            <video src="<?= e(BASE_URL) ?>/assets/video/boton-prevencion.mp4"
                   poster="<?= e(BASE_URL) ?>/assets/img/boton-prevencion-poster.jpg"
                   autoplay muted loop playsinline preload="metadata" tabindex="-1"></video>
          </div>
          <figcaption><span>Botón 3 / 06</span><span>Prevención</span></figcaption>
        </figure>
        <div class="inm-btn-slide-copy inm-anim">
          <p class="inm-btn-slide-kicker"><i aria-hidden="true"></i> Botón 3 / 06 — Prevención</p>
          <h1 class="inm-titulo inm-btn-slide-titulo"><span>Prevención</span><br>sin agua.</h1>
          <p class="inm-lead">Tapá, vaciá y tirá. Sin agua estancada, no hay mosquito: el Aedes aegypti cría en tu patio.</p>
          <span class="inm-btn-slide-cta">Tocá para abrir
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </span>
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">03</span>
    </a>

    <!-- 04 · CENTROS DE SALUD (botón 4) -->
    <a class="inm-slide inm-slide-btn" id="centros" data-slide="3" href="<?= e(BASE_URL) ?>/centros-salud-inmersivo.php" aria-label="Botón 4: ir a Centros de salud">
      <div class="inm-slide-inner inm-btn-slide">
        <figure class="inm-marco inm-btn-slide-video inm-anim">
          <div class="inm-marco-pantalla">
            <video src="<?= e(BASE_URL) ?>/assets/video/boton-centros-salud.mp4"
                   poster="<?= e(BASE_URL) ?>/assets/img/boton-centros-salud-poster.jpg"
                   autoplay muted loop playsinline preload="metadata" tabindex="-1"></video>
          </div>
          <figcaption><span>Botón 4 / 06</span><span>Centros de salud</span></figcaption>
        </figure>
        <div class="inm-btn-slide-copy inm-anim">
          <p class="inm-btn-slide-kicker"><i aria-hidden="true"></i> Botón 4 / 06 — Centros de salud</p>
          <h1 class="inm-titulo inm-btn-slide-titulo">Centros<br><span>de salud.</span></h1>
          <p class="inm-lead">Fiebre alta, dolor detrás de los ojos, sarpullido: no te automediques. Si tenés síntomas, andá al centro más cercano o llamá al 107.</p>
          <span class="inm-btn-slide-cta">Tocá para abrir
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </span>
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">04</span>
    </a>

    <!-- 05 · CUESTIONARIO (botón 5) -->
    <a class="inm-slide inm-slide-btn" id="cuestionario" data-slide="4" href="<?= e(BASE_URL) ?>/cuestionario-inmersivo.php" aria-label="Botón 5: ir al Cuestionario">
      <div class="inm-slide-inner inm-btn-slide">
        <figure class="inm-marco inm-btn-slide-video inm-anim">
          <div class="inm-marco-pantalla">
            <video src="<?= e(BASE_URL) ?>/assets/video/boton-cuestionario.mp4"
                   poster="<?= e(BASE_URL) ?>/assets/img/boton-cuestionario-poster.jpg"
                   autoplay muted loop playsinline preload="metadata" tabindex="-1"></video>
          </div>
          <figcaption><span>Botón 5 / 06</span><span>Cuestionario</span></figcaption>
        </figure>
        <div class="inm-btn-slide-copy inm-anim">
          <p class="inm-btn-slide-kicker"><i aria-hidden="true"></i> Botón 5 / 06 — Cuestionario</p>
          <h1 class="inm-titulo inm-btn-slide-titulo"><span>¿Cuánto</span><br>sabés?</h1>
          <p class="inm-lead">¿Cuánto sabés sobre prevención del dengue, zika y chikungunya? Respondé y compartí tu resultado con tu barrio.</p>
          <span class="inm-btn-slide-cta">Tocá para abrir
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </span>
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">05</span>
    </a>

    <!-- 06 · TEST DE SÍNTOMAS (botón 6) -->
    <a class="inm-slide inm-slide-btn" id="test-sintomas" data-slide="5" href="<?= e(BASE_URL) ?>/test-sintomas-inmersivo.php" aria-label="Botón 6: ir al Test de síntomas">
      <div class="inm-slide-inner inm-btn-slide">
        <figure class="inm-marco inm-btn-slide-video inm-anim">
          <div class="inm-marco-pantalla">
            <video src="<?= e(BASE_URL) ?>/assets/video/boton-test-sintomas.mp4"
                   poster="<?= e(BASE_URL) ?>/assets/img/boton-test-sintomas-poster.jpg"
                   autoplay muted loop playsinline preload="metadata" tabindex="-1"></video>
          </div>
          <figcaption><span>Botón 6 / 06</span><span>Test de síntomas</span></figcaption>
        </figure>
        <div class="inm-btn-slide-copy inm-anim">
          <p class="inm-btn-slide-kicker"><i aria-hidden="true"></i> Botón 6 / 06 — Test de síntomas</p>
          <h1 class="inm-titulo inm-btn-slide-titulo">Test de<br><span>síntomas.</span></h1>
          <p class="inm-lead">Marcá lo que sentís ahora y sabé si necesitás ir urgente al centro de salud o esperar a la próxima cita.</p>
          <span class="inm-btn-slide-cta">Tocá para abrir
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </span>
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">06</span>
    </a>

  </main>

  <div class="inm-desliza" data-js-desliza aria-hidden="true">
    <span>Deslizá</span><i></i>
  </div>

  <footer class="inm-hud">
    <div class="inm-hud-progreso" aria-hidden="true"><i data-js-progreso></i></div>

    <p class="inm-hud-contador" aria-hidden="true">
      <span data-js-actual>01</span><i>/</i><span><?= str_pad((string) count($slides), 2, '0', STR_PAD_LEFT) ?></span>
    </p>

    <nav class="inm-hud-dots" data-js-dots aria-label="Ir a una sección">
      <?php $i = 0; foreach ($slides as $id => $nombre): $i++; ?>
        <button type="button" data-ir="<?= $i - 1 ?>"<?= $i === 1 ? ' class="activo" aria-current="true"' : '' ?> aria-label="<?= e($nombre) ?>">
          <span><?= str_pad((string) $i, 2, '0', STR_PAD_LEFT) ?></span>
        </button>
      <?php endforeach; ?>
    </nav>

    <div class="inm-hud-flechas">
      <button type="button" data-js-prev aria-label="Sección anterior">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      </button>
      <button type="button" data-js-next aria-label="Sección siguiente">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
    </div>
  </footer>

  <script>
    window.BASE_URL = <?= json_encode(BASE_URL, JSON_UNESCAPED_SLASHES) ?>;
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register(window.BASE_URL + '/sw.js').catch(function () {});
      });
    }
  </script>
  <script src="<?= e(BASE_URL) ?>/assets/js/inmersivo.js?v=20261113-salas8" defer></script>
</body>
</html>
