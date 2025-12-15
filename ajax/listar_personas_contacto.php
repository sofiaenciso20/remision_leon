<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    $persona = new PersonaContacto($db);

    // Parámetros de paginación y búsqueda
    $pagina_actual = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
    $termino_busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
    $registros_por_pagina = 10;

    if ($pagina_actual < 1) {
        $pagina_actual = 1;
    }

    $offset = ($pagina_actual - 1) * $registros_por_pagina;

    // Obtener el total de personas (filtradas)
    $total_personas = $persona->contarPersonasContacto($termino_busqueda);

    // Obtener las personas para la página actual
    $personas = $persona->obtenerPersonasContactoPaginadas($termino_busqueda, $offset, $registros_por_pagina);

    // Calcular el total de páginas
    $total_paginas = ($total_personas > 0) ? ceil($total_personas / $registros_por_pagina) : 1;

    // Devolver los datos en formato JSON
    echo json_encode([
        'success' => true,
        'personas' => $personas,
        'paginacion' => [
            'pagina_actual' => $pagina_actual,
            'total_paginas' => $total_paginas,
            'total_personas' => $total_personas
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
