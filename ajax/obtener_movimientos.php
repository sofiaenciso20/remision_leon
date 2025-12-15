<?php
// ajax/obtener_movimientos.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/MovimientoInventario.php';

if (!isset($_GET['id_producto'])) {
    echo '<div class="alert alert-danger">No se especificó un producto.</div>';
    exit;
}

$id_producto = $_GET['id_producto'];

try {
    $database = new Database();
    $db = $database->getConnection();
    $movimientoModel = new MovimientoInventario($db);
    $movimientos = $movimientoModel->obtenerPorProducto($id_producto);

    if (empty($movimientos)) {
        echo '<div class="text-center p-4"><p class="text-muted">No hay movimientos para este producto.</p></div>';
        exit;
    }

    $html = '<div class="table-responsive"><table class="table table-sm table-striped table-hover">';
    $html .= '<thead class="table-light"><tr><th>Fecha</th><th>Tipo</th><th>Cantidad</th><th>Stock Anterior</th><th>Stock Nuevo</th><th>Remisión</th><th>Observaciones</th></tr></thead><tbody>';

    foreach ($movimientos as $m) {
        $tipo = '';
        if ($m['tipo_movimiento'] === 'entrada_manual') {
            $tipo = '<span class="badge bg-primary">Entrada</span>';
        } elseif ($m['tipo_movimiento'] === 'salida_manual') {
            $tipo = '<span class="badge bg-warning">Salida</span>';
        } else {
            $tipo = '<span class="badge bg-secondary">' . htmlspecialchars($m['tipo_movimiento']) . '</span>';
        }

        $html .= '<tr>';
        $html .= '<td>' . date('d/m/Y H:i', strtotime($m['fecha_movimiento'])) . '</td>';
        $html .= '<td>' . $tipo . '</td>';
        $html .= '<td>' . htmlspecialchars($m['cantidad']) . '</td>';
        $html .= '<td>' . htmlspecialchars($m['stock_anterior']) . '</td>';
        $html .= '<td>' . htmlspecialchars($m['stock_nuevo']) . '</td>';
        $html .= '<td>' . ($m['id_remision'] ? '<a href="generar_pdf.php?id=' . $m['id_remision'] . '" target="_blank">#' . htmlspecialchars($m['id_remision']) . '</a>' : '-') . '</td>';
        $html .= '<td>' . htmlspecialchars($m['observaciones']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div>';
    echo $html;

} catch (Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar el historial.</div>';
}
?>
