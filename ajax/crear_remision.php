<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';
require_once __DIR__ . '/../models/ItemRemisionado.php';
require_once __DIR__ . '/../models/Producto.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = null;
    try {
        $database = new Database();
        $db = $database->getConnection();

        if (!$db) {
            throw new Exception('Error de conexión a la base de datos');
        }

        if (empty($_POST['id_cliente'])) {
            throw new Exception('El cliente es obligatorio');
        }

        $db->beginTransaction();

        $remision = new Remision($db);
        $productoModel = new Producto($db);

        $remision->tipo_remision = $_POST['tipo_remision'] ?? 'Venta';
        $remision->id_responsable = !empty($_POST['id_responsable']) ? (int)$_POST['id_responsable'] : null;
        $remision->numero_remision = (int)$_POST['numero_remision'];
        $remision->fecha_emision = $_POST['fecha_emision'];
        $remision->id_cliente = (int)$_POST['id_cliente'];
        $remision->id_persona = !empty($_POST['id_persona']) ? (int)$_POST['id_persona'] : null;
        $remision->id_usuario = 1;
        $remision->observaciones = $_POST['observaciones'] ?? null;
        $remision->id_estado = 1;

        $id_remision = $remision->crear();

        if (!$id_remision) {
            throw new Exception('No se pudo crear la remisión');
        }

        $items_procesados = 0;
        $items_con_inventario = 0;

        if (!empty($_POST['items'])) {
            $items = json_decode($_POST['items'], true);

            if (!is_array($items)) {
                throw new Exception('Formato inválido en items');
            }

            foreach ($items as $item) {
                if (empty($item['id_producto']) || empty($item['cantidad'])) {
                    continue;
                }

                $itemObj = new ItemRemisionado($db);
                $itemObj->id_remision = $id_remision;
                $itemObj->id_producto = (int)$item['id_producto'];
                $itemObj->descripcion = $item['descripcion'];
                $itemObj->cantidad = (int)$item['cantidad'];
                $itemObj->valor_unitario = (float)($item['valor_unitario'] ?? 0.00);

                if ($itemObj->crear()) {
                    $items_procesados++;

                    if ($remision->tipo_remision === 'Venta') {
                        $producto_info = $productoModel->obtenerPorId($item['id_producto']);

                        if ($producto_info && $producto_info['maneja_inventario']) {
                            $movimiento_exitoso = $remision->registrarMovimientoInventario(
                                $item['id_producto'],
                                $item['cantidad'],
                                $id_remision
                            );

                            if ($movimiento_exitoso) {
                                $items_con_inventario++;
                            } else {
                                throw new Exception('Stock insuficiente para el producto: ' . $item['descripcion']);
                            }
                        }
                    }
                }
            }
        }

        $db->commit();

        echo json_encode([
            'success' => true,
            'id_remision' => $id_remision,
            'numero_remision' => $remision->numero_remision,
            'message' => 'Remisión creada correctamente.',
            'items_procesados' => $items_procesados,
            'items_con_inventario' => $items_con_inventario
        ]);

    } catch (Exception $e) {
        if ($db && $db->inTransaction()) {
            $db->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
