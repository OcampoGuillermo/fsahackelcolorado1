<?php
/**
 * CaszaMosqui — Test de Síntomas
 * FormosaHack 2026 · Detección temprana de dengue/zika/chikungunya
 * Página individual
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/header.php';

$page_title = 'Test de Síntomas';
$active_page = 'sintomas';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?>
<main class="container test-container">

  <!-- HERO -->
  <section class="hero">
    <div>
      <h2>🩺 Test de Síntomas</h2>
      <p>Marcá los síntomas que tenés ahora. Esto no es un diagnóstico médico, pero te ayuda a saber si debés ir urgente a un centro de salud.</p>
      <a href="<?= e(BASE_URL) ?>/" class="btn-hero">← Volver al mapa</a>
    </div>
    <div class="hero-kpis" data-js-hero-kpis></div>
  </section>

  <section class="panel" id="test-sintomas">
    <a href="<?= e(BASE_URL) ?>/" class="btn-volver-inicio">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Volver Al Inicio
    </a>
    <section class="test-card">
      <h3>🩺 Verificá tus síntomas</h3>
      <p style="color: var(--suave); margin-bottom: 16px;">
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
          <label for="s3">Vómitos persistentes (3 o más en 24h) <span class="gravedad gravedad-alta">ALERTA</span></label>
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

      <button type="button" class="btn-alerta" id="btn-evaluar" disabled>
        Evaluar síntomas
      </button>
    </section>

    <!-- Resultado -->
    <div id="resultado" class="resultado" role="alert" aria-live="assertive"></div>

    <section class="test-card" style="background: #f0fdfa; border-color: #99f6e4;">
      <h3>📍 Dónde atenderse en El Colorado</h3>
      <ul class="salud-mini">
        <?php foreach (require __DIR__ . '/inc/centros-salud.php' as $c): ?>
        <li>
          <strong><?= e($c['nombre']) ?></strong> — <?= e($c['direccion']) ?><br>
          <small>🕒 <?= e($c['horario_txt']) ?></small>
          <?php foreach ($c['telefonos'] as [$etq, $ver, $marcar]): ?>
          <a class="salud-tel" href="tel:<?= e($marcar) ?>">📞 <?= e($ver) ?></a>
          <?php endforeach; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <p style="margin-top: 12px; font-size: .9rem; color: var(--suave);">
        🚨 <strong>Si tenés síntomas de ALERTA (rojos), no esperes: andá ya a la guardia del hospital o llamá al <a href="tel:107">107</a>.</strong>
      </p>
    </section>
  </section>

</main>

<?php require __DIR__ . '/inc/footer.php'; ?>

<script>
  window.BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= e(BASE_URL) ?>/assets/js/clima.js?v=20260925-vivo"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/calles.js"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/app.js?v=20261112-redisenio"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/offline.js?v=20261111-app"></script>
</body>
</html>
