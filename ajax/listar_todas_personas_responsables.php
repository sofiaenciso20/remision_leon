<?php
// ajax/listar_todas_personas_responsables.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    $personaResponsable = new PersonaResponsable($db);
    $personas = $personaResponsable->obtenerTodos();
    
    echo json_encode($personas);

} catch (Exception $e) {
    error_log("Error al obtener todas las personas responsables: " . $e->getMessage());
    // Devuelve un array vacío en caso de error para no romper el frontend
    echo json_encode([]);
}
?>
