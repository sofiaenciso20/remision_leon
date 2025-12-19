<?php
// ajax/listar_personas_responsables.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

$database = new Database();
$db = $database->getConnection();

$persona = new PersonaResponsable($db);

$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
$filas_por_pagina = 10;
$inicio = ($pagina > 1) ? ($pagina * $filas_por_pagina) - $filas_por_pagina : 0;

try {
    // Obtener total de registros para la paginación
    $total_registros = $persona->contarPersonasResponsables($busqueda);
    
    // Obtener registros paginados
    $stmt = $persona->obtenerPaginados($inicio, $filas_por_pagina, $busqueda);
    $personas_arr = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $persona_item = [
            "id_responsable" => $id_responsable,
            "nombre_responsable" => $nombre_responsable,
            "telefono" => $telefono,
            "correo" => $correo
        ];
        array_push($personas_arr, $persona_item);
    }

    $paginacion = [
        'pagina_actual' => $pagina,
        'filas_por_pagina' => $filas_por_pagina,
        'total_registros' => (int)$total_registros,
        'total_paginas' => ceil($total_registros / $filas_por_pagina)
    ];

    echo json_encode([
        'success' => true,
        'personas' => $personas_arr,
        'paginacion' => $paginacion
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
