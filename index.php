<?php
/**
 * Dashboard principal — FormosaHack 2026
 * Los datos se cargan desde la API /api/?route=... con fetch().
 */
require_once __DIR__ . '/inc/helpers.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e(APP_NAME) ?> · <?= e(APP_TAGLINE) ?></title>
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/styles.css">
</head>
<body>

<header class="topbar">
  <div class="container">
    <div class="brand">
      <span class="logo">🏆</span>
      <div>
        <h1><?= e(APP_NAME) ?></h1>
        <p><?= e(APP_TAGLINE) ?></p>
      </div>
    </div>
    <nav>
      <a href="#" data-js-nav="dashboard" class="active">Dashboard</a>
      <a href="#" data-js-nav="reportes">Reportes</a>
      <a href="#" data-js-nav="nuevo">+ Nuevo</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- KPIs -->
  <section class="kpis" aria-label="Indicadores">
    <article class="kpi" data-js-kpi="total"><strong>—</strong><span>Reportes totales</span></article>
    <article class="kpi" data-js-kpi="pendiente"><strong>—</strong><span>Pendientes</span></article>
    <article class="kpi" data-js-kpi="en_proceso"><strong>—</strong><span>En proceso</span></article>
    <article class="kpi" data-js-kpi="resuelto"><strong>—</strong><span>Resueltos</span></article>
  </section>

  <!-- Sectores -->
  <section class="panel">
    <h2>Problemáticas por sector</h2>
    <div class="sectores" data-js-sectores>
      <p class="loading">Cargando sectores…</p>
    </div>
  </section>

  <!-- Reportes -->
  <section class="panel">
    <div class="panel-head">
      <h2>Reportes recientes</h2>
      <form class="filtros" data-js-filtros>
        <select name="categoria" aria-label="Filtrar por sector">
          <option value="">Todos los sectores</option>
        </select>
        <select name="estado" aria-label="Filtrar por estado">
          <option value="">Todos los estados</option>
          <option value="pendiente">Pendiente</option>
          <option value="en_proceso">En proceso</option>
          <option value="resuelto">Resuelto</option>
        </select>
        <input type="search" name="q" placeholder="Buscar…" aria-label="Buscar">
        <button type="button" data-js-reset class="btn-ghost">Limpiar</button>
      </form>
    </div>
    <div id="reportes" data-js-reportes>
      <p class="loading">Cargando reportes…</p>
    </div>
  </section>

  <!-- Formulario nuevo reporte -->
  <section class="panel" id="nuevo">
    <h2>Reportar una problemática</h2>
    <form data-js-form>
      <div class="grid-2">
        <label>
          Sector
          <select name="categoria_id" required></select>
        </label>
        <label>
          Ubicación
          <input type="text" name="ubicacion" value="Formosa" placeholder="Barrio / Localidad">
        </label>
      </div>
      <label>
        Título <span class="req">*</span>
        <input type="text" name="titulo" maxlength="120" required placeholder="Breve resumen del problema">
      </label>
      <label>
        Descripción <span class="req">*</span>
        <textarea name="descripcion" rows="4" required placeholder="Detallá la situación…"></textarea>
      </label>
      <button type="submit">Publicar reporte</button>
      <p class="form-msg" data-js-form-msg aria-live="polite"></p>
    </form>
  </section>

</main>

<footer>
  <div class="container">
    <p>Equipo FormosaHack 2026 · HTML + PHP + CSS + JS + MySQL · 🏆 Ultra Hackatón 24 horas</p>
  </div>
</footer>

<script>
  window.BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= e(BASE_URL) ?>/assets/js/app.js"></script>
</body>
</html>