<?php
// ajax/obtener_persona_responsable.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

$database = new Database();
$db = $database->getConnection();

$persona = new PersonaResponsable($db);

$id_responsable = isset($_POST['id_responsable']) ? $_POST['id_responsable'] : die(json_encode(['success' => false, 'message' => 'ID no proporcionado.']));

$persona->id_responsable = $id_responsable;

try {
    $datos_persona = $persona->obtenerPorId();
    if ($datos_persona) {
        echo json_encode(['success' => true, 'data' => $datos_persona]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Persona responsable no encontrada.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
