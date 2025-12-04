<?php
session_start();
require_once '../config/database.php';
require_once '../models/Producto.php';

header('Content-Type: application/json');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No autorizado'
    ]);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $producto = new Producto($db);

    // Verificar si se solicita un producto específico
    if (isset($_GET['producto_id'])) {
        $producto_id = intval($_GET['producto_id']);
        $resultado = $producto->obtenerPorId($producto_id);
        echo json_encode($resultado);
    } 
    // Verificar si se solicitan productos con stock bajo
    elseif (isset($_GET['stock_bajo']) && $_GET['stock_bajo'] === 'true') {
        $resultado = $producto->obtenerProductosStockBajo();
        echo json_encode($resultado);
    }
    // Obtener todos los productos con inventario
    else {
        $solo_con_inventario = isset($_GET['solo_inventario']) && $_GET['solo_inventario'] === 'true';
        $resultado = $producto->obtenerTodos($solo_con_inventario);
        echo json_encode($resultado);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener inventario: ' . $e->getMessage()
    ]);
}
?>
