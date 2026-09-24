# 🖥️ DEMO — CaszaMosqui

> **Guion de presentación — FormosaHack 2026** (3 a 5 minutos).

## 1. El problema (30 seg) 🎬
> "En los barrios existen condiciones que favorecen la proliferación del mosquito
> Aedes aegypti: recipientes, neumáticos, piletas, zanjas, basurales. Pero no hay
> información accesible ni organizada sobre dónde están esos criaderos, y sin esa
> información la comunidad no puede participar de la prevención del dengue."

## 2. La solución (30 seg) 💡
> "CaszaMosqui es una plataforma comunitaria donde cada vecino reporta criaderos;
> la plataforma los organiza y los muestra en un **mapa de riesgo por barrio** con
> semáforo, y guía con acciones de prevención. Cada reporte = un criadero menos."

## 3. Recorrido en vivo (2 min) — ¡lo más importante! ⭐

1. **Mapa de riesgo:** mostrar los barrios con colores (alto/medio/bajo) y el
   número de criaderos activos. **Hacer clic en un barrio** → lista filtrada.
2. **KPIs:** totales reportados / sin controlar / verificados / controlados y %
   de controlados (conectados a la base real).
3. **Criaderos más reportados:** ranking por tipo (iconos y colores).
4. **Crear un reporte en vivo:** elegir tipo + barrio + descripción →
   aparece al instante en el mapa y en la lista (**POST**).
5. **Gestionar:** verificar → controlar el reporte (los KPIs y el mapa cambian
   en vivo) (**PATCH**).
6. **Votar:** 👍 suma votos (participación).
7. **Prevención y quiz:** mostrar la guía y responder el quiz (concientización).

## 4. Valor e impacto (1 min) 📈
- Información **accesible y comprensible** (mapa + semáforo + guía).
- **Participación real de la comunidad** (reportar, votar, aprender).
- Cero costos de infraestructura: funciona en una PC local con XAMPP.
- Escalable: se adapta a otras localidades agregando barrios en la BD.

## 5. Cierre (15 seg) 🙌
> "CaszaMosqui: la comunidad identifica, el mapa organiza y juntos prevenimos."

## Cómo levantar el proyecto desde cero (para el jurado)

```bash
# 1. Copiar la carpeta a C:\xampp\htdocs\formosahack-2026
# 2. Con Apache y MySQL corriendo:
C:\xampp\php\php.exe scripts\setup.php
# 3. Abrir: http://localhost/formosahack-2026/
#    API de ejemplo: http://localhost/formosahack-2026/api/?route=stats
```

## Capturas de pantalla

(agregar capturas o gif de la demo aquí)

---

*Equipo CaszaMosqui — FormosaHack 2026*