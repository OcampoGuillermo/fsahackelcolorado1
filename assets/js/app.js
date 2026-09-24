/* ============================================================
   CaszaMosqui — app.js
   Consume la API REST (/api/?route=...) y renderiza el dashboard:
   mapa de riesgo, KPIs, reportes, formulario y quiz.
   ============================================================ */

const API = () => window.BASE_URL + '/api/';

/* ---------- Utilidades ---------- */
const $  = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

const ESCAPAR = (txt = '') =>
  String(txt).replace(/[&<>"']/g, (c) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
  }[c]));

async function api(route, options = {}) {
  const res = await fetch(API() + '?route=' + route, {
    headers: { 'Content-Type': 'application/json' },
    ...options,
  });
  const json = await res.json().catch(() => ({}));
  if (!res.ok || json.ok === false) throw new Error(json.error || 'Error en la API');
  return json.data;
}

function fechaRelativa(dt) {
  const ms = new Date(dt.replace(' ', 'T')) - new Date();
  const s = Math.round(Math.abs(ms) / 1000);
  if (s < 60) return 'hace un momento';
  const m = Math.round(s / 60);
  if (m < 60) return `hace ${m} min`;
  const h = Math.round(m / 60);
  if (h < 24) return `hace ${h} h`;
  return `hace ${Math.round(h / 24)} d`;
}

const ESTADOS = {
  pendiente: 'Sin controlar',
  verificado: 'Verificado',
  controlado: 'Controlado',
};

const NIVELES = {
  alto:  { color: '#dc2626', label: 'Riesgo alto' },
  medio: { color: '#d97706', label: 'Riesgo medio' },
  bajo:  { color: '#16a34a', label: 'Riesgo bajo' },
};

/* ---------- Estado global ---------- */
const state = {
  tipos: [],
  barrios: [],
  filtros: { tipo: '', barrio: '', estado: '', q: '' },
  quizIndex: 0,
  quizScore: 0,
  quizDone: false,
  mapaFiltroNivel: 'todos',
};

/* ---------- Límite diario (1 interacción por dispositivo) ---------- */
const LIMITE_KEY = 'casza_limite_diario';

function obtenerLimite() {
  try {
    const data = JSON.parse(localStorage.getItem(LIMITE_KEY) || '{}');
    const hoy = new Date().toISOString().split('T')[0];
    return data[hoy] || 0;
  } catch { return 0; }
}

function incrementarLimite() {
  try {
    const data = JSON.parse(localStorage.getItem(LIMITE_KEY) || '{}');
    const hoy = new Date().toISOString().split('T')[0];
    data[hoy] = (data[hoy] || 0) + 1;
    localStorage.setItem(LIMITE_KEY, JSON.stringify(data));
  } catch {}
}

function puedeInteractuar() {
  return obtenerLimite() === 0;
}

function actualizarEstadoLimite() {
  const bloqueado = !puedeInteractuar();
  $$('[data-js-form] button[type=submit]').forEach((b) => { b.disabled = bloqueado; });
  $$('[data-voto]').forEach((b) => { b.disabled = bloqueado; });
}

/* ---------- Quiz (concientización) ---------- */
const QUIZ = [
  {
    q: '¿Dónde se reproduce el mosquito del dengue (Aedes aegypti)?',
    opciones: ['En agua limpia y estancada', 'En agua sucia de cloaca', 'En pastizales secos', 'En la tierra de las macetas'],
    correcta: 0,
    expl: 'El Aedes aegypti se cría en recipientes con agua limpia y estancada cerca de las casas.',
  },
  {
    q: '¿Cada cuánto conviene revisar patios y «descacharrar»?',
    opciones: ['Una vez al año', 'Semanalmente', 'Cada 6 meses', 'Solo cuando hay casos'],
    correcta: 1,
    expl: 'Cada semana: vaciar y limpiar recipientes evita que las larvas completen su ciclo.',
  },
  {
    q: '¿Qué hacemos con los neumáticos viejos que juntan agua?',
    opciones: ['Tirarlos al patio', 'Llenarlos de agua a propósito', 'Guardarlos bajo techo o perforarlos', 'Enterrarlos en el jardín'],
    correcta: 2,
    expl: 'Bajo techo o perforados no acumulan agua: se elimina el criadero.',
  },
  {
    q: '¿Cuáles son síntomas típicos del dengue?',
    opciones: ['Tos y congestión nasal', 'Fiebre alta, dolor de cabeza y articulaciones', 'Solo cansancio', 'Pérdida de apetito únicamente'],
    correcta: 1,
    expl: 'Fiebre alta, dolor detrás de los ojos, muscular y articular, sarpullido: ¡a consultar!',
  },
  {
    q: 'Frente a fiebre y dolor, ¿qué se recomienda?',
    opciones: ['Tomar aspirina y esperar varios días', 'Automedicarse con antibióticos', 'No hacer nada', 'Consultar al centro de salud y NO automedicarse'],
    correcta: 3,
    expl: 'Nunca te automediques (la aspirina puede complicar el dengue). Consultá siempre a un profesional.',
  },
];

