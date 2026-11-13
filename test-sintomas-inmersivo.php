<?php
/**
 * Test de Síntomas — CaszaMosqui (clon inmersivo del modo tradicional)
 * Página independiente con el lenguaje del modo inmersivo: sala clara,
 * paneles oscuros y tipografía Inter Display. Misma funcionalidad que
 * test-sintomas.php; el modo tradicional queda intacto.
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Test de Síntomas';
$active_page = 'test-sintomas';

$reportes_logueado = reportes_logueado();
$reportes_error     = consumir_error_reportes();
$reportes_csrf      = reportes_csrf_token();
$reportes_usuario   = usuario_reportes();

$centros = require __DIR__ . '/inc/centros-salud.php';
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test de Síntomas · <?= e(APP_NAME) ?></title>
  <meta name="description" content="Test de síntomas de dengue, zika y chikungunya en El Colorado: marcá lo que sentís y sabé si debés acudir urgente a un centro de salud.">
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

  <a class="inm-saltar" href="#form-sintomas">Saltar al test de síntomas</a>

  <header class="inm-nav">
    <a class="inm-marca" href="<?= e(BASE_URL) ?>/index.php" aria-label="CaszaMosqui, volver a la portada">
      <span class="inm-marca-punto" aria-hidden="true"></span>
      CASZAMOSQUI
    </a>
    <p class="inm-nav-modo">Test de síntomas <span>·</span> El Colorado, Formosa</p>
    <nav class="inm-nav-enlaces" aria-label="Navegación del test">
      <a href="<?= e(BASE_URL) ?>/inmersivo.php">Modo inmersivo</a>
      <a href="<?= e(BASE_URL) ?>/tradicional.php">Modo tradicional</a>
      <a class="inm-nav-salir" href="<?= e(BASE_URL) ?>/index.php">Salir</a>
    </nav>
  </header>

  <main class="mr-pagina">

    <!-- PORTADA -->
    <section class="mr-hero" aria-labelledby="ts-titulo">
      <div class="mr-copy">
        <p class="inm-sobrelinea"><span class="inm-latido" aria-hidden="true"></span> Test de síntomas</p>
        <h1 class="inm-titulo mp-titulo" id="ts-titulo">
          Verificá<br><span>tus síntomas.</span>
        </h1>
        <p class="inm-lead">
          Esto <strong>no es un diagnóstico médico</strong>; te ayuda a saber si debés
          ir <strong>urgente</strong> al centro de salud más cercano.
        </p>
        <div class="inm-acciones">
          <a class="inm-btn inm-btn-primario" href="#form-sintomas">
            Empezar el test
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
                 src="<?= e(BASE_URL) ?>/assets/video/boton-test-sintomas.mp4"
                 poster="<?= e(BASE_URL) ?>/assets/img/boton-test-sintomas-poster.jpg"
                 autoplay muted loop playsinline preload="metadata"
                 aria-label="Test de síntomas del dengue"></video>
        </div>
        <figcaption><span>Test de síntomas</span><span>Detección temprana</span></figcaption>
      </figure>
    </section>

    <!-- TEST -->
    <section class="mp-test" aria-label="Formulario del test" style="margin-top: 26px;">
      <div class="mp-test-card">
        <h3>🩺 Verificá tus síntomas</h3>
        <p class="mp-test-sub">
          Marcá los síntomas que tenés ahora. <strong>Esto no es un diagnóstico médico</strong>,
          pero te ayuda a saber si debés ir urgente a un centro de salud.
        </p>

        <form id="form-sintomas">
          <!-- Síntomas de ALERTA (dengue grave) -->
          <div class="sintoma">
            <input type="checkbox" id="s1" name="sintomas[]" value="fiebre_alta" data-gravedad="alta">
            <label for="s1">Fiebre alta (> 38.5°C) <span class="gravedad gravedad-alta">ALERTA</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s2" name="sintomas[]" value="dolor_abdominal" data-gravedad="alta">
            <label for="s2">Dolor abdominal intenso y sostenido <span class="gravedad gravedad-alta">ALERTA</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s3" name="sintomas[]" value="vomitos" data-gravedad="alta">
            <label for="s3">Vómitos persistentes (3 o más en 24 h) <span class="gravedad gravedad-alta">ALERTA</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s4" name="sintomas[]" value="sangrado" data-gravedad="alta">
            <label for="s4">Sangrado (encías, nariz, heces, orina) <span class="gravedad gravedad-alta">ALERTA</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s5" name="sintomas[]" value="somnolencia" data-gravedad="alta">
            <label for="s5">Somnolencia, irritabilidad o confusión <span class="gravedad gravedad-alta">ALERTA</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s6" name="sintomas[]" value="dificultad_respirar" data-gravedad="alta">
            <label for="s6">Dificultad para respirar <span class="gravedad gravedad-alta">ALERTA</span></label>
          </div>

          <!-- Síntomas clásicos de dengue/zika/chikungunya -->
          <div class="sintoma">
            <input type="checkbox" id="s7" name="sintomas[]" value="fiebre" data-gravedad="media">
            <label for="s7">Fiebre (38–38.5°C) <span class="gravedad gravedad-media">Común</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s8" name="sintomas[]" value="dolor_cabeza" data-gravedad="media">
            <label for="s8">Dolor de cabeza intenso (detrás de los ojos) <span class="gravedad gravedad-media">Común</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s9" name="sintomas[]" value="dolor_articular" data-gravedad="media">
            <label for="s9">Dolor muscular y articular fuerte <span class="gravedad gravedad-media">Común</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s10" name="sintomas[]" value="sarpullido" data-gravedad="media">
            <label for="s10">Sarpullido / erupción en piel <span class="gravedad gravedad-media">Común</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s11" name="sintomas[]" value="nauseas" data-gravedad="media">
            <label for="s11">Náuseas o vómitos ocasionales <span class="gravedad gravedad-media">Común</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s12" name="sintomas[]" value="dolor_ojos" data-gravedad="media">
            <label for="s12">Dolor detrás de los ojos (retroocular) <span class="gravedad gravedad-media">Común</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s13" name="sintomas[]" value="cansancio" data-gravedad="baja">
            <label for="s13">Cansancio extremo / debilidad <span class="gravedad gravedad-baja">Leve</span></label>
          </div>
          <div class="sintoma">
            <input type="checkbox" id="s14" name="sintomas[]" value="conjuntivitis" data-gravedad="baja">
            <label for="s14">Ojos rojos / conjuntivitis (típico Zika) <span class="gravedad gravedad-baja">Leve</span></label>
          </div>
        </form>

        <button type="button" class="mp-evaluar" id="btn-evaluar" disabled>Evaluar síntomas</button>
      </div>

      <!-- Resultado -->
      <div id="resultado" class="mp-resultado" role="alert" aria-live="assertive"></div>

      <div class="mp-test-card">
        <h3>📍 Dónde atenderse en El Colorado</h3>
        <ul class="mp-test-centros" style="margin: 0; padding-left: 18px;">
          <?php foreach ($centros as $c): ?>
          <li>
            <strong><?= e($c['nombre']) ?></strong> — <?= e($c['direccion']) ?><br>
            <small>🕒 <?= e($c['horario_txt']) ?></small>
            <?php foreach ($c['telefonos'] as [$etq, $ver, $marcar]): ?>
              <a href="tel:<?= e($marcar) ?>">📞 <?= e($ver) ?></a>
            <?php endforeach; ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <p style="margin: 12px 0 0;"><a href="<?= e(BASE_URL) ?>/centros-salud-inmersivo.php" style="color:var(--acento); font-weight:700;">🧭 Ver el mapa y cómo llegar →</a></p>
        <p style="margin-top: 12px; font-size: 0.8rem; color: var(--inm-panel-tenue);">
          🚨 <strong style="color:#ffb3a7">Si tenés síntomas de ALERTA (rojos), no esperes: andá YA a la guardia del hospital o llamá al <a href="tel:107" style="color:#ffb3a7">107</a>.</strong>
        </p>
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
    window.CENTROS_SALUD = <?= json_encode($centros, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register(window.BASE_URL + '/sw.js').catch(function () {});
      });
    }
  </script>

  <script>
    const form = document.getElementById('form-sintomas');
    const btn = document.getElementById('btn-evaluar');
    const res = document.getElementById('resultado');

    const ALERTA = ['fiebre_alta','dolor_abdominal','vomitos','sangrado','somnolencia','dificultad_respirar'];

    form.addEventListener('change', () => {
      const checked = form.querySelectorAll('input:checked').length;
      btn.disabled = checked === 0;
    });

    btn.addEventListener('click', () => {
      const seleccionados = Array.from(form.querySelectorAll('input:checked')).map(i => i.value);
      const tieneAlerta = seleccionados.some(s => ALERTA.includes(s));
      const total = seleccionados.length;
      const volver = '<a href="' + (window.BASE_URL ?? '') + '/inmersivo.php" class="inm-btn inm-btn-sec" style="margin-top:14px;display:inline-flex;">Volver al recorrido inmersivo</a>';

      if (tieneAlerta) {
        res.className = 'mp-resultado alerta';
        res.innerHTML = `
          <h3>⚠️ ¡ACUDÍ URGENTE A UN SANATORIO!</h3>
          <p>Tenés <strong>${total} síntoma(s)</strong> y al menos uno es de <strong>ALERTA ROJA</strong>
             (dengue grave / shock).<br>
             No te automediques. No esperes a que pase.<br>
             <strong>Andá YA a la guardia más cercana.</strong></p>${volver}`;
      } else if (total >= 3) {
        res.className = 'mp-resultado alerta';
        res.innerHTML = `
          <h3>🩺 Consultá hoy mismo</h3>
          <p>Tenés <strong>${total} síntoma(s)</strong> compatibles con dengue/zika/chikungunya.
             Aunque no tengas signos de alarma, <strong>requerís evaluación médica</strong>.</p>${volver}`;
      } else if (total > 0) {
        res.className = 'mp-resultado ok';
        res.innerHTML = `
          <h3>✅ Pocos síntomas, pero atent@</h3>
          <p>Tenés <strong>${total} síntoma(s)</strong> leves. Podría ser inicio de dengue u otra enfermedad.
             <strong>Controlá tu temperatura</strong> y si aparece fiebre alta o alguno de los
             síntomas de ALERTA (rojos), <strong>andá al centro de salud</strong>.</p>${volver}`;
      } else {
        res.className = 'mp-resultado ok';
        res.innerHTML = `
          <h3>👍 Sin síntomas marcados</h3>
          <p>No seleccionaste ningún síntoma. Seguí usando repelente y descacharrando
             y reportando criaderos.</p>${volver}`;
      }
    });
  </script>

  <script src="<?= e(BASE_URL) ?>/assets/js/offline.js?v=20261111-app"></script>
  <script src="<?= e(BASE_URL) ?>/assets/js/chatbot.js"></script>
</body>
</html>
