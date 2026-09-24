# 🧠 SOLUCION

> **Propuesta técnica — FormosaHack 2026.**

## La propuesta

- **Nombre del producto:** 
- **Qué hace (value proposition en 1 frase):** 
- **Cómo lo usa la comunidad (flujo básico):**
  1. 
  2. 
  3. 

## Arquitectura

- **Frontend:** HTML5 + CSS3 + JavaScript vanilla (fetch a la API)
- **Backend:** PHP 8 (API REST JSON en `api/index.php`)
- **Base de datos:** MySQL/MariaDB (PDO, consultas preparadas)
- **Despliegue:** Local con XAMPP (Apache) + GitHub

### Diagrama (texto)

```
Navegador (index.php)
   │  fetch() → JSON
   ▼
api/index.php  (front controller, ?route=...)
   │   PDO preparado
   ▼
MySQL (formosahack: categorias, reportes)
```

## Tablas principales

| Tabla | Campos | Uso |
|---|---|---|
| `categorias` | id, nombre, color | Sectores del desafío |
| `reportes` | id, categoria_id, titulo, descripcion, ubicacion, estado, votos, creado_en | Reportes comunitarios |

## Decisiones clave tomadas (registrarlas en el momento)

| Decisión | Opciones | Elegida | Por qué |
|---|---|---|---|
| | | | |

## Riesgos y mitigación

| Riesgo | Mitigación |
|---|---|
| Internet del evento falla | Stack 100% local, sin CDNs ni APIs externas |
| MySQL no arranca | Plan B: datos en archivo JSON + `php -S` |
| Demo en vivo falla | Screenshots/gif grabado de respaldo |

---

*Equipo FormosaHack 2026*