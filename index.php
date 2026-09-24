<?php
/**
 * CaszaMosqui — Dashboard principal
 * FormosaHack 2026 · Desafío: enfermedades transmitidas por mosquitos
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
      <span class="logo">🦟</span>
      <div>
        <h1><?= e(APP_NAME) ?></h1>
        <p><?= e(APP_TAGLINE) ?></p>
      </div>
    </div>
    <nav>
      <a href="#" data-nav="mapa" class="active">Mapa de riesgo</a>
      <a href="#" data-nav="reportes">Reportes</a>
      <a href="#" data-nav="nuevo">+ Reportar</a>
      <a href="#" data-nav="prevencion">Prevención</a>
      <a href="#" data-nav="quiz">Quiz</a>
    </nav>
  </div>
</header>

<main class="container">

  <!-- HERO -->
  <section class="hero">
    <div>
      <h2>¿Dónde se crían los mosquitos?</h2>
      <p>Detectá y reportá los criaderos de tu barrio. Entre todos evitamos el
         <strong>dengue, zika y chikungunya</strong>. 📍 Un reporte = un criadero menos.</p>
      <a href="#" data-nav="nuevo" class="btn-hero">Reportar un criadero →</a>
    </div>
    <div class="hero-kpis" data-js-hero-kpis></div>
  </section>

  <!-- MAPA DE RIESGO -->
  <section class="panel" id="mapa">
    <div class="panel-head">
      <h2>🗺️ Mapa de riesgo por barrio</h2>
      <div class="leyenda">
        <span><i class="dot alto"></i> Riesgo alto</span>
        <span><i class="dot medio"></i> Riesgo medio</span>
        <span><i class="dot bajo"></i> Riesgo bajo</span>
      </div>
    </div>
    <div class="mapa-filtros" data-mapa-filtros>
      <button data-nivel="todos" class="activo">Todos</button>
      <button data-nivel="alto">Riesgo alto</button>
      <button data-nivel="medio">Riesgo medio</button>
      <button data-nivel="bajo">Riesgo bajo</button>
    </div>
    <div class="mapa" data-js-mapa aria-label="Mapa esquemático de riesgo por barrio">
      <p class="loading">Cargando mapa…</p>
    </div>
    <p class="mapa-nota">Mapa del plano oficial generado en vivo según los reportes de la comunidad.
       <strong>Clic en un barrio</strong> para ver sus criaderos.</p>
  </section>

  <!-- KPIs -->
  <section class="kpis" aria-label="Indicadores">
    <article class="kpi" data-kpi="total"><strong>—</strong><span>🦟 Criaderos reportados</span></article>
    <article class="kpi" data-kpi="pendiente"><strong>—</strong><span>⏳ Sin controlar</span></article>
    <article class="kpi" data-kpi="verificado"><strong>—</strong><span>🔎 Verificados</span></article>
    <article class="kpi" data-kpi="controlado"><strong>—</strong><span>✅ Controlados</span></article>
  </section>

  <!-- TIPOS MÁS COMUNES -->
  <section class="panel">
    <h2>🐞 Criaderos más reportados</h2>
    <div class="sectores" data-js-tipos>
      <p class="loading">Cargando…</p>
    </div>
  </section>

  <!-- REPORTES -->
  <section class="panel" id="reportes">
    <div class="panel-head">
      <h2>📋 Criaderos reportados por la comunidad</h2>
      <form class="filtros" data-js-filtros>
        <select name="tipo" aria-label="Filtrar por tipo">
          <option value="">Todos los tipos</option>
        </select>
        <select name="barrio" aria-label="Filtrar por barrio">
          <option value="">Todos los barrios</option>
        </select>
        <select name="estado" aria-label="Filtrar por estado">
          <option value="">Todos los estados</option>
          <option value="pendiente">Sin controlar</option>
          <option value="verificado">Verificado</option>
          <option value="controlado">Controlado</option>
        </select>
        <input type="search" name="q" placeholder="Buscar…" aria-label="Buscar">
        <button type="button" data-js-reset class="btn-ghost">Limpiar</button>
      </form>
    </div>
    <div data-js-reportes>
      <p class="loading">Cargando reportes…</p>
    </div>
  </section>

  <!-- FORMULARIO -->
  <section class="panel" id="nuevo">
    <h2>📢 Reportar un criadero</h2>
    <p class="sub">Elegí el tipo de situación y el barrio. Tu reporte se suma al mapa de riesgo al instante.</p>
    <form data-js-form>
      <div class="grid-3">
        <label>
          Tipo de criadero <span class="req">*</span>
          <select name="tipo_id" required></select>
        </label>
        <label>
          Barrio <span class="req">*</span>
          <select name="barrio_id" required></select>
        </label>
        <label>
          Referencia
          <input type="text" name="referencia" placeholder="Calle, plaza, escuela…">
        </label>
      </div>
      <label>
        Título <span class="req">*</span>
        <input type="text" name="titulo" maxlength="120" required placeholder="Ej.: Baldes con agua en obra abandonada">
      </label>
      <label>
        Descripción <span class="req">*</span>
        <textarea name="descripcion" rows="4" required placeholder="Contanos qué viste y dónde… puede haber larvas o mosquitos."></textarea>
      </label>
      <button type="submit">Reportar criadero</button>
      <p class="form-msg" data-js-form-msg aria-live="polite"></p>
    </form>
  </section>

  <!-- PREVENCIÓN -->
  <section class="panel" id="prevencion">
    <h2>📖 Guía rápida de prevención</h2>
    <p class="sub">El mosquito <strong>Aedes aegypti</strong> se cría en <strong>agua limpia y estancada</strong> cerca de casas. Eliminá sus criaderos:</p>
    <div class="tips">
      <article class="tip">🪣 <h3>Descacharrá</h3><p>Tirá latas, botellas, baldes y cacharros que junten agua.</p></article>
      <article class="tip">🛢️ <h3>Tapá los tanques</h3><p>Tanques y recipientes grandes siempre con tapa bien ajustada.</p></article>
      <article class="tip">🛞 <h3>Neumáticos</h3><p>Guardalos bajo techo o perforalos para que no junten agua.</p></article>
      <article class="tip">💧 <h3>Vaciá y limpiá</h3><p>Platitos de macetas, bebederos y piletas: semanal, sin agua estancada.</p></article>
      <article class="tip">🧹 <h3>Limpiá canaletas</h3><p>Hojas y tierra en desagües dejan charcos ideales para larvas.</p></article>
      <article class="tip">🛡️ <h3>Protegé tu casa</h3><p>Mosquiteros, espirales, repelente y ropa clara en horas de actividad.</p></article>
    </div>
    <p class="mapa-nota">🚨 <strong>Síntomas de alarma (dengue):</strong> fiebre alta, dolor detrás de los ojos, dolor muscular y articular, sarpullido. Ante estos síntomas, <strong>no te automediques</strong>: consultá al centro de salud más cercano.</p>
  </section>

  <!-- QUIZ -->
  <section class="panel" id="quiz">
    <h2>🎯 ¿Cuánto sabés sobre prevención?</h2>
    <p class="sub">Respondé el quiz y recibí tu veredicto. Compartilo con tu barrio para frenar al mosquito. 🦟</p>
    <div data-js-quiz>
      <p class="loading">Cargando quiz…</p>
    </div>
  </section>

</main>

<footer>
  <div class="container">
    <p>🦟 <strong>CaszaMosqui</strong> · Equipo FormosaHack 2026 · HTML + PHP + CSS + JS + MySQL ·
       Desafío: prevenir enfermedades transmitidas por mosquitos</p>
  </div>
</footer>

<script>
  window.BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>
<script src="<?= e(BASE_URL) ?>/assets/js/app.js"></script>
</body>
</html>