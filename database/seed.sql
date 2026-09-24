-- ============================================================
-- CaszaMosqui — Datos de ejemplo (seed)
-- FormosaHack 2026 · El Colorado, Formosa
-- ============================================================
USE formosahack;

-- Tipos de criadero (clasificación oficial dengue)
INSERT INTO tipos_criadero (nombre, color, icono) VALUES
  ('Recipientes con agua',    '#dc2626', '🪣'),
  ('Neumáticos',              '#d97706', '🛞'),
  ('Piletas y tanques',       '#2563eb', '🛁'),
  ('Zanjas y agua estancada', '#0891b2', '💧'),
  ('Basurales y residuos',    '#16a34a', '🗑️'),
  ('Botellas y cacharros',    '#9333ea', '🍾');

-- Barrios de El Colorado (mapa esquemático, posiciones en %)
INSERT INTO barrios (nombre, localidad, x, y, poblacion) VALUES
  ('Centro',              'El Colorado', 50, 52, 3200),
  ('San Cayetano',        'El Colorado', 62, 35, 1850),
  ('25 de Mayo',          'El Colorado', 38, 30, 1400),
  ('San Antonio',         'El Colorado', 28, 62, 1100),
  ('Kennedy',             'El Colorado', 70, 70, 1600),
  ('9 de Julio',          'El Colorado', 45, 78, 980),
  ('Villa Primavera',     'El Colorado', 20, 20, 720),
  ('Santa María',         'El Colorado', 78, 22, 640),
  ('La Costanera',        'El Colorado', 58, 12, 850),
  ('Villa Obrera',        'El Colorado', 35, 88, 1200);

-- Reportes de criaderos (variedad de estados y barrios)
INSERT INTO reportes (tipo_id, barrio_id, titulo, descripcion, referencia, estado, votos) VALUES
  (1, 1, 'Baldes con agua en obra abandonada',
   'Baldes y tachos acumulados con agua de lluvia en el galpón en construcción de la cuadra.', 'Calle San Martín al 300', 'pendiente', 14),
  (1, 3, 'Tachos sin tapa en casa en obra', 
   'Varios recipientes descubiertos en un patio en obra, se acumula agua desde hace días.', 'Av. 25 de Mayo y Rivadavia', 'pendiente', 9),
  (2, 5, 'Neumáticos apilados en taller', 
   'Más de 20 cubiertas usadas con agua estancada detrás del taller mecánico.', 'Ruta 81 km 1164', 'pendiente', 22),
  (2, 8, 'Gomería acumula cubiertas al aire libre',
   'Neumáticos apilados sin cobertura, con agua de las últimas lluvias.', 'Bv. San Martín y Sarmiento', 'verificado', 11),
  (3, 2, 'Pileta sin tratamiento en club',
   'Pileta del club con agua estancada verde, no le dan mantenimiento hace semanas.', 'Club Sportivo, 25 de Mayo', 'pendiente', 18),
  (3, 5, 'Tanque descubierto en vivienda',
   'Tanque de agua sin tapa en vivienda familiar, acceso directo de mosquitos.', 'Barrio Kennedy, manzana 4', 'verificado', 7),
  (4, 4, 'Zanja obstruida con agua estancada',
   'Desagüe tapado genera charco permanente en la esquina, imposible circular sin mojarse.', 'San Antonio, calle 8 y 9', 'pendiente', 31),
  (4, 1, 'Charco en terreno descampado',
   'Agua acumulada en bajo nivel detrás de la plaza, no evacúa.', 'Centro, frente a plaza', 'pendiente', 12),
  (5, 9, 'Acumulación de residuos en la Costanera',
   'Puntos de basura sin recolección sobre la costanera, atraen mosquitos y roedores.', 'Costanera, sector sur', 'pendiente', 27),
  (5, 6, 'Basural improvisado en predio',
   'Desechos acumulados detrás del supermercado, se junta agua en envases y bolsas.', '9 de Julio, manzana 12', 'controlado', 8),
  (1, 10, 'Macetas y platitos con agua en vereda',
   'Platitos de macetas en comercios de la esquina acumulan agua por la falta de limpieza.', 'Villa Obrera, esquina céntrica', 'pendiente', 5),
  (6, 7, 'Botellas y latas en terreno baldío',
   'Gran cantidad de botellas y latas con agua en un lote sin cerrar.', 'Villa Primavera, lote 7', 'verificado', 6),
  (6, 2, 'Cacharros en patio vecinal',
   'Electrodomésticos en desuso y objetos de plástico acumulando agua en un patio compartido.', 'San Cayetano, manzana 2', 'controlado', 3),
  (4, 1, 'Zanja a cielo abierto frente a escuela',
   'Zanja sin entubar frente a la escuela primaria N° 63, agua estancada permanente.', 'Centro, calle Reconquista', 'pendiente', 40);

-- Nota: la fórmula del riesgo y los KPIs se calculan en la API (ver api/index.php)