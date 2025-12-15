<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    $remision = new Remision($db);

    // Parámetros
    $pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
    $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $registros_por_pagina = 10;

    if ($pagina < 1) $pagina = 1;
    $offset = ($pagina - 1) * $registros_por_pagina;

    // Obtener datos
    $total_remisiones = $remision->contarRemisiones($busqueda, $fecha);
    $remisiones = $remision->obtenerRemisionesPaginadas($busqueda, $fecha, $offset, $registros_por_pagina);

    $total_paginas = ($total_remisiones > 0) ? ceil($total_remisiones / $registros_por_pagina) : 1;

    // Devolver respuesta
    echo json_encode([
        'success' => true,
        'remisiones' => $remisiones,
        'paginacion' => [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_remisiones' => $total_remisiones
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
