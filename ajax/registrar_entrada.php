<?php
// ajax/registrar_entrada.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/MovimientoInventario.php';

header('Content-Type: application/json');
session_start();

// Auth check
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    $database = new Database();
    $db = $database->getConnection();

    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : null;
    $motivo = 'ajuste_manual'; // Motivo explícito
    $id_usuario = $_SESSION['usuario_id'];

    // Validaciones
    if ($id_producto <= 0) {
        throw new Exception('ID de producto no válido');
    }

    if ($cantidad <= 0) {
        throw new Exception('La cantidad debe ser mayor a 0');
    }

    // Obtener producto actual
    $productoModel = new Producto($db);
    $producto = $productoModel->obtenerPorId($id_producto);

    if (!$producto) {
        throw new Exception('Producto no encontrado');
    }

    if (!$producto['maneja_inventario']) {
        throw new Exception('Este producto no maneja inventario');
    }

    $stock_anterior = $producto['stock_actual'];
    $stock_nuevo = $stock_anterior + $cantidad;

    // Iniciar transacción
    $db->beginTransaction();

    try {
        // 1. Actualizar stock del producto
        $productoModel->actualizarStock($id_producto, $stock_nuevo);

        // 2. Registrar movimiento
        $movimientoModel = new MovimientoInventario($db);
        $movimientoModel->id_producto = $id_producto;
        $movimientoModel->tipo_movimiento = 'entrada';
        $movimientoModel->cantidad = $cantidad;
        $movimientoModel->stock_anterior = $stock_anterior;
        $movimientoModel->stock_nuevo = $stock_nuevo;
        $movimientoModel->motivo = $motivo;
        $movimientoModel->id_remision = null;
        $movimientoModel->id_usuario = $id_usuario;
        $movimientoModel->observaciones = $observaciones;

        if (!$movimientoModel->crear()) {
            throw new Exception('Error al registrar el movimiento de inventario');
        }

        $db->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Entrada de inventario registrada correctamente.',
            'stock_nuevo' => $stock_nuevo
        ]);

    } catch (Exception $e) {
        $db->rollBack();
        throw $e; // Re-lanzar para el manejo de errores global
    }

} catch (Exception $e) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
