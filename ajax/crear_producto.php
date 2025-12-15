<?php
// ajax/crear_producto.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/MovimientoInventario.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$nombre_producto = $_POST['nombre_producto'] ?? '';
$maneja_inventario = isset($_POST['maneja_inventario']) ? 1 : 0;
$stock_inicial = $_POST['stock_inicial'] ?? 0;
$stock_minimo = $_POST['stock_minimo'] ?? 0;

if (empty($nombre_producto)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El nombre del producto es obligatorio']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $productoModel = new Producto($db);
    $movimientoModel = new MovimientoInventario($db);

    // Iniciar transacción
    $db->beginTransaction();

    $productoModel->nombre_producto = $nombre_producto;
    $productoModel->maneja_inventario = $maneja_inventario;
    $productoModel->stock_actual = $maneja_inventario ? $stock_inicial : 0;
    $productoModel->stock_minimo = $maneja_inventario ? $stock_minimo : 0;

    if ($productoModel->crear()) {
        if ($maneja_inventario && $stock_inicial > 0) {
            $id_producto = $productoModel->id_producto;
            $movimientoModel->registrarMovimiento($id_producto, 'stock_inicial', $stock_inicial, 0, $stock_inicial, null, 'Creación del producto');
        }
        $db->commit();
        echo json_encode(['success' => true, 'message' => 'Producto creado correctamente']);
    } else {
        $db->rollBack();
        throw new Exception('No se pudo crear el producto');
    }

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
