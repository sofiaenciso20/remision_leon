<?php
require_once '../config/database.php';
require_once '../models/MovimientoInventario.php';

header('Content-Type: text/html');

if (!isset($_GET['id_producto'])) {
    echo "<div class='alert alert-danger'>ID de producto no especificado</div>";
    exit();
}

$id_producto = intval($_GET['id_producto']);

$database = new Database();
$db = $database->getConnection();

$movimientoModel = new MovimientoInventario($db);
$movimientos = $movimientoModel->obtenerPorProducto($id_producto);

if (empty($movimientos)) {
    echo "<p class='text-muted'>No hay movimientos registrados para este producto.</p>";
    exit();
}
?>

<div class="table-responsive">
    <table class="table table-sm table-striped">
        <thead class="table-light">
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Stock Anterior</th>
                <th>Stock Nuevo</th>
                <th>Motivo</th>
                <th>Usuario</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $movimiento): 
                $badge_class = $movimiento['tipo_movimiento'] === 'entrada' ? 'bg-success' : 'bg-warning';
            ?>
            <tr>
                <td><?php echo date('d/m/Y H:i', strtotime($movimiento['fecha_movimiento'])); ?></td>
                <td>
                    <span class="badge <?php echo $badge_class; ?>">
                        <?php echo ucfirst($movimiento['tipo_movimiento']); ?>
                    </span>
                </td>
                <td class="fw-bold"><?php echo $movimiento['cantidad']; ?></td>
                <td><?php echo $movimiento['stock_anterior']; ?></td>
                <td><?php echo $movimiento['stock_nuevo']; ?></td>
                <td><?php echo ucfirst($movimiento['motivo']); ?></td>
                <td><?php echo htmlspecialchars($movimiento['usuario_nombre'] ?? 'Sistema'); ?></td>
                <td><?php echo htmlspecialchars($movimiento['observaciones'] ?? '-'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>