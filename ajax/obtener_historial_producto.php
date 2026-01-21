<?php
// ajax/obtener_historial_producto.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';

header('Content-Type: application/json');

if (!isset($_GET['id_producto']) || empty($_GET['id_producto'])) {
    echo json_encode(['success' => false, 'message' => 'ID de producto no proporcionado.']);
    exit;
}

$id_producto = (int)$_GET['id_producto'];

try {
    $database = new Database();
    $db = $database->getConnection();
    $producto = new Producto($db);

    $historial = $producto->obtenerHistorialMovimientos($id_producto);

    echo json_encode(['success' => true, 'historial' => $historial]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener el historial: ' . $e->getMessage()]);
}