function renderQuiz() {
  const cont = $('[data-js-quiz]');
  if (!cont) return;

  if (state.quizDone) {
    const pct = Math.round((state.quizScore / QUIZ.length) * 100);
    const msg =
      state.quizScore >= 4
        ? '🏆 ¡Sos un/a experto/a en prevención! Compartí CaszaMosqui con tu barrio.'
        : state.quizScore === 3
          ? '💪 ¡Muy bien! Seguí aprendiendo y mirá la guía de prevención.'
          : '📖 Repasá la guía de prevención: cada reporte y cada casa limpia cuentan.';
    cont.innerHTML = `
      <div class="quiz-card">
        <h3>Resultado: ${state.quizScore}/${QUIZ.length} (${pct}%)</h3>
        <p>${msg}</p>
        <button data-js-quiz-reiniciar>Volver a intentar</button>
      </div>`;
    return;
  }

  const q = QUIZ[state.quizIndex];
  cont.innerHTML = `
    <div class="quiz-card">
      <div class="quiz-bar"><i style="width:${(state.quizIndex / QUIZ.length) * 100}%"></i></div>
      <h3>Pregunta ${state.quizIndex + 1} de ${QUIZ.length}: ${ESCAPAR(q.q)}</h3>
      <div class="quiz-opciones">
        ${q.opciones.map((op, i) =>
          `<button class="quiz-opcion" data-js-respuesta data-i="${i}">${ESCAPAR(op)}</button>`).join('')}
      </div>
      <p class="quiz-foot">🏆 ${state.quizScore} aciertos hasta ahora</p>
    </div>`;
}

function responderQuiz(sel) {
  const q = QUIZ[state.quizIndex];
  const correcta = q.correcta;
  const ok = sel.dataset.i == correcta;

  $$('[data-js-respuesta]', $('[data-js-quiz]')).forEach((b) => {
    b.disabled = true;
    if (b.dataset.i == correcta) b.classList.add('ok');
    else if (b === sel && !ok) b.classList.add('fail');
  });

  if (ok) state.quizScore++;
  const cont = $('[data-js-quiz]');
  const foot = $('.quiz-foot', cont);
  const expl = document.createElement('p');
  expl.innerHTML = `<span class="${ok ? 'ok' : 'err'} form-msg">${ok ? '✅ ¡Correcto!' : '❌ Incorrecto.'}</span>
                    💡 ${ESCAPAR(q.expl)}`;
  expl.style.marginTop = '10px';
  const nextBtn = document.createElement('button');
  nextBtn.textContent = state.quizIndex + 1 >= QUIZ.length ? 'Ver resultado' : 'Siguiente pregunta →';
  nextBtn.style.marginTop = '12px';
  nextBtn.addEventListener('click', () => {
    state.quizIndex++;
    if (state.quizIndex >= QUIZ.length) state.quizDone = true;
    renderQuiz();
  });
  expl.appendChild(nextBtn);
  foot.after(expl);
}

