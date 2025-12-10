<?php
// ajax/obtener_remision_completa.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';

$database = new Database();
$db = $database->getConnection();
$remision = new Remision($db);

$id_remision = isset($_POST['id_remision']) ? (int)$_POST['id_remision'] : 0;

if ($id_remision <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de remisión no válido.']);
    exit;
}

try {
    $datos_remision = $remision->obtenerCompletaPorId($id_remision);

    if ($datos_remision) {
        echo json_encode(['success' => true, 'data' => $datos_remision]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Remisión no encontrada.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
