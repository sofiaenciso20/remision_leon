<?php
// ajax/ver_remision.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';
require_once __DIR__ . '/../models/ItemRemisionado.php';

if (!isset($_POST['id_remision'])) {
    die('ID de remisión no especificado');
}

$id_remision = $_POST['id_remision'];

$database = new Database();
$db = $database->getConnection();

$remision = new Remision($db);
$itemRemisionado = new ItemRemisionado($db);

$datos = $remision->obtenerPorId($id_remision);
$items = $itemRemisionado->obtenerPorRemision($id_remision);

if (!$datos) {
    echo '<div class="alert alert-danger">Remisión no encontrada</div>';
    exit;
}
?>

<div class="detalles-remision">
    <div class="row">
        <div class="col-md-6">
            <h5><i class="fas fa-info-circle"></i> Información de la Remisión</h5>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="40%">Número de Remisión:</th>
                    <td><?php echo $datos['numero_remision'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Fecha:</th>
                    <td><?php echo isset($datos['fecha_emision']) ? date('d/m/Y', strtotime($datos['fecha_emision'])) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Cliente:</th>
                    <td><?php echo htmlspecialchars($datos['nombre_cliente'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>NIT:</th>
                    <td><?php echo htmlspecialchars($datos['nit'] ?? 'N/A'); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h5><i class="fas fa-user"></i> Información de Contacto</h5>
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="40%">Dirección:</th>
                    <td><?php echo htmlspecialchars($datos['direccion'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>Teléfono:</th>
                    <td><?php echo htmlspecialchars($datos['telefono'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>Persona de Contacto:</th>
                    <td>
                        <?php if (!empty($datos['nombre_persona'])): ?>
                            <strong><?php echo htmlspecialchars($datos['nombre_persona']); ?></strong>
                            <?php if (!empty($datos['telefono_persona'])): ?>
                                <br><small class="text-muted">Tel: <?php echo htmlspecialchars($datos['telefono_persona']); ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <?php if (count($items) > 0): ?>
    <div class="row mt-4">
        <div class="col-12">
            <h5><i class="fas fa-boxes"></i> Items Remisionados</h5>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th width="10%">Cantidad</th>
                            <th width="50%">Descripción</th>
                            <th width="20%">Valor Unitario</th>
                            <th width="20%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_general = 0;
                        foreach ($items as $item): 
                            $valor_unitario = $item['valor_unitario'] ?? 0;
                            $total_item = $item['cantidad'] * $valor_unitario;
                            $total_general += $total_item;
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $item['cantidad']; ?></td>
                                <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
                                <td class="text-right">$ <?php echo number_format($valor_unitario, 0, ',', '.'); ?></td>
                                <td class="text-right">$ <?php echo number_format($total_item, 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-primary">
                            <th colspan="3" class="text-right">TOTAL GENERAL:</th>
                            <th class="text-right">$ <?php echo number_format($total_general, 0, ',', '.'); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($datos['observaciones'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <h5><i class="fas fa-sticky-note"></i> Observaciones</h5>
            <div class="observaciones-responsive">
                <?php echo nl2br(htmlspecialchars($datos['observaciones'])); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>