/* ---------- Cargar opciones ---------- */
async function cargarCatalogos() {
  const [tipos, barrios] = await Promise.all([api('tipos'), api('barrios')]);
  state.tipos = tipos;
  state.barrios = barrios;

  const filtroTipo = $('[data-js-filtros] select[name=tipo]');
  const filtroBarrio = $('[data-js-filtros] select[name=barrio]');
  const formTipo = $('[data-js-form] select[name=tipo_id]');
  const formBarrio = $('[data-js-form] select[name=barrio_id]');

  const optsTipo = tipos.map((t) => `<option value="${t.id}">${ESCAPAR(t.icono)} ${ESCAPAR(t.nombre)}</option>`).join('');
  const optsBarrio = barrios.map((b) => `<option value="${b.id}">${ESCAPAR(b.nombre)}</option>`).join('');

  if (filtroTipo) filtroTipo.innerHTML = '<option value="">Todos los tipos</option>' + optsTipo;
  if (filtroBarrio) filtroBarrio.innerHTML = '<option value="">Todos los barrios</option>' + optsBarrio;
  if (formTipo) formTipo.innerHTML = '<option value="">— Elegir tipo —</option>' + optsTipo;
  if (formBarrio) formBarrio.innerHTML = '<option value="">— Elegir barrio —</option>' + optsBarrio;

  // Sidebar de barrios en el mapa
  renderBarriosSidebar(barrios);
}

/* ---------- Sidebar de barrios ---------- */
function renderBarriosSidebar(barrios) {
  const ul = $('[data-js-barra-barrios]');
  if (!ul) return;

  const items = barrios.map((b) => {
    const n = b.nombre.length > 22 ? b.nombre.substring(0, 21) + '…' : b.nombre;
    const idx = b.indice !== undefined ? b.indice : 0;
    const nivel = idx >= 4 ? 'alto' : (idx >= 2 ? 'medio' : 'bajo');
    const cls = idx > 0 ? nivel : 'none';
    return `<li>
      <button type="button" data-barrio="${b.id}" data-x="${b.x}" data-y="${b.y}" title="${ESCAPAR(b.nombre)}">
        <span>${ESCAPAR(n)}</span>
        <span class="barrio-indice ${cls}">${idx}</span>
      </button>
    </li>`;
  }).join('');

  ul.innerHTML = `<li><button type="button" data-barrio="todos" class="activo">Todos los barrios</button></li>` + items;
}

/* ---------- Stats + mapa + KPIs ---------- */
const NIVEL_LABEL = { alto: '🔴', medio: '🟠', bajo: '🟢' };

async function cargarStats() {
  const stats = await api('stats');
  const porEstado = Object.fromEntries(stats.por_estado.map((s) => [s.estado, s.total]));

  $('[data-kpi=total] strong').textContent = stats.total;
  $('[data-kpi=pendiente] strong').textContent = porEstado.pendiente || 0;
  $('[data-kpi=verificado] strong').textContent = porEstado.verificado || 0;
  $('[data-kpi=controlado] strong').textContent = porEstado.controlado || 0;

  // Hero KPIs
  const hero = $('[data-js-hero-kpis]');
  if (hero) {
    hero.innerHTML = `
      <div class="hero-kpi"><strong>${stats.total}</strong><span>criaderos<br>reportados</span></div>
      <div class="hero-kpi"><strong>${stats.controlados_pct}%</strong><span>de reportes<br>controlados</span></div>`;
  }

  // Tipos más comunes
  const tipos = $('[data-js-tipos]');
  if (tipos) {
    tipos.innerHTML = stats.por_tipo
      .map((t) => `
        <div class="sector" style="--color:${ESCAPAR(t.color)}">
          <span class="icono">${t.icono}</span>
          <strong>${t.total}</strong>
          <span>${ESCAPAR(t.nombre || t.tipo)}</span>
        </div>`).join('')
      || '<p class="placeholder">Aún no hay reportes.</p>';
  }

  state.ultimaRiesgo = stats.riesgo_barrios;
  renderMapa(stats.riesgo_barrios);
  renderMapaFiltros();
}

// Cuenta cuántos barrios hay en cada nivel y lo muestra en los botones de filtro
function renderMapaFiltros() {
  const riesgo = state.ultimaRiesgo || [];
  const cuenta = { alto: 0, medio: 0, bajo: 0 };
  riesgo.forEach((b) => { cuenta[nivelBarrio(b)]++; });
  $$('#mapa .mapa-filtros button[data-nivel]').forEach((btn) => {
    const n = btn.dataset.nivel;
    let badge = $('.cuenta', btn);
    if (n === 'todos') return;
    if (!badge) { badge = document.createElement('span'); badge.className = 'cuenta'; btn.appendChild(badge); }
    badge.textContent = cuenta[n];
  });
}

