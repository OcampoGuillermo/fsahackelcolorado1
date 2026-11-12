<?php
/**
 * CaszaMosqui — Mapa de riesgo (página principal)
 * FormosaHack 2026 · Desafío: enfermedades transmitidas por mosquitos
 */
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/header.php';

$page_title = 'Mapa de riesgo';
$active_page = 'mapa';

$reportes_logueado = reportes_logueado();
$reportes_error     = consumir_error_reportes();
$reportes_csrf      = reportes_csrf_token();
$reportes_usuario   = usuario_reportes();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
?>
<main class="container">

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

  <!-- MAPA DE RIESGO -->
  <section class="panel" id="mapa">
    <a href="<?= e(BASE_URL) ?>/" class="btn-volver-inicio">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Volver Al Inicio
    </a>
    <div class="panel-head">
      <h2>🗺️ Mapa de riesgo por barrio</h2>
      <div class="mapa-filtros" role="group" aria-label="Filtrar barrios por nivel de riesgo">
        <button type="button" data-nivel="todos" class="activo">Todos</button>
        <button type="button" data-nivel="alto"><i class="dot alto"></i> Alto</button>
        <button type="button" data-nivel="medio"><i class="dot medio"></i> Medio</button>
        <button type="button" data-nivel="bajo"><i class="dot bajo"></i> Bajo</button>
      </div>
      <div class="mapa-controles" role="group" aria-label="Controles de zoom del mapa">
        <button type="button" id="zoom-in" aria-label="Acercar">🔍+</button>
        <button type="button" id="zoom-out" aria-label="Alejar">🔍−</button>
        <button type="button" id="zoom-reset" aria-label="Restablecer vista">⌂</button>
      </div>
    </div>
    <div class="mapa-contenedor">
      <aside class="mapa-sidebar" aria-label="Lista de barrios">
        <h3>📍 Barrios</h3>
        <ul data-js-barra-barrios>
          <li><button type="button" data-barrio="todos" class="activo">Todos los barrios</button></li>
        </ul>
      </aside>
      <div class="mapa-wrapper">
        <div class="mapa-lienzo" data-js-mapa-lienzo>
          <img class="mapa-imagen" data-js-mapa-imagen
               src="<?= e(BASE_URL) ?>/assets/img/mapa-el-colorado.svg"
               alt="Plano de barrios de El Colorado" draggable="false" decoding="async"
               onerror="this.onerror=null;this.src='<?= e(BASE_URL) ?>/assets/img/mapa-el-colorado.png';">
          <div class="mapa-burbujas" data-js-mapa-burbujas aria-label="Barrios y nivel de riesgo"></div>
        </div>
      </div>
      <p class="mapa-nota">Plano oficial de barrios de El Colorado. Usá <strong>🔍+ / 🔍−</strong> o la rueda del mouse para zoom, arrastrá para moverte y <strong>⌂</strong> para volver. <strong>Clic en un barrio</strong> (lista o etiqueta) para centrar y ver sus criaderos. Semáforo: <strong>rojo 5+</strong> criaderos sin controlar, <strong>amarillo 3-4</strong>, <strong>verde 0-2</strong>.</p>
    </div>
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
    <a href="<?= e(BASE_URL) ?>/" class="btn-volver-inicio">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Volver Al Inicio
    </a>
    <h2>🐞 Criaderos más reportados</h2>
    <div class="sectores" data-js-tipos>
      <p class="loading">Cargando…</p>
    </div>
  </section>

  <!-- REPORTES (acceso autorizado) -->
  <section class="panel" id="reportes-resumen">
    <a href="<?= e(BASE_URL) ?>/reportar.php" class="btn-volver-inicio">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Ir a Reportes
    </a>
    <div class="panel-head">
      <h2>📋 Gestión de criaderos (acceso autorizado)</h2>
    </div>
    <?php if ($reportes_logueado): ?>
      <div class="reportes-sesion">
        <span>Sesión iniciada como <strong><?= e($reportes_usuario) ?></strong></span>
        <form method="post" action="<?= e(BASE_URL) ?>/reportes-auth.php" class="reportes-logout-form">
          <input type="hidden" name="accion" value="logout">
          <input type="hidden" name="csrf" value="<?= e($reportes_csrf) ?>">
          <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
      </div>
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
      <nav class="reportes-paginacion" data-js-paginacion aria-label="Paginación de reportes" hidden>
        <button type="button" class="reportes-pagina" data-pagina-anterior disabled>← Anterior</button>
        <div class="paginacion-numeros" data-js-paginacion-numeros></div>
        <button type="button" class="reportes-pagina" data-pagina-siguiente disabled>Siguiente →</button>
        <span class="paginacion-info" data-js-paginacion-info aria-live="polite"></span>
      </nav>
    <?php else: ?>
      <div class="reportes-login">
        <div class="reportes-login-icon" aria-hidden="true">🔒</div>
        <h2>Acceso a Reportes</h2>
        <p class="sub">Ingresá con tu usuario para consultar y gestionar los criaderos reportados por la comunidad.</p>
        <form method="post" action="<?= e(BASE_URL) ?>/reportes-auth.php" class="reportes-login-form">
          <input type="hidden" name="accion" value="login">
          <input type="hidden" name="csrf" value="<?= e($reportes_csrf) ?>">
          <label for="reportes-usuario">
            Usuario
            <input id="reportes-usuario" name="usuario" type="text" autocomplete="username" required autofocus>
          </label>
          <label for="reportes-contrasena">
            Contraseña
            <input id="reportes-contrasena" name="contrasena" type="password" autocomplete="current-password" required>
          </label>
          <?php if ($reportes_error): ?>
            <p class="reportes-login-error" role="alert"><?= e($reportes_error) ?></p>
          <?php endif; ?>
          <button type="submit" class="btn-login">Ingresar</button>
        </form>
        <p class="reportes-login-note">El acceso usa una sesión de PHP y se mantiene mientras la aplicación esté abierta.</p>
      </div>
    <?php endif; ?>
  </section>

</main>

<?php require __DIR__ . '/inc/footer.php'; ?>

<script>
  window.BASE_URL = <?= json_encode(BASE_URL) ?>;
  window.REPORTES_ACCESS = <?= $reportes_logueado ? 'true' : 'false' ?>;
</script>
<script src="<?= e(BASE_URL) ?>/assets/js/clima.js?v=20260925-vivo"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/calles.js"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/app.js?v=20261112-redisenio"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/leaflet.js"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/geo.js?v=20261019-gps"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/mapa-real.js?v=20261112-redisenio"></script>
<script src="<?= e(BASE_URL) ?>/assets/js/offline.js?v=20261111-app"></script>
</body>
</html>
