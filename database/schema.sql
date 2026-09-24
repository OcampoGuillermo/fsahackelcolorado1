-- ============================================================
-- FormosaHack 2026 — Esquema de base de datos
-- Motor: MySQL / MariaDB (XAMPP)
-- ============================================================

CREATE DATABASE IF NOT EXISTS formosahack
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE formosahack;

-- Re-ejecutable (idempotente): elimina y recrea tablas
DROP TABLE IF EXISTS reportes;
DROP TABLE IF EXISTS categorias;

-- Sectores del desafío (salud, educación, producción y ambiente,
-- seguridad, economía)
CREATE TABLE categorias (
  id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60)  NOT NULL UNIQUE,
  color  VARCHAR(7)   NOT NULL DEFAULT '#2563eb'
) ENGINE = InnoDB;

-- Reportes de la comunidad
CREATE TABLE reportes (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT UNSIGNED NOT NULL,
  titulo       VARCHAR(120) NOT NULL,
  descripcion  TEXT         NOT NULL,
  ubicacion    VARCHAR(120) NOT NULL DEFAULT 'Formosa',
  estado       ENUM('pendiente','en_proceso','resuelto') NOT NULL DEFAULT 'pendiente',
  votos        INT UNSIGNED NOT NULL DEFAULT 0,
  creado_en    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reporte_categoria FOREIGN KEY (categoria_id)
    REFERENCES categorias (id) ON DELETE CASCADE,
  INDEX idx_reporte_categoria (categoria_id),
  INDEX idx_reporte_estado (estado)
) ENGINE = InnoDB;