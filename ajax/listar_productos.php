<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    $producto = new Producto($db);

    // Parámetros
    $pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
    $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
    $filtroInventario = isset($_POST['filtroInventario']) ? $_POST['filtroInventario'] : 'todos';
    $filtroStock = isset($_POST['filtroStock']) ? $_POST['filtroStock'] : 'todos';
    $registros_por_pagina = 10;

    if ($pagina < 1) $pagina = 1;
    $offset = ($pagina - 1) * $registros_por_pagina;

    // Obtener datos
    $total_productos = $producto->contarProductos($busqueda, $filtroInventario, $filtroStock);
    $productos = $producto->obtenerProductosPaginados($busqueda, $filtroInventario, $filtroStock, $offset, $registros_por_pagina);
    $total_paginas = ($total_productos > 0) ? ceil($total_productos / $registros_por_pagina) : 1;

    // Devolver respuesta
    echo json_encode([
        'success' => true,
        'productos' => $productos,
        'paginacion' => [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_productos' => $total_productos
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
