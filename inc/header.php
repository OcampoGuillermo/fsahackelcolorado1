<?php
/**
 * Header compartido para todas las páginas de CaszaMosqui.
 * Uso: require __DIR__ . '/inc/header.php';
 * Requiere: BASE_URL, APP_NAME, APP_TAGLINE definidos en config.php
 * Opcional: $page_title, $active_page para resaltar navegación
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e(isset($page_title) ? $page_title : APP_NAME) ?> · <?= e(APP_TAGLINE) ?></title>
  <link rel="manifest" href="<?= e(BASE_URL) ?>/manifest.webmanifest">
  <meta name="theme-color" content="#0b3d2e">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="CaszaMosqui">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <link rel="icon" href="<?= e(BASE_URL) ?>/assets/img/app/favicon-48.png" type="image/png">
  <link rel="apple-touch-icon" href="<?= e(BASE_URL) ?>/assets/img/app/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/styles.css?v=20261112-redisenio">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/chatbot.css">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/clima.css?v=20260925-vivo">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/leaflet.css">
  <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/mapa-plano.css">
</head>
<body>

<header class="topbar">
  <div class="container">
    <div class="topbar-inner">
      <nav class="topbar-nav">
        <a href="<?= e(BASE_URL) ?>/" data-nav="mapa" <?= (isset($active_page) && $active_page === 'mapa') ? 'class="active"' : '' ?>>🗺️ Mapa de riesgo</a>
        <a href="<?= e(BASE_URL) ?>/reportar.php" data-nav="nuevo" <?= (isset($active_page) && $active_page === 'reportar') ? 'class="active"' : '' ?>>➕ Reportar</a>
        <a href="<?= e(BASE_URL) ?>/prevencion.php" data-nav="prevencion" <?= (isset($active_page) && $active_page === 'prevencion') ? 'class="active"' : '' ?>>🛡️ Prevención</a>
        <a href="<?= e(BASE_URL) ?>/cuestionario.php" data-nav="quiz" <?= (isset($active_page) && $active_page === 'cuestionario') ? 'class="active"' : '' ?>>🎯 Cuestionario</a>
        <a href="<?= e(BASE_URL) ?>/test-sintomas.php" data-nav="sintomas" <?= (isset($active_page) && $active_page === 'sintomas') ? 'class="active"' : '' ?>>🩺 Test de Síntomas</a>
        <a href="<?= e(BASE_URL) ?>/centros-salud.php" data-nav="centros" <?= (isset($active_page) && $active_page === 'centros') ? 'class="active"' : '' ?>>🏥 Centros de Salud</a>
      </nav>
      <div class="topbar-brand">
        <img src="<?= e(BASE_URL) ?>/assets/img/logo-casza.jpeg" alt="<?= e(APP_NAME) ?>" class="logo-center">
        <div class="brand-text">
          <h1><?= e(APP_NAME) ?></h1>
          <p><?= e(APP_TAGLINE) ?></p>
        </div>
      </div>
      <div class="topbar-mapa">
        <span class="mapa-chip">📍 Formosa</span>
      </div>
    </div>
  </div>
</header>
