<?php
session_start();
require_once '../config/database.php';
require_once '../models/Producto.php';
require_once '../models/MovimientoInventario.php';

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
    // Validar que se reciban los datos necesarios
    if (!isset($_POST['remision_id']) || !isset($_POST['productos'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        exit;
    }

    $remision_id = intval($_POST['remision_id']);
    $productos = json_decode($_POST['productos'], true);
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($productos)) {
        echo json_encode([
            'success' => false,
            'message' => 'No se especificaron productos para devolver'
        ]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();
    
    $producto = new Producto($db);
    $movimiento = new MovimientoInventario($db);

    // Iniciar transacción
    $db->beginTransaction();

    $errores = [];
    $productos_procesados = 0;

    foreach ($productos as $item) {
        $producto_id = intval($item['producto_id']);
        $cantidad = intval($item['cantidad']);

        if ($cantidad <= 0) {
            continue;
        }

        // Agregar stock al producto
        $resultado = $producto->agregarStock($producto_id, $cantidad);

        if ($resultado['success']) {
            // Registrar el movimiento de inventario
            $movimiento_resultado = $movimiento->registrar(
                $producto_id,
                'entrada',
                $cantidad,
                $usuario_id,
                "Devolución de remisión #$remision_id",
                $remision_id
            );

            if ($movimiento_resultado['success']) {
                $productos_procesados++;
            } else {
                $errores[] = "Error al registrar movimiento para producto ID $producto_id";
            }
        } else {
            $errores[] = $resultado['message'];
        }
    }

    if (empty($errores)) {
        $db->commit();
        echo json_encode([
            'success' => true,
            'message' => "Se procesaron $productos_procesados productos devueltos correctamente"
        ]);
    } else {
        $db->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Errores al procesar devolución: ' . implode(', ', $errores)
        ]);
    }

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la devolución: ' . $e->getMessage()
    ]);
}
?>
