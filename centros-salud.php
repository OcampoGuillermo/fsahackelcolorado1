<?php
/**
 * CaszaMosqui — Centros de Salud
 * FormosaHack 2026 · Dónde atenderse
 * Página individual de centros de salud
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/header.php';

$page_title = 'Centros de Salud';
$active_page = 'centros';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?>
<main class="container">

  <!-- HERO -->
  <section class="hero">
    <div>
      <h2>🏥 Dónde atenderse</h2>
      <p>Centros de salud, hospitales y guardias de El Colorado para consulta médica por dengue, zika y chikungunya.</p>
      <a href="<?= e(BASE_URL) ?>/" class="btn-hero">← Volver al mapa</a>
    </div>
    <div class="hero-kpis" data-js-hero-kpis></div>
  </section>

  <section class="panel" id="centros-salud">
    <a href="<?= e(BASE_URL) ?>/" class="btn-volver-inicio">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Volver Al Inicio
    </a>
    <h2>🏥 Centros de salud de El Colorado</h2>
    <p class="sub">Lista de centros de salud, hospitales y guardias para consulta médica. Ante síntomas de alarma, andá ya a la guardia más cercana.</p>

    <?php foreach (require __DIR__ . '/inc/centros-salud.php' as $c): ?>
    <article class="salud-card <?= ($c['estado'] ?? '') === 'guardia' ? 'guardia' : '' ?>">
      <header>
        <h3><?= e($c['nombre']) ?></h3>
        <span class="salud-estado <?= ($c['estado'] ?? '') === 'guardia' ? 'abierto' : 'cerrado' ?>">
          <?= ($c['estado'] ?? '') === 'guardia' ? 'Guardia 24h' : 'Consultorio' ?>
        </span>
      </header>
      <p class="salud-dato"><?= e($c['direccion']) ?></p>
      <p class="salud-dato"><small>🕒 <?= e($c['horario_txt']) ?></small></p>
      <?php foreach ($c['telefonos'] as [$etq, $ver, $marcar]): ?>
      <div class="salud-acciones">
        <a class="salud-btn salud-llamar" href="tel:<?= e($marcar) ?>">📞 <?= e($ver) ?></a>
      </div>
      <?php endforeach; ?>
    </article>
    <?php endforeach; ?>

    <p class="salud-pie">
      🚨 Ante síntomas de alarma (fiebre alta, dolor abdominal intenso, vómitos que no paran, sangrado, somnolencia, dificultad para respirar),
      andá ya a la guardia del hospital más cercano o llamá al <a href="tel:107">107</a>.
    </p>
  </section>

</main>

<?php require __DIR__ . '/inc/footer.php'; ?>

<script>
  window.BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= e(BASE_URL) ?>/assets/js/clima.js?v=20260925-vivo"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/app.js?v=20261112-redisenio"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/offline.js?v=20261111-app"></script>
</body>
</html>
