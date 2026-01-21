<?php
// ajax/editar_persona_responsable.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

$database = new Database();
$db = $database->getConnection();

$persona = new PersonaResponsable($db);

$id_responsable = isset($_POST['id_responsable']) ? $_POST['id_responsable'] : '';
$nombre_responsable = isset($_POST['nombre_responsable']) ? $_POST['nombre_responsable'] : '';
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$correo = isset($_POST['correo']) ? $_POST['correo'] : '';

if (empty($id_responsable) || empty($nombre_responsable)) {
    echo json_encode(['success' => false, 'message' => 'El ID y el nombre son obligatorios.']);
    exit;
}

// Asignar valores al objeto persona
$persona->id_responsable = $id_responsable;
$persona->nombre_responsable = $nombre_responsable;
$persona->telefono = $telefono;
$persona->correo = $correo;

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