<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();
$clienteModel = new Cliente($db);

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 10;
$offset = ($pagina - 1) * $porPagina;
$termino = $_GET['termino'] ?? '';

try {
    $clientes = $clienteModel->obtenerClientesPaginados($termino, $offset, $porPagina);
    $total = $clienteModel->contarClientes($termino);
    $totalPaginas = ceil($total / $porPagina);

    echo json_encode([
        'success' => true,
        'clientes' => $clientes,
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