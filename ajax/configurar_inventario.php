<?php
session_start();
header('Content-Type: application/json');

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

require_once '../config/database.php';
require_once '../models/Producto.php';

try {
    // Obtener datos JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validar datos requeridos
    if (!isset($data['producto_id']) || !isset($data['maneja_inventario'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit();
    }

    $database = new Database();
    $db = $database->getConnection();
    $producto = new Producto($db);

    // Configurar inventario
    $resultado = $producto->actualizarConfiguracionInventario(
        $data['producto_id'],
        $data['maneja_inventario'],
        $data['stock_actual'] ?? 0,
        $data['stock_minimo'] ?? 0
    );

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al configurar inventario: ' . $e->getMessage()
    ]);
}
?>
