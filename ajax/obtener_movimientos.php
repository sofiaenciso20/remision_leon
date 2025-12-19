<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/MovimientoInventario.php';

header('Content-Type: application/json');

// Validar entrada
if (!isset($_GET['id_producto'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'ID de producto no especificado.']);
    exit();
}

$id_producto = intval($_GET['id_producto']);
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$registros_por_pagina = 10;
$offset = ($pagina - 1) * $registros_por_pagina;

try {
    $database = new Database();
    $db = $database->getConnection();
    $movimientoModel = new MovimientoInventario($db);

    // Obtener total y datos paginados
    $total_movimientos = $movimientoModel->contarMovimientosPorProducto($id_producto);
    $movimientos = $movimientoModel->obtenerMovimientosPaginadosPorProducto($id_producto, $offset, $registros_por_pagina);
    $total_paginas = ceil($total_movimientos / $registros_por_pagina);

    echo json_encode([
        'success' => true,
        'movimientos' => $movimientos,
        'paginacion' => [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_movimientos
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>
