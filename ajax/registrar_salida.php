<?php
// ajax/registrar_salida.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/MovimientoInventario.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$id_producto = $_POST['id_producto'] ?? 0;
$cantidad = $_POST['cantidad'] ?? 0;
$observaciones = $_POST['observaciones'] ?? '';

if (empty($id_producto) || empty($cantidad) || !is_numeric($cantidad) || $cantidad <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $productoModel = new Producto($db);
    $movimientoModel = new MovimientoInventario($db);

    $producto = $productoModel->obtenerPorId($id_producto);
    if (!$producto) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
        exit;
    }

    $stock_anterior = $producto['stock_actual'];
    if ($cantidad > $stock_anterior) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No hay suficiente stock para registrar la salida']);
        exit;
    }

    $stock_nuevo = $stock_anterior - $cantidad;

    // Iniciar transacción
    $db->beginTransaction();

    if ($productoModel->actualizarStock($id_producto, $stock_nuevo) &&
        $movimientoModel->registrarMovimiento($id_producto, 'salida_manual', $cantidad, $stock_anterior, $stock_nuevo, null, $observaciones)) {

        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Salida registrada correctamente']);
    } else {
        $db->rollBack();
        throw new Exception('No se pudo completar la operación');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
