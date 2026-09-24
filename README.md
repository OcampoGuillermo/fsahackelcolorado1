# 🏆 FormosaHack 2026 — Base de Proyecto (HTML + PHP + CSS + JS + MySQL)

> **Repositorio GitHub:** `github.com/OcampoGuillermo/fsahackelcolorado1` (rama `main`)
> Proyecto base del equipo para el **Ultra Hackatón de 24 Horas FormosaHack 2026**.
> Stack: **HTML + CSS + JavaScript (vanilla) + PHP 8 + MySQL (MariaDB)**, servido con XAMPP (Apache).

## 🧩 Qué incluye este scaffold

| Módulo | Descripción |
|---|---|
| `index.php` | Dashboard principal: KPIs, filtros, tabla de reportes y formulario de alta |
| `api/index.php` | **API REST en JSON** con front controller (`categorias`, `reportes`, `stats`) |
| `inc/` | Conexión **PDO** a MySQL, config, helpers (`json_response`, `e()`, etc.) |
| `database/` | `schema.sql` (estructura) + `seed.sql` (datos de ejemplo realistas) |
| `scripts/setup.php` | Crea la BD `formosahack`, tablas y datos en **un solo comando** |
| `docs/` | Plantillas de documentación para el jurado |

La funcionalidad demo es un **Sistema de Reportes Comunitarios** cuyos sectores coinciden con los ejes del desafío (salud, educación, producción y ambiente, seguridad, economía), para pivotar rápido al desafío asignado.

## 🚀 Puesta en marcha (local con XAMPP)

### 1. Requisitos
- **XAMPP** instalado con Apache + MySQL (MariaDB) corriendo.

### 2. Copiar el proyecto a htdocs
```
C:\xampp\htdocs\formosahack-2026\
```
(o crear un acceso directo/symlink hacia esta carpeta).

### 3. Crear la base de datos (1 comando, con PHP)
```bash
C:\xampp\php\php.exe scripts\setup.php
```
Esto crea la BD `formosahack`, las tablas `categorias` y `reportes`, y carga datos de ejemplo.

> Configuración de BD: `inc/config.php` (en XAMPP el usuario es `root` sin contraseña por defecto).

### 4. Abrir la app
```
http://localhost/formosahack-2026/
```

### Plan B sin Apache
```bash
C:\xampp\php\php.exe -S localhost:8000 -t .
```
→ `http://localhost:8000/`

## 🔌 API REST (formato JSON)

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/?route=categorias` | Lista de sectores |
| GET | `/api/?route=reportes` | Todos los reportes (`?categoria=`, `?estado=`, `?q=`) |
| GET | `/api/?route=reportes/{id}` | Un reporte por id |
| POST | `/api/?route=reportes` | Crear reporte (body JSON) |
| PATCH | `/api/?route=reportes/{id}` | Cambiar estado (`pendiente`/`en_proceso`/`resuelto`) |
| DELETE | `/api/?route=reportes/{id}` | Eliminar |
| GET | `/api/?route=stats` | KPIs: totales, por sector y por estado |

Ejemplo de creación:
```bash
curl -X POST "http://localhost/formosahack-2026/api/?route=reportes" ^
  -H "Content-Type: application/json" ^
  -d "{\"categoria_id\":2,\"titulo\":\"Barrio nuevo\",\"descripcion\":\"Falta iluminación\",\"ubicacion\":\"Formosa\"}"
```

## 📁 Estructura
```
formosahack-2026/
├── README.md
├── docs/               DESAFIO.md · SOLUCION.md · DEMO.md
├── index.php           dashboard
├── api/index.php       API REST
├── inc/                config.php · config.example.php · db.php · helpers.php
├── assets/             css/styles.css · js/app.js
├── database/           schema.sql · seed.sql
└── scripts/            setup.php
```

## 🛠️ Guía rápida para pivotar al desafío asignado
1. Copiar un sector → `database/seed.sql` y ajustar textos en `inc/config.php`.
2. Crear tablas nuevas en `database/schema.sql` (o nuevas secciones).
3. Agregar endpoints en `api/index.php` y llamadas en `assets/js/app.js`.
4. Actualizar `docs/DESAFIO.md`, `docs/SOLUCION.md` y `docs/DEMO.md`.
5. Commit + push a GitHub ✅

## ⚠️ Seguridad base (mínimo demostrable)
- Consultas **PDO preparadas** en toda la API (anti inyección SQL).
- Salida escapada con `e()` (anti XSS) en todas las vistas.
- `inc/config.php` **no se sube** a Git (ver `.gitignore`); se versiona `config.example.php`.
- Apache: `RedirectMatch 403` sobre `inc/`, `database/` y `scripts/`.