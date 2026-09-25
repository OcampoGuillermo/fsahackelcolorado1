# CaszaMosqui — Instalación y puesta en marcha (XAMPP)

## Instalación normal

1. Copiá la carpeta a `C:\xampp\htdocs\formosahack-2026` o mantené el enlace/junction existente.
2. Copiá `inc/config.example.php` como `inc/config.php`.
3. Ajustá la conexión MySQL/MariaDB y, si querés IA, `CLAUDE_API_KEY` **solo en `inc/config.php`**.
4. Iniciá Apache y MySQL desde XAMPP.
5. Recreá la base con:

```bat
C:\xampp\php\php.exe scripts\setup.php
```

6. Abrí `http://localhost/formosahack-2026/`.

## Funciones disponibles

- Mapa vectorial con zoom, pan, nombres de barrios y filtro por nivel de riesgo; se conservan el PNG/JPG originales como fallback.
- Semáforo por cantidad de criaderos sin controlar:
  - rojo: 5 o más;
  - amarillo: 3-4;
  - verde: 0-2.
- Reportes protegidos por sesión, paginados de a 4 por página.
- Formulario público de reporte, mapa, KPIs, prevención, cuestionario y test de síntomas.
- Comentarios por barrio.
- Chatbot local **Mosqui**, con base de conocimiento offline y respuesta opcional con IA si se configura `CLAUDE_API_KEY`.
- Modo demo para pruebas: `http://localhost/formosahack-2026/?demo=1` desactiva el límite diario durante la sesión del navegador.

## Instalador web alternativo

`instalar-db.php` existe como compatibilidad para una instalación local, pero es destructivo: borra y recrea las tablas. Solo permite peticiones desde `localhost` y exige `?ok=1`.

```text
http://localhost/formosahack-2026/instalar-db.php?ok=1
```

La opción recomendada sigue siendo `scripts/setup.php`. No se debe dejar el instalador expuesto en un servidor público.

## Configuración importante

- `inc/config.php` está ignorado por Git y nunca debe publicarse.
- Credenciales iniciales de la sección protegida:
  - usuario: `admin`;
  - contraseña: `admin123`.
- La contraseña se verifica mediante `AUTH_PASSWORD_HASH`.
- Para cambiar la contraseña, generá un hash nuevo y reemplazá el valor en `inc/config.php`.

## Problemas frecuentes

- **Mapa vacío:** verificá Apache/MySQL y ejecutá `scripts/setup.php`.
- **Chat sin IA:** es normal si `CLAUDE_API_KEY` está vacía; la base local sigue funcionando sin internet.
- **Estilos viejos:** hacé `Ctrl+F5` para recargar CSS/JS.
- **La sección Reportes pide login:** ingresá con las credenciales configuradas arriba.
