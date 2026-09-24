/* ============================================================
   FormosaHack 2026 — app.js
   Consume la API REST en /api/?route=... y renderiza el dashboard.
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
  if (!res.ok || json.ok === false) {
    throw new Error(json.error || 'Error en la API');
  }
  return json.data;
}

function fechaRelativa(dt) {
  const ms = new Date(dt.replace(' ', 'T')) - new Date();
  const s = Math.round(Math.abs(ms) / 1000);
  if (s < 60) return 'hace unos segundos';
  const m = Math.round(s / 60);
  if (m < 60) return `hace ${m} min`;
  const h = Math.round(m / 60);
  if (h < 24) return `hace ${h} h`;
  const d = Math.round(h / 24);
  return `hace ${d} d`;
}

const ESTADOS = {
  pendiente: 'Pendiente',
  en_proceso: 'En proceso',
  resuelto: 'Resuelto',
};

/* ---------- Estado global ---------- */
const state = {
  categorias: [],
  filtros: { categoria: '', estado: '', q: '' },
};

/* ---------- Cargar y renderizar ---------- */
async function cargarCategorias() {
  state.categorias = await api('categorias');
  const selectFiltro = $('[data-js-filtros] select[name=categoria]');
  const selectForm = $('[data-js-form] select[name=categoria_id]');

  const options = state.categorias
    .map((c) => `<option value="${c.id}">${ESCAPAR(c.nombre)}</option>`)
    .join('');

  if (selectFiltro) selectFiltro.innerHTML = '<option value="">Todos los sectores</option>' + options;
  if (selectForm) selectForm.innerHTML = '<option value="">— Elegir sector —</option>' + options;
}

async function cargarStats() {
  const stats = await api('stats');
  const map = Object.fromEntries(stats.por_estado.map((s) => [s.estado, s.total]));

  $('[data-js-kpi=total] strong').textContent = stats.total;
  $('[data-js-kpi=pendiente] strong').textContent = map.pendiente || 0;
  $('[data-js-kpi=en_proceso] strong').textContent = map.en_proceso || 0;
  $('[data-js-kpi=resuelto] strong').textContent = map.resuelto || 0;

  const cont = $('[data-js-sectores]');
  cont.innerHTML = stats.por_sector
    .map((s) => `
      <div class="sector" style="--color:${ESCAPAR(s.color)}">
        <strong>${s.total}</strong>
        <span>${ESCAPAR(s.sector)}</span>
      </div>`)
    .join('') || '<p class="placeholder">Sin reportes aún.</p>';
}

async function cargarReportes() {
  const f = state.filtros;
  const params = new URLSearchParams();
  if (f.categoria) params.set('categoria', f.categoria);
  if (f.estado) params.set('estado', f.estado);
  if (f.q) params.set('q', f.q);

  const reportes = await api('reportes' + (params.toString() ? '&' + params.toString() : ''));
  const cont = $('[data-js-reportes]');

  if (!reportes.length) {
    cont.innerHTML = '<p class="placeholder">No hay reportes con esos filtros.</p>';
    return;
  }

  cont.innerHTML = reportes.map((r) => `
    <article class="reporte" style="--color:${ESCAPAR(r.color)}">
      <div>
        <h3>${ESCAPAR(r.titulo)}</h3>
        <p>${ESCAPAR(r.descripcion)}</p>
        <div class="meta">
          <span class="badge ${ESCAPAR(r.estado)}">${ESTADOS[r.estado] || r.estado}</span>
          <span>📍 ${ESCAPAR(r.ubicacion)}</span>
          <span>🏷️ ${ESCAPAR(r.sector)}</span>
          <span>🗳️ ${r.votos} votos</span>
          <span>🕒 ${fechaRelativa(r.creado_en)}</span>
        </div>
      </div>
      <div class="acciones">
        ${r.estado !== 'resuelto' ? `
          <button class="btn-mini" data-js-estado data-id="${r.id}" data-estado="resuelto">Marcar resuelto</button>
        ` : ''}
        <button class="btn-del" data-js-eliminar data-id="${r.id}">✕</button>
      </div>
    </article>
  `).join('');
}

/* ---------- Acciones ---------- */
async function crearReporte(ev) {
  ev.preventDefault();
  const form = ev.currentTarget;
  const data = Object.fromEntries(new FormData(form).entries());
  const msg = $('[data-js-form-msg]');
  msg.className = 'form-msg';

  try {
    await api('reportes', {
      method: 'POST',
      body: JSON.stringify({
        categoria_id: Number(data.categoria_id),
        titulo: data.titulo.trim(),
        descripcion: data.descripcion.trim(),
        ubicacion: data.ubicacion.trim(),
      }),
    });
    msg.textContent = '✅ Reporte publicado. ¡Gracias por participar!';
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
  } catch (err) {
    alert(err.message);
  }
}

async function eliminarReporte(id) {
  if (!confirm('¿Eliminar este reporte?')) return;
  try {
    await api(`reportes/${id}`, { method: 'DELETE' });
    await Promise.all([cargarStats(), cargarReportes()]);
  } catch (err) {
    alert(err.message);
  }
}

/* ---------- Eventos ---------- */
function bind() {
  $('[data-js-form]')?.addEventListener('submit', crearReporte);

  $('[data-js-filtros]')?.addEventListener('change', async (ev) => {
    const f = new FormData(ev.currentTarget);
    state.filtros = {
      categoria: f.get('categoria') || '',
      estado: f.get('estado') || '',
      q: f.get('q')?.trim() || '',
    };
    await cargarReportes();
  });

  $('[data-js-reset]')?.addEventListener('click', () => {
    $('[data-js-filtros]')?.reset();
    state.filtros = { categoria: '', estado: '', q: '' };
    cargarReportes();
  });

  $('[data-js-reportes]')?.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('button[data-js-estado], button[data-js-eliminar]');
    if (!btn) return;
    if (btn.dataset.jsEstado) await cambiarEstado(btn.dataset.id, btn.dataset.estado);
    if (btn.dataset.jsEliminar) await eliminarReporte(btn.dataset.id);
  });

  // Navegación suave (anclar a secciones)
  $$('[data-js-nav]').forEach((a) => {
    a.addEventListener('click', (ev) => {
      ev.preventDefault();
      $$('[data-js-nav]').forEach((x) => x.classList.remove('active'));
      a.classList.add('active');
      const dest = a.dataset.jsNav;
      if (dest === 'reportes') {
        window.scrollTo({ top: $('.panel:has([data-js-reportes])').offsetTop - 20, behavior: 'smooth' });
      } else if (dest === 'nuevo') {
        window.scrollTo({ top: $('#nuevo').offsetTop - 20, behavior: 'smooth' });
      } else {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    });
  });
}

/* ---------- Init ---------- */
async function init() {
  bind();
  try {
    await Promise.all([cargarCategorias(), cargarStats(), cargarReportes()]);
  } catch (err) {
    $('[data-js-reportes]').innerHTML =
      `<p class="placeholder">⚠️ ${ESCAPAR(err.message)}</p>`;
  }
}

document.addEventListener('DOMContentLoaded', init);