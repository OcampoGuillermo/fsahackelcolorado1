-- ============================================================
-- FormosaHack 2026 — Datos de ejemplo (seed)
-- ============================================================
USE formosahack;

INSERT INTO categorias (nombre, color) VALUES
  ('Salud',               '#dc2626'),
  ('Educación',           '#2563eb'),
  ('Producción y Ambiente','#16a34a'),
  ('Seguridad y Sociedad', '#d97706'),
  ('Economía',            '#9333ea');

INSERT INTO reportes (categoria_id, titulo, descripcion, ubicacion, estado, votos) VALUES
  (1, 'Demoras en el centro de salud del barrio San Martín',
   'Vecinos reportan turnos con más de 15 días de espera para consultas clínicas generales.',
   'Formosa - Barrio San Martín', 'pendiente', 34),
  (1, 'Necesidad de campaña de vacunación antigripal',
   'Adultos mayores de la zona oeste solicitan vacunadores móviles para temporada de invierno.',
   'Formosa - Zona Oeste', 'en_proceso', 21),
  (2, 'Conectividad insuficiente en escuela rural',
   'La escuela de la Colonia 26 tiene internet de baja calidad para plataformas educativas.',
   'Colonia 26 - Laguna de Lobos', 'pendiente', 58),
  (2, 'Faltan computadoras en laboratorio de informática',
   'El laboratorio funciona con 4 equipos para 28 alumnos en el turno tarde.',
   'Formosa - Escuela Técnica N°2', 'pendiente', 19),
  (3, 'Puntos de reciclado en plazas céntricas',
   'Pedido de contenedores diferenciados en la plaza San Martín y alrededores.',
   'Formosa - Plaza San Martín', 'en_proceso', 45),
  (3, 'Monitoreo del río para alerta de crecidas',
   'Productores rurales piden información en tiempo real del nivel del río Bermejo.',
   'Ruta 81 - Tramo oeste', 'pendiente', 30),
  (4, 'Iluminación deficiente en parada de colectivos',
   'Parada de la avenida Néstor Kirchner sin luz, riesgo para estudiantes que salen de noche.',
   'Formosa - Av. Néstor Kirchner', 'resuelto', 62),
  (4, 'Campaña de concientización sobre grooming',
   'Talleres en escuelas secundarias sobre seguridad en redes sociales.',
   'Formosa y Laguna Blanca', 'en_proceso', 27),
  (5, 'Feria de emprendedores locales',
   'Espacio semanal para productores y emprendedores formoseños en el paseo costero.',
   'Formosa - Paseo Costero', 'pendiente', 40),
  (5, 'Capacitación en finanzas digitales para comercios',
   'Comerciantes de El Colorado piden talleres de billeteras virtuales y facturación.',
   'El Colorado', 'en_proceso', 16),
  (3, 'Huertas comunitarias en barrios',
   'Proyecto de huertas en espacios públicos de los barrios Namqom y Lote 111.',
   'Formosa - Barrio Namqom', 'pendiente', 12),
  (2, 'Becas de conectividad para estudiantes del interior',
   'Estudiantes de Las Lomitas y Ibarreta necesitan acceso a datos móviles para cursar.',
   'Las Lomitas / Ibarreta', 'pendiente', 33);