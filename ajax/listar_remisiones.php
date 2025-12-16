<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();
$remisionModel = new Remision($db);

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 10;
$offset = ($pagina - 1) * $porPagina;

$termino = $_GET['buscar'] ?? '';
$fecha_creacion = $_GET['fecha_creacion'] ?? '';
$id_cliente = $_GET['id_cliente'] ?? '';
$id_persona = $_GET['id_persona'] ?? '';

try {
    $remisiones = $remisionModel->obtenerRemisionesPaginadas($termino, $fecha_creacion, $offset, $porPagina, $id_cliente, $id_persona);
    $total = $remisionModel->contarRemisiones($termino, $fecha_creacion, $id_cliente, $id_persona);
    $totalPaginas = ceil($total / $porPagina);

    echo json_encode([
        'success' => true,
        'remisiones' => $remisiones,
        'paginacion' => [
            'pagina' => $pagina,
            'porPagina' => $porPagina,
            'total' => $total,
            'totalPaginas' => $totalPaginas
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>