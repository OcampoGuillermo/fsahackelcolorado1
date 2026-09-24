<?php
/**
 * API REST — Front controller
 * ============================
 * Rutas (usar ?route=):
 *   GET    /api/?route=categorias
 *   GET    /api/?route=reportes            (filtros: ?categoria=&estado=&q=&limit=)
 *   GET    /api/?route=reportes/{id}
 *   POST   /api/?route=reportes            (body JSON)
 *   PATCH  /api/?route=reportes/{id}       (changing estado: pendiente|en_proceso|resuelto)
 *   DELETE /api/?route=reportes/{id}
 *   GET    /api/?route=stats
 */

require_once __DIR__ . '/../inc/helpers.php';

/* ---------- Resolución de la ruta ---------- */
$route = $_GET['route'] ?? '';
if ($route === '') {
    // Soporte de rutas limpias: /api/reportes/12 -> route = reportes/12
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); // /formosahack-2026/api/index.php
    $base   = dirname($script);                                // /formosahack-2026/api
    $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    if (str_starts_with($uri, $base . '/')) {
        $route = substr($uri, strlen($base) + 1);
    }
}
$route  = preg_replace('#^index\.php/?#', '', $route); // limpiar /api/index.php/reportes
$route  = trim($route, '/');
$method = $_SERVER['REQUEST_METHOD'];

/* ---------- Helpers de recursos ---------- */

function listar_categorias(): array
{
    return db()->query('SELECT id, nombre, color FROM categorias ORDER BY id')->fetchAll();
}

function validar_estado(string $estado): bool
{
    return in_array($estado, ['pendiente', 'en_proceso', 'resuelto'], true);
}

function url_base(): string
{
    return BASE_URL . '/api';
}

/* ---------- Rutas ---------- */

// Lista de endpoints (útil para el jurado)
if ($route === '' && $method === 'GET') {
    $b = url_base();
    json_response([
        'ok' => true,
        'app' => APP_NAME,
        'version' => APP_VERSION,
        'endpoints' => [
            "$b?route=categorias",
            "$b?route=reportes",
            "$b?route=reportes&categoria={id}&estado={estado}&q={texto}",
            "$b?route=reportes/{id}",
            "$b?route=stats",
        ],
    ]);
}

// Categorías (sectores del desafío)
if ($route === 'categorias' && $method === 'GET') {
    json_response(['ok' => true, 'data' => listar_categorias()]);
}

// Estadísticas (KPIs del dashboard)
if ($route === 'stats' && $method === 'GET') {
    $total   = (int) db()->query('SELECT COUNT(*) FROM reportes')->fetchColumn();
    $porEstado = db()->query(
        "SELECT estado, COUNT(*) AS total FROM reportes GROUP BY estado"
    )->fetchAll();

    $porSector = db()->query(
        "SELECT c.nombre AS sector, c.color, COUNT(r.id) AS total
           FROM categorias c
           LEFT JOIN reportes r ON r.categoria_id = c.id
          GROUP BY c.id, c.nombre, c.color
          ORDER BY total DESC"
    )->fetchAll();

    json_response([
        'ok' => true,
        'data' => [
            'total'          => $total,
            'por_estado'     => $porEstado,
            'por_sector'     => $porSector,
            'resueltos_pct'  => $total > 0
                ? round(array_sum(array_map(
                    fn ($s) => $s['estado'] === 'resuelto' ? $s['total'] : 0,
                    $porEstado
                )) / $total * 100)
                : 0,
        ],
    ]);
}

// LISTA de reportes con filtros
if (preg_match('#^reportes$#', $route) && $method === 'GET') {
    $categoria = val($_GET['categoria'] ?? '');
    $estado    = val($_GET['estado'] ?? '');
    $q         = trim($_GET['q'] ?? '');
    $limit     = min(200, max(1, (int) ($_GET['limit'] ?? 50)));

    $sql  = "SELECT r.id, r.titulo, r.descripcion, r.ubicacion, r.estado, r.votos,
                    r.creado_en, c.nombre AS sector, c.color
               FROM reportes r
               JOIN categorias c ON c.id = r.categoria_id
              WHERE 1=1";
    $pars = [];

    if ($categoria !== '') {
        $sql .= ' AND r.categoria_id = ?';
        $pars[] = (int) $categoria;
    }
    if ($estado !== '' && validar_estado($estado)) {
        $sql .= ' AND r.estado = ?';
        $pars[] = $estado;
    }
    if ($q !== '') {
        $sql .= ' AND (r.titulo LIKE ? OR r.descripcion LIKE ? OR r.ubicacion LIKE ? OR c.nombre LIKE ?)';
        $like = "%{$q}%";
        array_push($pars, $like, $like, $like, $like);
    }
    $sql .= ' ORDER BY r.creado_en DESC LIMIT ' . $limit;

    $stmt = db()->prepare($sql);
    $stmt->execute($pars);

    json_response(['ok' => true, 'data' => $stmt->fetchAll()]);
}

// UN reporte por id
if (preg_match('#^reportes/(\d+)$#', $route, $m)) {
    $id = (int) $m[1];

    if ($method === 'GET') {
        $stmt = db()->prepare(
            "SELECT r.*, c.nombre AS sector, c.color
               FROM reportes r
               JOIN categorias c ON c.id = r.categoria_id
              WHERE r.id = ?
              LIMIT 1"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            json_error('Reporte no encontrado', 404);
        }
        json_response(['ok' => true, 'data' => $row]);
    }

    // Actualizar estado (simula avance de la gestión)
    if ($method === 'PATCH' || $method === 'PUT') {
        $body = body_json();
        $estado = $body['estado'] ?? '';
        if (!validar_estado($estado)) {
            json_error('Estado inválido. Use: pendiente, en_proceso o resuelto.');
        }
        $stmt = db()->prepare('UPDATE reportes SET estado = ? WHERE id = ?');
        $stmt->execute([$estado, $id]);
        if ($stmt->rowCount() === 0) {
            json_error('Reporte no encontrado', 404);
        }
        json_response(['ok' => true, 'message' => 'Estado actualizado a ' . $estado]);
    }

    // Eliminar
    if ($method === 'DELETE') {
        $stmt = db()->prepare('DELETE FROM reportes WHERE id = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount() === 0) {
            json_error('Reporte no encontrado', 404);
        }
        json_response(['ok' => true, 'message' => 'Reporte eliminado']);
    }
}

// CREAR reporte
if ($route === 'reportes' && $method === 'POST') {
    $b = body_json();

    $titulo       = trim($b['titulo'] ?? '');
    $descripcion  = trim($b['descripcion'] ?? '');
    $ubicacion    = trim($b['ubicacion'] ?? 'Formosa');
    $categoria_id = (int) ($b['categoria_id'] ?? 0);

    if ($titulo === '' || mb_strlen($titulo) > 120) {
        json_error('El título es obligatorio (máx. 120 caracteres).');
    }
    if ($descripcion === '') {
        json_error('La descripción es obligatoria.');
    }
    if ($categoria_id < 1) {
        json_error('Debe elegir una categoría (sector).');
    }

    $stmt = db()->prepare(
        'INSERT INTO reportes (categoria_id, titulo, descripcion, ubicacion)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$categoria_id, $titulo, $descripcion, $ubicacion]);
    $id = (int) db()->lastInsertId();

    $stmt = db()->prepare('SELECT * FROM reportes WHERE id = ?');
    $stmt->execute([$id]);

    json_response(['ok' => true, 'message' => 'Reporte creado', 'data' => $stmt->fetch()], 201);
}

// Ruta desconocida
json_error('Ruta no encontrada. Ver /api/?route=', 404);