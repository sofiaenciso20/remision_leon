<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    $cliente = new Cliente($db);

    // Parámetros de paginación y búsqueda
    $pagina_actual = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
    $termino_busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
    $registros_por_pagina = 10; // Puedes ajustar este valor

    // Validar que la página sea un número positivo
    if ($pagina_actual < 1) {
        $pagina_actual = 1;
    }

    // Calcular el offset
    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    // Obtener el total de clientes (filtrados si hay búsqueda)
    $total_clientes = $cliente->contarClientes($termino_busqueda);

    // Obtener los clientes para la página actual
    $clientes = $cliente->obtenerClientesPaginados($termino_busqueda, $offset, $registros_por_pagina);

    // Calcular el total de páginas
    $total_paginas = ($total_clientes > 0) ? ceil($total_clientes / $registros_por_pagina) : 1;

    // Devolver los datos en formato JSON
    echo json_encode([
        'success' => true,
        'clientes' => $clientes,
        'paginacion' => [
            'pagina_actual' => $pagina_actual,
            'total_paginas' => $total_paginas,
            'total_clientes' => $total_clientes
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
