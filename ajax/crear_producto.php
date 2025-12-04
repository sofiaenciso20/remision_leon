<?php
session_start();
require_once '../config/database.php';
require_once '../models/Producto.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$producto = new Producto($db);

$producto->nombre_producto = $_POST['nombre_producto'];
$producto->maneja_inventario = isset($_POST['maneja_inventario']) ? 1 : 0;
$producto->stock_actual = $_POST['stock_inicial'];
$producto->stock_minimo = $_POST['stock_minimo'];

if ($producto->crear()) {
    echo json_encode(['success' => true, 'message' => 'Producto creado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear el producto']);
}
?>