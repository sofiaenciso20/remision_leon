<?php
// ajax/editar_persona_responsable.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

$database = new Database();
$db = $database->getConnection();

$persona = new PersonaResponsable($db);

$id_responsable = isset($_POST['id_responsable']) ? $_POST['id_responsable'] : die(json_encode(['success' => false, 'message' => 'ID no proporcionado.']));
$nombre_responsable = isset($_POST['nombre_responsable']) ? $_POST['nombre_responsable'] : die(json_encode(['success' => false, 'message' => 'Nombre no proporcionado.']));

// Asignar valores al objeto persona
$persona->id_responsable = $id_responsable;
$persona->nombre_responsable = $nombre_responsable;

if (empty($persona->nombre_responsable)) {
    echo json_encode(['success' => false, 'message' => 'El nombre del responsable es obligatorio.']);
    exit;
}

try {
    if ($persona->actualizar()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la persona responsable.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