// Nivel final del barrio = criaderos sin controlar + bonus climático (clima.js).
// Si el clima todavía no cargó (o falla), se usa el nivel que calcula la API.
function nivelBarrio(b) {
  const activos = Number(b.indice) || 0;
  if (window.riesgoConClima && window.CaszaClima && window.CaszaClima.listo) {
    return window.riesgoConClima(activos).nivel;
  }
  return b.nivel in NIVELES ? b.nivel : 'bajo';
}

function renderMapa(riesgo) {
  const mapa = $('[data-js-mapa]');
  if (!mapa) return;

  const filtroNivel = state.mapaFiltroNivel || 'todos';
  const bonus = (window.CaszaClima && window.CaszaClima.listo) ? window.CaszaClima.bonus : 0;

  mapa.innerHTML = riesgo.map((b) => {
    const activos = Number(b.indice) || 0;
    const nivel = nivelBarrio(b);
    const oculto = (filtroNivel !== 'todos' && filtroNivel !== nivel) ? ' oculto' : '';
    const vacio = activos === 0 ? ' none' : '';
    const sel = state.filtros.barrio == b.id ? ' activo' : '';
    const extraClima = activos > 0 && bonus > 0 ? ` (+${bonus} por clima)` : '';
    // La burbuja muestra los criaderos sin controlar; el nombre aparece al pasar el mouse
    return `
      <button type="button" class="mapa-node ${nivel}${vacio}${sel}${oculto}" style="left:${b.x}%;top:${b.y}%"
              data-barrio="${b.id}" data-nivel="${nivel}"
              title="${ESCAPAR(b.nombre)}: ${activos} criadero(s) sin controlar${extraClima} · ${NIVELES[nivel].label}">
        <span class="burbuja">${activos}</span>
        <span class="nombre">${ESCAPAR(b.nombre)}</span>
      </button>`;
  }).join('');

  if (!riesgo.length) mapa.innerHTML = '<p class="placeholder">Sin datos para el mapa.</p>';
}

/* ---------- Reportes ---------- */
function reporteCard(r) {
  const acciones = [];
  if (r.estado === 'pendiente') {
    acciones.push(`<button class="btn-mini" data-estado data-id="${r.id}" data-sig="verificado">🔎 Verificar</button>`);
  } else if (r.estado === 'verificado') {
    acciones.push(`<button class="btn-mini btn-verde" data-estado data-id="${r.id}" data-sig="controlado">✅ Controlar</button>`);
  }
  acciones.push(`<button class="btn-voto" data-voto data-id="${r.id}">👍 ${r.votos}</button>`);
  acciones.push(`<button class="btn-del" data-eliminar data-id="${r.id}" title="Eliminar">✕</button>`);

  return `
    <article class="reporte" style="--color:${ESCAPAR(r.color)}">
      <div>
        <h3>${r.icono} ${ESCAPAR(r.titulo)}</h3>
        <p>${ESCAPAR(r.descripcion)}</p>
        <div class="meta">
          <span class="badge ${ESCAPAR(r.estado)}">${ESTADOS[r.estado] || r.estado}</span>
          <span>📍 Barrio ${ESCAPAR(r.barrio)}</span>
          ${r.referencia ? `<span>🧭 ${ESCAPAR(r.referencia)}</span>` : ''}
          <span>🏷️ ${ESCAPAR(r.tipo)}</span>
          <span>🕒 ${fechaRelativa(r.creado_en)}</span>
        </div>
      </div>
      <div class="acciones">${acciones.join(' ')}</div>
    </article>`;
}

