<?php
// ajax/crear_persona_responsable.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

$database = new Database();
$db = $database->getConnection();

$persona = new PersonaResponsable($db);

$nombre_responsable = isset($_POST['nombre_responsable']) ? $_POST['nombre_responsable'] : die(json_encode(['success' => false, 'message' => 'Nombre no proporcionado.']));

// Asignar valores al objeto persona
$persona->nombre_responsable = $nombre_responsable;

if (empty($persona->nombre_responsable)) {
    echo json_encode(['success' => false, 'message' => 'El nombre del responsable es obligatorio.']);
    exit;
}

try {
    if ($persona->crear()) {
        echo json_encode(['success' => true, 'id' => $persona->id_responsable, 'nombre_responsable' => $persona->nombre_responsable]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear la persona responsable.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
