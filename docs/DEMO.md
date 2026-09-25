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

1. **Alerta climática:** "La lluvia y el calor de estas dos semanas forman parte del contexto ambiental; la plataforma los informa sin mezclarlos en el semáforo de cada barrio". Mostrar el gráfico de lluvia.
2. **Mapa vectorial de riesgo sobre el plano oficial de El Colorado:** barrios con etiquetas, zoom, desplazamiento y número de criaderos sin controlar. Tocar **"Alto"** → quedan solo los barrios en rojo. **Clic en El Arco** → lista filtrada de ese barrio.
3. **KPIs:** totales reportados / sin controlar / verificados / controlados y % de controlados (conectados a la base real).
4. **Criaderos más reportados:** ranking por tipo (iconos y colores).
5. **Crear un reporte en vivo:** elegir tipo + **barrio** → en "referencia" aparecen las **calles reales del barrio** → aparece al instante en el mapa (**POST**). Tip: usar `?demo=1` para mostrar varios aportes durante la demo.
6. **Gestionar:** entrar a **Reportes** (`admin` / `admin123`), verificar → controlar el reporte; el número del barrio y los KPIs cambian en vivo (**PATCH**).
7. **Votar:** 👍 suma votos (participación).
8. **Prevención y cuestionario:** mostrar la guía visual y responder el cuestionario.
9. **Chatbot Mosqui:** abrir el chat flotante, probar una pregunta sobre dengue y mostrar que la base local funciona sin internet; la IA es opcional.

## 4. Valor e impacto (1 min) 📈
- **Alerta informativa:** muestra la lluvia y la temperatura reales de la semana como contexto ambiental, sin mezclar ese dato en el semáforo de cada barrio.
- Trabajamos sobre el **plano oficial** de El Colorado: 28 barrios y 147 calles reales.
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
#    Clima: http://localhost/formosahack-2026/api/clima.php
#    API de ejemplo: http://localhost/formosahack-2026/api/?route=stats
```

## Capturas de pantalla

(agregar capturas o gif de la demo aquí)

---

*Equipo CaszaMosqui — FormosaHack 2026*