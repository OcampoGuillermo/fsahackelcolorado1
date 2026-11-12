<?php
/**
 * CaszaMosqui — Mapa de riesgo (página principal)
 * FormosaHack 2026 · Desafío: enfermedades transmitidas por mosquitos
 * Los datos se cargan desde la API /api/?route=... con fetch().
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

require __DIR__ . '/inc/cabecera.php';
?>

  <!-- HERO -->
  <section class="hero">
    <div>
      <h2>¿Dónde se crían los mosquitos?</h2>
      <p>Detectá y reportá los criaderos de tu barrio. Entre todos evitamos el
         <strong>dengue, zika y chikungunya</strong>. 📍 Un reporte = un criadero menos.</p>
      <a href="<?= e(BASE_URL) ?>/reportar.php" class="btn-hero">Reportar un criadero →</a>
    </div>
    <div class="hero-kpis" data-js-hero-kpis></div>
  </section>

  <!-- ALERTA CLIMÁTICA -->
  <section class="panel" id="clima">
    <p class="loading">Cargando datos climáticos…</p>
  </section>

  <?php require __DIR__ . '/inc/mapa.php'; ?>

  <?php require __DIR__ . '/inc/kpis.php'; ?>

  <!-- TIPOS MÁS COMUNES -->
  <section class="panel">
    <a href="<?= e(BASE_URL) ?>/" class="btn-volver-inicio">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Volver Al Inicio
    </a>
    <h2>🐞 Criaderos más reportados</h2>
    <div class="sectores" data-js-tipos>
      <p class="loading">Cargando…</p>
    </div>
  </section>

<?php require __DIR__ . '/inc/scripts.php'; ?>
