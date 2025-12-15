<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';
require_once __DIR__ . '/../models/ItemRemisionado.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/MovimientoInventario.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $itemRemisionado = new ItemRemisionado($db);
        $productoModel = new Producto($db);
        $movimientoInventario = new MovimientoInventario($db);

        // 🔹 NUEVO: Tipo Remisión y Persona Responsable
        $remision->tipo_remision = $_POST['tipo_remision'] ?? 'Venta';
        $remision->id_responsable = !empty($_POST['id_responsable']) 
                                            ? (int) $_POST['id_responsable'] 
                                            : null;

        // Campos anteriores
        $remision->numero_remision = (int) $_POST['numero_remision'];
        $remision->fecha_emision = $_POST['fecha_emision'];
        $remision->id_cliente = (int) $_POST['id_cliente'];
        $remision->id_persona = !empty($_POST['id_persona']) ? (int) $_POST['id_persona'] : null;
        $remision->id_usuario = 1;
        $remision->observaciones = $_POST['observaciones'] ?? null;
        $remision->id_estado = !empty($_POST['id_estado']) ? (int) $_POST['id_estado'] : 1;

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
                if (empty($item['descripcion']) || empty($item['cantidad'])) {
                    continue;
                }

                $itemObj = new ItemRemisionado($db);
                $itemObj->id_remision = $id_remision;
                $itemObj->id_producto = !empty($item['id_producto']) ? (int) $item['id_producto'] : null;
                $itemObj->descripcion = $item['descripcion'];
                $itemObj->cantidad = (int) $item['cantidad'];
                $itemObj->valor_unitario = !empty($item['valor_unitario']) ? (float) $item['valor_unitario'] : 0.00;

                if ($itemObj->crear()) {
                    $items_procesados++;

                    if (!empty($item['id_producto'])) {
                        $query_producto = "SELECT maneja_inventario, stock_actual FROM productos WHERE id_producto = ?";
                        $stmt = $db->prepare($query_producto);
                        $stmt->execute([$item['id_producto']]);
                        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($producto && $producto['maneja_inventario']) {
                            if ($producto['stock_actual'] < $item['cantidad']) {
                                throw new Exception('Stock insuficiente del producto: ' . $item['descripcion']);
                            }

                            $stock_anterior = $producto['stock_actual'];
                            $stock_nuevo = $stock_anterior - $item['cantidad'];

                            $update_stock = "UPDATE productos SET stock_actual = ? WHERE id_producto = ?";
                            $stmt = $db->prepare($update_stock);
                            $stmt->execute([$stock_nuevo, $item['id_producto']]);

                            $movimiento = new MovimientoInventario($db);
                            $movimiento->id_producto = $item['id_producto'];
                            $movimiento->tipo_movimiento = 'salida';
                            $movimiento->cantidad = $item['cantidad'];
                            $movimiento->stock_anterior = $stock_anterior;
                            $movimiento->stock_nuevo = $stock_nuevo;
                            $movimiento->motivo = 'remision';
                            $movimiento->id_remision = $id_remision;
                            $movimiento->id_usuario = 1;
                            $movimiento->observaciones = "Remisión #" . $remision->numero_remision;
                            $movimiento->crear();

                            $items_con_inventario++;
                        }
                    }
                }
            }
        }

        $db->commit();

        echo json_encode([
            'success' => true,
            'id_remision' => $id_remision,
            'message' => 'Remisión creada correctamente',
            'items_procesados' => $items_procesados,
            'items_con_inventario' => $items_con_inventario
        ]);

    } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
