<?php
// ajax/listar_personas_contacto.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

header('Content-Type: application/json');

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$termino = isset($_GET['termino']) ? $_GET['termino'] : '';
$registros_por_pagina = 10;
$offset = ($pagina - 1) * $registros_por_pagina;

try {
    $database = new Database();
    $db = $database->getConnection();
    $personaContacto = new PersonaContacto($db);

    $total_registros = $personaContacto->contarPersonasContacto($termino);
    $personas = $personaContacto->obtenerPersonasContactoPaginadas($termino, $offset, $registros_por_pagina);

    echo json_encode([
        'personas' => $personas,
        'paginacion' => [
            'pagina_actual' => $pagina,
            'total_paginas' => ceil($total_registros / $registros_por_pagina),
            'total_registros' => $total_registros
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
