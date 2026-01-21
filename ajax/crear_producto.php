<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    $producto = new Producto($db);

    // Assign data from POST
    $producto->nombre_producto = $_POST['nombre_producto'] ?? null;
    $producto->maneja_inventario = isset($_POST['maneja_inventario']) ? (int)$_POST['maneja_inventario'] : 0;
    $producto->stock_actual = isset($_POST['stock_actual']) ? (int)$_POST['stock_actual'] : 0;
    $producto->stock_minimo = isset($_POST['stock_minimo']) ? (int)$_POST['stock_minimo'] : 0;

    // Validate name
    if (empty($producto->nombre_producto)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El nombre del producto es obligatorio']);
        exit;
    }

    if ($producto->crear()) {
        http_response_code(201); // Created
        echo json_encode([
            'success' => true,
            'message' => 'Producto creado con éxito',
            'producto' => [
                'id_producto' => $producto->id_producto,
                'nombre_producto' => $producto->nombre_producto,
                'maneja_inventario' => $producto->maneja_inventario,
                'stock_actual' => $producto->stock_actual,
                'stock_minimo' => $producto->stock_minimo
            ]
        ]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'No se pudo crear el producto. Es posible que ya exista.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    // Log the detailed PDO error to a file, not to the user
    file_put_contents('db_errors.log', $e->getMessage(), FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Error de base de datos.']);

} catch (Exception $e) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
