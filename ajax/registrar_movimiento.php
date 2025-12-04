<?php
session_start();
require_once '../config/database.php';
require_once '../models/Producto.php';
require_once '../models/MovimientoInventario.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$id_producto = $_POST['id_producto'];
$tipo_movimiento = $_POST['tipo_movimiento'];
$cantidad = intval($_POST['cantidad']);
$motivo = $_POST['motivo'];
$observaciones = $_POST['observaciones'];
$id_usuario = $_SESSION['usuario']['id'] ?? 1; // Ajusta según tu sesión

// Obtener producto actual
$productoModel = new Producto($db);
$query = "SELECT * FROM productos WHERE id_producto = ?";
$stmt = $db->prepare($query);
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    exit();
}

if (!$producto['maneja_inventario']) {
    echo json_encode(['success' => false, 'message' => 'Este producto no maneja inventario']);
    exit();
}

$stock_anterior = $producto['stock_actual'];
$stock_nuevo = $stock_anterior;

if ($tipo_movimiento === 'entrada') {
    $stock_nuevo = $stock_anterior + $cantidad;
} elseif ($tipo_movimiento === 'salida') {
    if ($stock_anterior < $cantidad) {
        echo json_encode(['success' => false, 'message' => 'Stock insuficiente. Stock actual: ' . $stock_anterior]);
        exit();
    }
    $stock_nuevo = $stock_anterior - $cantidad;
} else {
    echo json_encode(['success' => false, 'message' => 'Tipo de movimiento no válido']);
    exit();
}

// Iniciar transacción
try {
    $db->beginTransaction();

    // Actualizar stock del producto
    $updateStock = $productoModel->actualizarStock($id_producto, $stock_nuevo);

    if (!$updateStock) {
        throw new Exception('Error al actualizar el stock');
    }

    // Registrar movimiento
    $movimiento = new MovimientoInventario($db);
    $movimiento->id_producto = $id_producto;
    $movimiento->tipo_movimiento = $tipo_movimiento;
    $movimiento->cantidad = $cantidad;
    $movimiento->stock_anterior = $stock_anterior;
    $movimiento->stock_nuevo = $stock_nuevo;
    $movimiento->motivo = $motivo;
    $movimiento->id_remision = null; // Puedes ajustar si viene de una remisión
    $movimiento->id_usuario = $id_usuario;
    $movimiento->observaciones = $observaciones;

    if (!$movimiento->crear()) {
        throw new Exception('Error al registrar el movimiento');
    }

    $db->commit();
    echo json_encode(['success' => true, 'message' => 'Movimiento registrado exitosamente']);

} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>