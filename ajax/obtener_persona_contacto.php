<?php
// ajax/obtener_persona_contacto.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

$database = new Database();
$db = $database->getConnection();
$persona = new PersonaContacto($db);

$id_persona = 0;
if (isset($_GET['id'])) {
    $id_persona = (int)$_GET['id'];
} elseif (isset($_POST['id_persona'])) {
    $id_persona = (int)$_POST['id_persona'];
}

if ($id_persona <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de persona no válido.']);
    exit;
}

try {
    $datos_persona = $persona->obtenerPorId($id_persona);

    if ($datos_persona) {
        echo json_encode(['success' => true, 'data' => $datos_persona]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Persona de contacto no encontrada.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