async function cargarReportes() {
  const f = state.filtros;
  const params = new URLSearchParams();
  if (f.tipo) params.set('tipo', f.tipo);
  if (f.barrio) params.set('barrio', f.barrio);
  if (f.estado) params.set('estado', f.estado);
  if (f.q) params.set('q', f.q);

  const reportes = await api('reportes' + (params.toString() ? '&' + params.toString() : ''));

  // Actualizar el título según el filtro de barrio activo
  if (f.barrio) {
    const b = state.barrios.find((x) => x.id == f.barrio);
    if (b) $('.panel-head h2', $('#reportes')).innerHTML =
      `📋 Criaderos de <span class="mapa-seleccionado">${ESCAPAR(b.nombre)}</span>`;
  } else {
    $('.panel-head h2', $('#reportes')).innerHTML = '📋 Criaderos reportados por la comunidad';
  }

  const cont = $('[data-js-reportes]');
  if (!reportes.length) {
    cont.innerHTML = '<p class="placeholder">No hay criaderos con esos filtros. ¡Reportá el primero!</p>';
    return;
  }
  cont.innerHTML = reportes.map(reporteCard).join('');
}

/* ---------- Acciones ---------- */
async function crearReporte(ev) {
  ev.preventDefault();
  if (!puedeInteractuar()) {
    alert('Ya realizaste tu interacción diaria (reportar, votar o comentar). Volvé mañana.');
    return;
  }
  const form = ev.currentTarget;
  const data = Object.fromEntries(new FormData(form).entries());
  const msg = $('[data-js-form-msg]');
  msg.className = 'form-msg';

  try {
    await api('reportes', {
      method: 'POST',
      body: JSON.stringify({
        tipo_id: Number(data.tipo_id),
        barrio_id: Number(data.barrio_id),
        titulo: data.titulo.trim(),
        descripcion: data.descripcion.trim(),
        referencia: data.referencia.trim(),
      }),
    });
    incrementarLimite();
    actualizarEstadoLimite();
    msg.textContent = '✅ ¡Criadero reportado! Ya aparece en el mapa de tu barrio.';
    msg.classList.add('ok');
    form.reset();
    await Promise.all([cargarStats(), cargarReportes()]);
  } catch (err) {
    msg.textContent = '❌ ' + err.message;
    msg.classList.add('err');
  }
}

async function cambiarEstado(id, estado) {
  try {
    await api(`reportes/${id}`, { method: 'PATCH', body: JSON.stringify({ estado }) });
    await Promise.all([cargarStats(), cargarReportes()]);
  } catch (err) { alert(err.message); }
}

async function votarReporte(id) {
  if (!puedeInteractuar()) {
    alert('Ya realizaste tu interacción diaria (reportar, votar o comentar). Volvé mañana.');
    return;
  }
  try {
    await api(`reportes/${id}`, { method: 'PATCH', body: JSON.stringify({ votos: 1 }) });
    incrementarLimite();
    actualizarEstadoLimite();
    await cargarReportes();
  } catch (err) { alert(err.message); }
}

async function eliminarReporte(id) {
  if (!confirm('¿Eliminar este reporte?')) return;
  try {
    await api(`reportes/${id}`, { method: 'DELETE' });
    await Promise.all([cargarStats(), cargarReportes()]);
  } catch (err) { alert(err.message); }
}

/* ---------- Navegación ---------- */
function navegar(dest) {
  $$('[data-nav]').forEach((x) => x.classList.remove('active'));
  const target = dest === 'reportes' || dest === 'prevencion' || dest === 'quiz'
    ? $('#' + dest)
    : dest === 'nuevo' ? $('#nuevo') : $('#mapa');
  if (target) {
    window.scrollTo({ top: target.offsetTop - 16, behavior: 'smooth' });
    const nav = $(`.topbar a[data-nav="${dest}"]`);
    if (nav) nav.classList.add('active');
  }
}

