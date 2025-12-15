<?php
// ajax/actualizar_remision.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';

$database = new Database();
$db = $database->getConnection();
$remision = new Remision($db);

// Obtener datos del POST
$id_remision = $_POST['id_remision'] ?? 0;
$items = json_decode($_POST['items'] ?? '[]', true);

if ($id_remision <= 0 || !is_array($items)) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

// Asignar datos a la remisión
$remision->id_remision = $id_remision;
$remision->fecha_emision = $_POST['fecha_emision'];
$remision->tipo_remision = $_POST['tipo_remision'];
$remision->id_cliente = $_POST['id_cliente'];
$remision->id_persona = !empty($_POST['id_persona']) ? $_POST['id_persona'] : null;
$remision->id_responsable = !empty($_POST['id_responsable']) ? $_POST['id_responsable'] : null;
$remision->observaciones = $_POST['observaciones'] ?? '';

try {
    $db->beginTransaction();

    // 1. Actualizar la cabecera de la remisión
    if (!$remision->actualizar()) {
        throw new Exception("No se pudo actualizar la remisión.");
    }

    // 2. Borrar los items antiguos
    $query_delete = "DELETE FROM remision_items WHERE id_remision = :id_remision";
    $stmt_delete = $db->prepare($query_delete);
    $stmt_delete->bindParam(':id_remision', $id_remision);
    $stmt_delete->execute();

    // 3. Insertar los items nuevos
    $query_insert = "INSERT INTO remision_items (id_remision, id_producto, descripcion, cantidad, valor_unitario) 
                     VALUES (:id_remision, :id_producto, :descripcion, :cantidad, :valor_unitario)";
    $stmt_insert = $db->prepare($query_insert);

    foreach ($items as $item) {
        $stmt_insert->bindParam(':id_remision', $id_remision);
        $stmt_insert->bindParam(':id_producto', $item['id_producto']);
        $stmt_insert->bindParam(':descripcion', $item['descripcion']);
        $stmt_insert->bindParam(':cantidad', $item['cantidad']);
        $stmt_insert->bindParam(':valor_unitario', $item['valor_unitario']);
        
        if (!$stmt_insert->execute()) {
            throw new Exception("No se pudo guardar un item de la remisión.");
        }
    }

    $db->commit();
    echo json_encode(['success' => true, 'message' => 'Remisión actualizada correctamente.']);

} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
