<?php
/**
 * CaszaMosqui — Cuestionario de concientización
 * FormosaHack 2026 · Página individual de cuestionario
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/header.php';

$page_title = 'Cuestionario';
$active_page = 'cuestionario';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?>
<main class="container">

  <!-- HERO -->
  <section class="hero">
    <div>
      <h2>🎯 Cuestionario de prevención</h2>
      <p>Respondé las preguntas y conocé tu nivel de conocimiento sobre dengue, zika y chikungunya. Compartí tu resultado con tu barrio.</p>
      <a href="<?= e(BASE_URL) ?>/" class="btn-hero">← Volver al mapa</a>
    </div>
    <div class="hero-kpis" data-js-hero-kpis></div>
  </section>

  <section class="panel" id="quiz">
    <a href="<?= e(BASE_URL) ?>/" class="btn-volver-inicio">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Volver Al Inicio
    </a>
    <div class="cuestionario-encabezado">
      <h2>🎯 ¿Cuánto sabés sobre prevención?</h2>
      <p class="sub">Respondé el cuestionario y recibí tu resultado. Compartilo con tu barrio para frenar al mosquito. 🦟</p>
    </div>
    <div data-js-quiz>
      <p class="loading">Cargando cuestionario…</p>
    </div>
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
