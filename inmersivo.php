<?php
/**
 * CaszaMosqui — Modo inmersivo (recorrido horizontal)
 * Galería 3D invertida: sala clara, paneles oscuros y desplazamiento lateral.
 * Inspirada en la entrada de Panasonic DSR, adaptada a El Colorado.
 */
require_once __DIR__ . '/inc/helpers.php';

$centros = require __DIR__ . '/inc/centros-salud.php';
$centros_mostrar = array_slice($centros, 0, 4);

$slides = [
    'inicio'     => 'Portada',
    'datos'      => 'Datos',
    'territorio' => 'Territorio',
    'prevencion' => 'Prevención',
    'salud'      => 'Salud',
    'cierre'     => 'Participá',
];
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modo inmersivo · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Recorrido horizontal en 3D por el mapa de criaderos de mosquitos de El Colorado: datos en vivo, prevención, emergencias y centros de salud.">
  <meta name="theme-color" content="#ece9e1">
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/inmersivo.css?v=20261113-salas5">
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
            <a class="inm-btn inm-btn-primario" href="<?= e(BASE_URL) ?>/reportar.php">
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

    <!-- 02 · DATOS EN VIVO -->
    <section class="inm-slide" id="datos" data-slide="1" aria-labelledby="inm-datos-titulo">
      <div class="inm-slide-inner inm-slide-datos">
        <header class="inm-cab inm-anim">
          <p class="inm-indice">01 / En vivo</p>
          <h2 id="inm-datos-titulo">Lo que el mapa ya sabe.</h2>
        </header>

        <div class="inm-ventana inm-anim">
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
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">02</span>
    </section>

    <!-- 03 · TERRITORIO -->
    <section class="inm-slide" id="territorio" data-slide="2" aria-labelledby="inm-territorio-titulo">
      <div class="inm-slide-inner inm-territorio">
        <div class="inm-copy">
          <p class="inm-indice inm-anim">02 / Territorio</p>
          <h2 class="inm-anim" id="inm-territorio-titulo">El riesgo<br>tiene dirección.</h2>
          <p class="inm-texto inm-anim">
            El índice sube con cada criadero sin controlar. Los barrios en rojo
            concentran los focos activos; los verdes ya están en tratamiento.
          </p>

          <ul class="inm-leyenda inm-anim" aria-label="Nivel de riesgo">
            <li><i class="nivel-alto" aria-hidden="true"></i><span>Alto</span><em>4+ focos</em></li>
            <li><i class="nivel-medio" aria-hidden="true"></i><span>Medio</span><em>2 o 3</em></li>
            <li><i class="nivel-bajo" aria-hidden="true"></i><span>Bajo</span><em>0 o 1</em></li>
          </ul>

          <a class="inm-enlace inm-anim" href="<?= e(BASE_URL) ?>/tradicional.php#mapa">
            Abrir mapa interactivo
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        </div>

        <figure class="inm-marco inm-marco-mapa inm-anim">
          <div class="inm-marco-pantalla">
            <div class="inm-mapa-lienzo">
              <img src="<?= e(BASE_URL) ?>/assets/img/mapa-el-colorado.svg"
                   alt="Plano de barrios de El Colorado con los puntos de riesgo" width="576" height="642">
              <div class="inm-mapa-puntos" data-js-puntos aria-hidden="true"></div>
            </div>
          </div>
          <figcaption><span><b data-js-mapa-pie>—</b> barrios georreferenciados</span><span>Plano municipal</span></figcaption>
        </figure>
      </div>
      <span class="inm-slide-num" aria-hidden="true">03</span>
    </section>

    <!-- 04 · PREVENCIÓN -->
    <section class="inm-slide" id="prevencion" data-slide="3" aria-labelledby="inm-prevencion-titulo">
      <div class="inm-slide-inner inm-slide-prevencion">
        <header class="inm-cab inm-anim">
          <p class="inm-indice">03 / Prevención</p>
          <h2 id="inm-prevencion-titulo">Tres gestos.<br>Un barrio sin mosquitos.</h2>
        </header>

        <div class="inm-pasos">
          <article class="inm-paso inm-anim">
            <p class="inm-paso-num">01</p>
            <h3>Tapá</h3>
            <p>Tanques, tachos y baldes: con tapa o tela mosquitera. El agua quieta y a la sombra es la cuna perfecta.</p>
            <p class="inm-paso-dato">Una hembra puede poner hasta 200 huevos por vez.</p>
          </article>
          <article class="inm-paso inm-anim">
            <p class="inm-paso-num">02</p>
            <h3>Vaciá</h3>
            <p>Macetas, bebederos y canaletas. Cambiá el agua de las mascotas y cepillá el borde: los huevos quedan pegados.</p>
            <p class="inm-paso-dato">Los huevos resisten meses en seco y vuelven con la lluvia.</p>
          </article>
          <article class="inm-paso inm-anim">
            <p class="inm-paso-num">03</p>
            <h3>Tirá</h3>
            <p>Neumáticos, latas y botellas. Si junta agua, no tiene que quedar en el patio: llevalo al punto limpio.</p>
            <p class="inm-paso-dato">Un solo neumático puede criar cientos de mosquitos.</p>
          </article>
          <article class="inm-paso inm-paso-extra inm-anim">
            <p class="inm-paso-num">+</p>
            <h3>Reportá</h3>
            <p>Si ves un foco que no podés resolver, marcalo en el mapa. El equipo y los agentes sanitarios lo verifican.</p>
            <p class="inm-paso-dato">Tu reporte guía la fumigación y la limpieza del barrio.</p>
          </article>
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">04</span>
    </section>

    <!-- 05 · SALUD -->
    <section class="inm-slide" id="salud" data-slide="4" aria-labelledby="inm-salud-titulo">
      <div class="inm-slide-inner inm-slide-salud">
        <div class="inm-copy">
          <p class="inm-indice inm-anim">04 / Emergencia</p>
          <h2 class="inm-anim" id="inm-salud-titulo">Si hay fiebre,<br>no esperes.</h2>
          <p class="inm-texto inm-anim">
            Fiebre alta, dolor detrás de los ojos, sarpullido o dolor muscular:
            acercate al centro de salud más próximo. No te automediques.
          </p>
          <a class="inm-enlace inm-anim" href="<?= e(BASE_URL) ?>/centros-salud.php">
            Ver todos los centros de salud
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        </div>

        <div class="inm-ventana inm-salud-ventana inm-anim">
          <a class="inm-107" href="tel:107">
            <span class="inm-107-etiqueta">Emergencias<br>24 h</span>
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
                <p class="inm-centro-tipo"><?= !empty($c['guardia']) ? 'Guardia 24 h' : 'Programada' ?></p>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <span class="inm-slide-num" aria-hidden="true">05</span>
    </section>

    <!-- 06 · CIERRE -->
    <section class="inm-slide" id="cierre" data-slide="5" aria-labelledby="inm-cierre-titulo">
      <div class="inm-slide-inner inm-slide-cierre">
        <div class="inm-copy">
          <p class="inm-indice inm-anim">05 / Participá</p>
          <h2 class="inm-anim" id="inm-cierre-titulo">El mapa cambia<br>cuando reportás.</h2>
          <p class="inm-lead inm-anim">Un dato tuyo puede ser el que evite el próximo brote en tu barrio.</p>
          <div class="inm-acciones inm-anim">
            <a class="inm-btn inm-btn-primario" href="<?= e(BASE_URL) ?>/reportar.php">
              Reportar un criadero
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
            <a class="inm-btn inm-btn-sec" href="<?= e(BASE_URL) ?>/tradicional.php">Volver al modo tradicional</a>
          </div>
        </div>

        <aside class="inm-ventana inm-final inm-anim">
          <p class="inm-final-marca">CASZAMOSQUI</p>
          <p class="inm-final-texto">Vigilancia comunitaria contra los criaderos de mosquitos.</p>
          <dl class="inm-final-datos">
            <div><dt>Proyecto</dt><dd>Formosa Hack 2026</dd></div>
            <div><dt>Lugar</dt><dd>El Colorado, Formosa</dd></div>
            <div><dt>Modo</dt><dd>Sala inmersiva 3D</dd></div>
          </dl>
          <div class="inm-final-enlaces">
            <a href="<?= e(BASE_URL) ?>/tradicional.php">Ir al mapa tradicional</a>
            <a href="<?= e(BASE_URL) ?>/index.php">Volver a la portada</a>
          </div>
        </aside>
      </div>
      <span class="inm-slide-num" aria-hidden="true">06</span>
    </section>

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
  <script src="<?= e(BASE_URL) ?>/assets/js/inmersivo.js?v=20261113-salas5" defer></script>
</body>
</html>
