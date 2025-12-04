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
    if (!isset($_POST['producto_id']) || !isset($_POST['cantidad']) || !isset($_POST['tipo']) || !isset($_POST['motivo'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos'
        ]);
        exit;
    }

    $producto_id = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);
    $tipo = $_POST['tipo']; // 'entrada' o 'salida'
    $motivo = trim($_POST['motivo']);
    $usuario_id = $_SESSION['usuario_id'];

    // Validar tipo de movimiento
    if (!in_array($tipo, ['entrada', 'salida'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de movimiento inválido'
        ]);
        exit;
    }

    // Validar cantidad
    if ($cantidad <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'La cantidad debe ser mayor a 0'
        ]);
        exit;
    }

    // Validar motivo
    if (empty($motivo)) {
        echo json_encode([
            'success' => false,
            'message' => 'Debe especificar un motivo para el ajuste'
        ]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();
    
    $producto = new Producto($db);
    $movimiento = new MovimientoInventario($db);

    // Iniciar transacción
    $db->beginTransaction();

    // Ajustar el stock según el tipo
    if ($tipo === 'entrada') {
        $resultado = $producto->agregarStock($producto_id, $cantidad);
    } else {
        // Verificar que haya suficiente stock antes de descontar
        $verificacion = $producto->verificarStock($producto_id, $cantidad);
        if (!$verificacion['success']) {
            echo json_encode($verificacion);
            exit;
        }
        $resultado = $producto->descontarStock($producto_id, $cantidad);
    }

    if ($resultado['success']) {
        // Registrar el movimiento de inventario
        $movimiento_resultado = $movimiento->registrar(
            $producto_id,
            $tipo,
            $cantidad,
            $usuario_id,
            $motivo
        );

        if ($movimiento_resultado['success']) {
            $db->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Ajuste de inventario realizado correctamente'
            ]);
        } else {
            $db->rollBack();
            echo json_encode($movimiento_resultado);
        }
    } else {
        $db->rollBack();
        echo json_encode($resultado);
    }

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode([
        'success' => false,
        'message' => 'Error al ajustar inventario: ' . $e->getMessage()
    ]);
}
?>
