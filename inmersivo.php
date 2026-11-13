<?php
/**
 * CaszaMosqui — Modo inmersivo
 * Experiencia visual a pantalla completa con los datos del mapa comunitario.
 * Inspirada en la narrativa de activetheory.net, adaptada a El Colorado.
 */
require_once __DIR__ . '/inc/helpers.php';

$centros = require __DIR__ . '/inc/centros-salud.php';
$centros_mostrar = array_slice($centros, 0, 4);

$page_title = 'Modo inmersivo';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modo inmersivo · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Una experiencia visual sobre el mapa de criaderos de mosquitos de El Colorado: datos en vivo, prevención y centros de salud.">
  <meta name="theme-color" content="#070707">
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/inmersivo.css?v=20261113-modos2">
</head>
<body class="inm">
  <canvas class="inm-fondo" id="inm-fondo" aria-hidden="true"></canvas>
  <div class="inm-grano" aria-hidden="true"></div>

  <a class="inm-saltar" href="#contenido">Saltar al contenido</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Modo inmersivo <span>·</span> El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación del modo inmersivo">
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main id="contenido">

    <!-- 00 · HERO -->
    <section class="inm-hero" aria-labelledby="inm-titulo">
      <div class="inm-hero-texto">
        <p class="inm-sobrelinea"><span class="inm-latido" aria-hidden="true"></span> Vigilancia comunitaria en vivo</p>
        <h1 class="inm-titulo" id="inm-titulo">
          <span class="inm-titulo-a">El enemigo</span>
          <span class="inm-titulo-b">es invisible.</span>
        </h1>
        <p class="inm-bajada">
          El <strong>Aedes aegypti</strong> cría en el agua que queda en tu patio.
          Este es el mapa vivo de los criaderos de El Colorado: cada punto es una casa,
          una esquina, un barrio que puede cambiar hoy.
        </p>
        <div class="inm-acciones">
          <a class="inm-btn inm-btn-primario" href="<?= e(BASE_URL) ?>/reportar.php">
            Reportar un criadero
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a class="inm-btn inm-btn-sec" href="#territorio">Ver el territorio</a>
        </div>
      </div>

      <div class="inm-hero-visual" aria-hidden="true">
        <div class="inm-hero-halo"></div>
        <img src="<?= e(BASE_URL) ?>/assets/img/logo-modo-inmersivo.jpg"
             alt="" width="1824" height="1376" fetchpriority="high">
      </div>

      <div class="inm-scroll" aria-hidden="true">
        <span>Deslizá para explorar</span>
        <i></i>
      </div>
    </section>

    <!-- Cinta conceptual -->
    <div class="inm-cinta" aria-hidden="true">
      <div class="inm-cinta-pista">
        <span>Aedes aegypti</span><span>Dengue</span><span>Zika</span><span>Chikungunya</span>
        <span>El Colorado</span><span>Formosa</span><span>Un reporte = un criadero menos</span>
        <span>Aedes aegypti</span><span>Dengue</span><span>Zika</span><span>Chikungunya</span>
        <span>El Colorado</span><span>Formosa</span><span>Un reporte = un criadero menos</span>
      </div>
    </div>

    <!-- 00 · DATOS EN VIVO -->
    <section class="inm-datos" id="datos" aria-labelledby="inm-datos-titulo">
      <div class="inm-cab">
        <p class="inm-indice">00 / En vivo</p>
        <h2 id="inm-datos-titulo">Lo que el mapa ya sabe.</h2>
      </div>

      <div class="inm-datos-grilla">
        <article class="inm-dato">
          <strong data-contador="total">—</strong>
          <span>Criaderos reportados</span>
        </article>
        <article class="inm-dato">
          <strong data-contador="controlados" data-sufijo="%">—</strong>
          <span>Ya controlados</span>
        </article>
        <article class="inm-dato">
          <strong data-contador="riesgo">—</strong>
          <span>Barrios en alerta</span>
        </article>
        <article class="inm-dato">
          <strong data-contador="barrios">—</strong>
          <span>Barrios en el mapa</span>
        </article>
      </div>

      <div class="inm-datos-pie">
        <p class="inm-nota" data-js-estado>Conectando con el mapa comunitario…</p>
        <ul class="inm-tipos" data-js-tipos aria-label="Criaderos más reportados"></ul>
      </div>
    </section>

    <!-- 01 · TERRITORIO -->
    <section class="inm-territorio" id="territorio" aria-labelledby="inm-territorio-titulo">
      <div class="inm-cab">
        <p class="inm-indice">01 / Territorio</p>
        <h2 id="inm-territorio-titulo">El riesgo tiene dirección.</h2>
        <p class="inm-cab-texto">
          El índice sube con cada criadero sin controlar. Los barrios en rojo
          concentran los focos activos; los verdes ya están en tratamiento.
        </p>
      </div>

      <div class="inm-mapa">
        <div class="inm-mapa-lienzo">
          <div class="inm-mapa-marco">
            <img src="<?= e(BASE_URL) ?>/assets/img/mapa-el-colorado.svg"
                 alt="Plano de barrios de El Colorado con los puntos de riesgo" width="576" height="642" loading="lazy">
            <div class="inm-mapa-puntos" data-js-puntos aria-hidden="true"></div>
          </div>
          <p class="inm-mapa-pie"><span data-js-mapa-pie>—</span> barrios georreferenciados · Plano municipal</p>
        </div>

        <aside class="inm-leyenda" aria-label="Leyenda del mapa">
          <p class="inm-leyenda-titulo">Nivel de riesgo</p>
          <ul>
            <li><i class="nivel-alto" aria-hidden="true"></i><span>Alto</span><em>4 o más focos activos</em></li>
            <li><i class="nivel-medio" aria-hidden="true"></i><span>Medio</span><em>2 o 3 focos activos</em></li>
            <li><i class="nivel-bajo" aria-hidden="true"></i><span>Bajo</span><em>0 o 1 foco activo</em></li>
          </ul>
          <p class="inm-leyenda-nota">El mapa se actualiza con cada reporte verificado por el equipo.</p>
          <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/tradicional.php#mapa">Abrir mapa interactivo</a>
        </aside>
      </div>
    </section>

    <!-- 02 · PREVENCIÓN -->
    <section class="inm-prevencion" id="prevencion" aria-labelledby="inm-prevencion-titulo">
      <div class="inm-cab">
        <p class="inm-indice">02 / Prevención</p>
        <h2 id="inm-prevencion-titulo">Tres gestos.<br>Un barrio sin mosquitos.</h2>
      </div>

      <div class="inm-carrusel" data-js-carrusel tabindex="0" aria-label="Pasos de prevención">
        <article class="inm-paso">
          <p class="inm-paso-num">01</p>
          <h3>Tapá</h3>
          <p>Tanques, tachos y baldes: con tapa o tela mosquitera. El agua quieta y a la sombra es la cuna perfecta.</p>
          <p class="inm-paso-dato">Una hembra puede poner hasta 200 huevos por vez.</p>
        </article>
        <article class="inm-paso">
          <p class="inm-paso-num">02</p>
          <h3>Vaciá</h3>
          <p>Macetas, bebederos y canaletas. Cambiá el agua de las mascotas y cepillá el borde: los huevos quedan pegados.</p>
          <p class="inm-paso-dato">Los huevos resisten meses en seco y vuelven a eclosionar con la lluvia.</p>
        </article>
        <article class="inm-paso">
          <p class="inm-paso-num">03</p>
          <h3>Tirá</h3>
          <p>Neumáticos, latas y botellas. Si junta agua, no tiene que quedar en el patio: llevalo al punto limpio.</p>
          <p class="inm-paso-dato">Un solo neumático puede criar cientos de mosquitos.</p>
        </article>
        <article class="inm-paso inm-paso-extra">
          <p class="inm-paso-num">+</p>
          <h3>Reportá</h3>
          <p>Si ves un foco que no podés resolver, marcalo en el mapa. El equipo y los agentes sanitarios lo verifican.</p>
          <p class="inm-paso-dato">Tu reporte guía la fumigación y la limpieza del barrio.</p>
        </article>
      </div>

      <div class="inm-carrusel-controles">
        <button type="button" data-js-prev aria-label="Paso anterior">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" data-js-next aria-label="Paso siguiente">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
      </div>
    </section>

    <!-- 03 · SALUD -->
    <section class="inm-salud" id="salud" aria-labelledby="inm-salud-titulo">
      <div class="inm-cab">
        <p class="inm-indice">03 / Emergencia</p>
        <h2 id="inm-salud-titulo">Si hay fiebre,<br>no esperes.</h2>
        <p class="inm-cab-texto">
          Fiebre alta, dolor detrás de los ojos, sarpullido o dolor muscular:
          acercate al centro de salud más próximo. No te automediques.
        </p>
      </div>

      <a class="inm-107" href="tel:107">
        <span class="inm-107-etiqueta">Emergencias 24 h</span>
        <strong>107</strong>
        <span class="inm-107-flecha" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </span>
      </a>

      <ul class="inm-centros">
        <?php foreach ($centros_mostrar as $c): ?>
          <li class="inm-centro">
            <div>
              <p class="inm-centro-nombre"><?= e($c['nombre']) ?></p>
              <p class="inm-centro-dir"><?= e($c['direccion']) ?><?= !empty($c['barrio']) ? ' · ' . e($c['barrio']) : '' ?></p>
            </div>
            <p class="inm-centro-tipo"><?= !empty($c['guardia']) ? 'Guardia 24 h' : 'Atención programada' ?></p>
          </li>
        <?php endforeach; ?>
      </ul>

      <a class="inm-enlace-flecha" href="<?= e(BASE_URL) ?>/centros-salud.php">
        Ver todos los centros de salud
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
    </section>

    <!-- 04 · CIERRE -->
    <section class="inm-cierre" id="cierre" aria-labelledby="inm-cierre-titulo">
      <p class="inm-indice">04 / Participá</p>
      <h2 id="inm-cierre-titulo">El mapa cambia<br>cuando reportás.</h2>
      <p class="inm-cab-texto">Un dato tuyo puede ser el que evite el próximo brote en tu barrio.</p>
      <div class="inm-acciones">
        <a class="inm-btn inm-btn-primario" href="<?= e(BASE_URL) ?>/reportar.php">
          Reportar un criadero
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/tradicional.php">Volver al modo tradicional</a>
      </div>
    </section>

  </main>

  <footer class="inm-pie">
    <p>CaszaMosqui · Formosa Hack 2026</p>
    <p>El Colorado, Formosa · Datos del mapa comunitario</p>
    <a href="<?= e(BASE_URL) ?>/index.php">Volver a la portada</a>
  </footer>

  <script>
    window.BASE_URL = <?= json_encode(BASE_URL, JSON_UNESCAPED_SLASHES) ?>;
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register(window.BASE_URL + '/sw.js').catch(function () {});
      });
    }
  </script>
  <script src="<?= e(BASE_URL) ?>/assets/js/inmersivo.js?v=20261113-modos2" defer></script>
</body>
</html>