/* ---------- Eventos ---------- */
function bind() {
  // Nav
  $$('[data-nav]').forEach((a) => a.addEventListener('click', (ev) => {
    ev.preventDefault();
    navegar(a.dataset.nav);
  }));

  // Mapa: clic en barrio filtra reportes
  $('[data-js-mapa]')?.addEventListener('click', (ev) => {
    const node = ev.target.closest('[data-barrio]');
    if (!node) return;
    state.filtros.barrio = state.filtros.barrio == node.dataset.barrio ? '' : node.dataset.barrio;
    const selBarrio = $('[data-js-filtros] select[name=barrio]');
    if (selBarrio) selBarrio.value = state.filtros.barrio;   // mantener el filtro visible sincronizado
    renderMapa(state.ultimaRiesgo || []);
    cargarReportes();
    $('#reportes').scrollIntoView({ behavior: 'smooth' });
  });

  // Sidebar de barrios: clic centra el mapa en el barrio
  $('[data-js-barra-barrios]')?.addEventListener('click', (ev) => {
    const btn = ev.target.closest('button[data-barrio]');
    if (!btn) return;
    const barrioId = btn.dataset.barrio;
    // Actualizar UI sidebar
    $$('[data-js-barra-barrios] button').forEach((b) => b.classList.remove('activo'));
    btn.classList.add('activo');
    if (barrioId === 'todos') {
      // Mostrar todos
      state.filtros.barrio = '';
      renderMapa(state.ultimaRiesgo || []);
      return;
    }
    // Centrar mapa en el barrio (hacer scroll al contenedor del mapa si hace falta)
    const barrio = state.barrios.find((b) => b.id == barrioId);
    if (barrio) {
      state.filtros.barrio = barrioId;
      // Actualizar filtro visible
      const selBarrio = $('[data-js-filtros] select[name=barrio]');
      if (selBarrio) selBarrio.value = barrioId;
      renderMapa(state.ultimaRiesgo || []);
      cargarReportes();
      // Scroll suave al mapa
      $('.mapa-wrapper')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  });

  // Formulario nuevo reporte
  $('[data-js-form]')?.addEventListener('submit', crearReporte);

  // Filtros
  $('[data-js-filtros]')?.addEventListener('change', async (ev) => {
    const f = new FormData(ev.currentTarget);
    state.filtros = {
      tipo: f.get('tipo') || '',
      barrio: f.get('barrio') || '',
      estado: f.get('estado') || '',
      q: f.get('q')?.trim() || '',
    };
    await cargarReportes();
  });

  $('[data-js-reset]')?.addEventListener('click', () => {
    $('[data-js-filtros]')?.reset();
    state.filtros = { tipo: '', barrio: '', estado: '', q: '' };
    cargarReportes();
    cargarStats();
  });

  // Acciones en listado de reportes
  $('[data-js-reportes]')?.addEventListener('click', (ev) => {
    const target = ev.target.closest('button');
    if (!target) return;
    // Los atributos data-estado / data-voto / data-eliminar no tienen valor (dataset = ""),
    // por eso se pregunta si existen con hasAttribute y no por su valor.
    if (target.hasAttribute('data-estado')) cambiarEstado(target.dataset.id, target.dataset.sig);
    if (target.hasAttribute('data-voto')) votarReporte(target.dataset.id);
    if (target.hasAttribute('data-eliminar')) eliminarReporte(target.dataset.id);
  });

  // Quiz
  $('[data-js-quiz]')?.addEventListener('click', (ev) => {
    const respuesta = ev.target.closest('[data-js-respuesta]');
    if (respuesta && !respuesta.disabled) responderQuiz(respuesta);
    if (ev.target.closest('[data-js-quiz-reiniciar]')) {
      state.quizIndex = 0; state.quizScore = 0; state.quizDone = false;
      renderQuiz();
    }
  });

  // Filtros del mapa (riesgo alto/medio/bajo)
  $('#mapa .mapa-filtros')?.addEventListener('click', (ev) => {
    const btn = ev.target.closest('button[data-nivel]');
    if (!btn) return;
    state.mapaFiltroNivel = btn.dataset.nivel;
    // Actualizar UI
    $$('#mapa .mapa-filtros button').forEach((b) => b.classList.remove('activo'));
    btn.classList.add('activo');
    // Re-renderizar mapa con filtro
    renderMapa(state.ultimaRiesgo || []);
  });

  // Cuando llegan los datos de clima, recalcular el semáforo
  document.addEventListener('clima:listo', () => {
    renderMapa(state.ultimaRiesgo || []);
    renderMapaFiltros();
  });
}

/* ---------- Init ---------- */
async function init() {
  bind();
  actualizarEstadoLimite();
  try {
    await cargarCatalogos();
    const stats = await api('stats');
    state.ultimaRiesgo = stats.riesgo_barrios;
    cargarStats();
    await cargarReportes();
  } catch (err) {
    $('[data-js-reportes]').innerHTML = `<p class="placeholder">⚠️ ${ESCAPAR(err.message)}</p>`;
  }
  renderQuiz();
}

document.addEventListener('DOMContentLoaded', init);