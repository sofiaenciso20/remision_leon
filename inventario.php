<?php
// inventario.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Producto.php';
require_once __DIR__ . '/models/MovimientoInventario.php';

$database = new Database();
$db = $database->getConnection();

$productoModel = new Producto($db);
$productos = $productoModel->obtenerTodos();
?>

<?php include __DIR__ . '/views/layout/header.php'; ?>

<style>
/* Estilos basados en listar_remisiones.php */
.content-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem 0;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.25rem;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.card-title {
    font-weight: 600;
    color: #495057;
}

/* Mejoras para formularios */
.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control, .select2-container .select2-selection--single {
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus, .select2-container--focus .select2-selection--single {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Mejoras para botones */
.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn i {
    margin-right: 0.5rem;
}

.btn-group .btn {
    margin: 0 2px;
}

/* Mejoras para la tabla */
.table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 0.75rem;
    vertical-align: middle;
}

.table tbody td {
    padding: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.04);
    transform: translateY(-1px);
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Estados de stock */
.stock-bajo { 
    background-color: #ffe6e6; 
    border-left: 4px solid #dc3545;
}

.stock-medio { 
    background-color: #fff9e6; 
    border-left: 4px solid #ffc107;
}

.stock-ok { 
    background-color: #e6ffe6; 
    border-left: 4px solid #28a745;
}

.badge-stock { 
    font-size: 0.8em; 
    font-weight: 500;
}

/* Mejoras para modales */
.modal-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1.25rem;
}

.modal-title {
    font-weight: 600;
    color: #495057;
}

.modal-content {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Mejoras específicas para responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1.25rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 2px 0;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between > div {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .content-header h1 {
        font-size: 1.5rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
}

/* Efectos visuales adicionales */
.card-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #495057;
}

/* Mejoras para filtros */
.filter-card {
    border-left: 4px solid #6c757d !important;
}

.filter-card .card-body {
    padding: 1.25rem;
}

/* Mejoras para badges */
.badge {
    font-weight: 500;
}

.badge.bg-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800) !important;
}

.badge.bg-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

/* Animaciones suaves */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Estados vacíos */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-file-invoice mr-2"></i>Inventario de Productos
                </h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content py-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            
            <div class="d-flex flex-column flex-md-row gap-2">
                <button class="btn btn-success" data-toggle="modal" data-target="#nuevoProductoModal">
                    <i class="fas fa-plus mr-1"></i> Nuevo Producto
                </button>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Inicio
                </a>
            </div>
        </div>

        <!-- Alertas -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipo_mensaje']; ?> alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <div><?php echo $_SESSION['mensaje']; ?></div>
                </div>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <?php if (empty($productos)): ?>
            <div class="card">
                <div class="card-body empty-state">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">No se encontraron productos</h4>
                    <p class="text-muted mb-4">Comienza agregando productos a tu inventario</p>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#nuevoProductoModal">
                        <i class="fas fa-plus mr-1"></i> Crear Primer Producto
                    </button>
                </div>
            </div>
        <?php else: ?>

        <!-- Filtros y tabla de productos -->
        <div class="card filter-card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-filter text-secondary mr-2"></i> Filtros de Búsqueda
                    </h4>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="collapse" data-target="#filtrosBody">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div class="collapse show" id="filtrosBody">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label for="buscarProducto" class="form-label fw-semibold">Buscar Producto</label>
                            <input type="text" class="form-control" id="buscarProducto" placeholder="Nombre del producto...">
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label for="filtroInventario" class="form-label fw-semibold">Tipo de Inventario</label>
                            <select class="form-control" id="filtroInventario">
                                <option value="todos">Todos los productos</option>
                                <option value="con-inventario">Con inventario</option>
                                <option value="sin-inventario">Sin inventario</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <label for="filtroStock" class="form-label fw-semibold">Estado de Stock</label>
                            <select class="form-control" id="filtroStock">
                                <option value="todos">Todo el stock</option>
                                <option value="bajo">Stock bajo</option>
                                <option value="medio">Stock medio</option>
                                <option value="ok">Stock ok</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">
                    <i class="fas fa-list text-primary mr-2"></i> Productos en Inventario
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaInventario">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">ID</th>
                                <th class="border-0">Producto</th>
                                <th class="border-0">Maneja Inventario</th>
                                <th class="border-0">Stock Actual</th>
                                <th class="border-0">Stock Mínimo</th>
                                <th class="border-0">Estado</th>
                                <th class="border-0 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): 
                                $stock = $producto['stock_actual'] ?? 0;
                                $stock_minimo = $producto['stock_minimo'] ?? 0;
                                $maneja_inventario = $producto['maneja_inventario'] ?? 0;
                                
                                if ($maneja_inventario) {
                                    if ($stock <= $stock_minimo) {
                                        $clase_stock = 'stock-bajo';
                                        $estado = '<span class="badge bg-danger badge-stock">Bajo</span>';
                                    } elseif ($stock <= ($stock_minimo + 10)) {
                                        $clase_stock = 'stock-medio';
                                        $estado = '<span class="badge bg-warning badge-stock">Medio</span>';
                                    } else {
                                        $clase_stock = 'stock-ok';
                                        $estado = '<span class="badge bg-success badge-stock">Ok</span>';
                                    }
                                } else {
                                    $clase_stock = '';
                                    $estado = '<span class="badge bg-secondary badge-stock">No aplica</span>';
                                }
                            ?>
                            <tr class="<?php echo $clase_stock; ?> fade-in" 
                                data-inventario="<?php echo $maneja_inventario ? 'con-inventario' : 'sin-inventario'; ?>" 
                                data-stock="<?php echo $maneja_inventario ? ($stock <= $stock_minimo ? 'bajo' : ($stock <= ($stock_minimo + 10) ? 'medio' : 'ok')) : 'na'; ?>">
                                <td>
                                    <span class="text-muted">#<?php echo $producto['id_producto']; ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo htmlspecialchars($producto['nombre_producto']); ?></div>
                                </td>
                                <td>
                                    <?php if ($maneja_inventario): ?>
                                        <span class="badge bg-success">Sí</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="<?php echo $maneja_inventario ? 'text-dark' : 'text-muted'; ?>">
                                        <?php echo $stock; ?>
                                    </strong>
                                    <small class="text-muted"> unidades</small>
                                </td>
                                <td>
                                    <?php if ($maneja_inventario): ?>
                                        <strong class="text-dark"><?php echo $stock_minimo; ?></strong>
                                        <small class="text-muted"> unidades</small>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $estado; ?></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?php if ($maneja_inventario): ?>
                                            <button class="btn btn-outline-primary btn-entrada" 
                                                    data-id="<?php echo $producto['id_producto']; ?>"
                                                    data-nombre="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                                    title="Registrar entrada">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                            <button class="btn btn-outline-warning btn-salida"
                                                    data-id="<?php echo $producto['id_producto']; ?>"
                                                    data-nombre="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                                    data-stock="<?php echo $stock; ?>"
                                                    title="Registrar salida">
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-outline-info btn-historial"
                                                data-id="<?php echo $producto['id_producto']; ?>"
                                                data-nombre="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                                title="Ver historial">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Nuevo Producto -->
<div class="modal fade" id="nuevoProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formNuevoProducto">
                <div class="modal-header bg-light">
                    <h4 class="modal-title mb-0">
                        <i class="fas fa-plus text-success mr-2"></i>Nuevo Producto
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label for="nombre_producto" class="form-label">Nombre del Producto *</label>
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                    </div>
                    <div class="form-group form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="maneja_inventario" name="maneja_inventario" value="1" checked>
                        <label class="form-check-label" for="maneja_inventario">Maneja inventario</label>
                    </div>
                    <div class="form-group mb-3">
                        <label for="stock_inicial" class="form-label">Stock Inicial</label>
                        <input type="number" class="form-control" id="stock_inicial" name="stock_inicial" value="0" min="0" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" value="0" min="0" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Crear Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Entrada -->
<div class="modal fade" id="entradaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEntrada">
                <input type="hidden" name="motivo" value="ajuste_manual">
                <div class="modal-header bg-light">
                    <h4 class="modal-title mb-0">
                        <i class="fas fa-arrow-down text-primary mr-2"></i>Entrada de Inventario
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="entrada_id_producto" name="id_producto">
                    <div class="form-group mb-3">
                        <label class="form-label">Producto</label>
                        <input type="text" class="form-control" id="entrada_nombre_producto" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="cantidad_entrada" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control" id="cantidad_entrada" name="cantidad" min="1" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="observaciones_entrada" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones_entrada" name="observaciones" rows="2" placeholder="Motivo de la entrada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Registrar Entrada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Salida -->
<div class="modal fade" id="salidaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSalida">
                <input type="hidden" name="motivo" value="ajuste_manual">
                <div class="modal-header bg-light">
                    <h4 class="modal-title mb-0">
                        <i class="fas fa-arrow-up text-warning mr-2"></i>Salida de Inventario
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="salida_id_producto" name="id_producto">
                    <div class="form-group mb-3">
                        <label class="form-label">Producto</label>
                        <input type="text" class="form-control" id="salida_nombre_producto" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Stock Actual</label>
                        <input type="text" class="form-control" id="salida_stock_actual" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="cantidad_salida" class="form-label">Cantidad *</label>
                        <input type="number" class="form-control" id="cantidad_salida" name="cantidad" min="1" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="observaciones_salida" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones_salida" name="observaciones" rows="2" placeholder="Motivo de la salida..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i> Registrar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Historial -->
<div class="modal fade" id="historialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-history text-info mr-2"></i>Historial de Movimientos
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div id="contenido-historial">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                        <p class="text-muted">Cargando historial...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Filtros
    $('#buscarProducto').on('input', filtrarTabla);
    $('#filtroInventario, #filtroStock').on('change', filtrarTabla);

    function filtrarTabla() {
        const termino = $('#buscarProducto').val().toLowerCase();
        const filtroInventario = $('#filtroInventario').val();
        const filtroStock = $('#filtroStock').val();

        $('#tablaInventario tbody tr').each(function() {
            const nombre = $(this).find('td:eq(1)').text().toLowerCase();
            const inventario = $(this).data('inventario');
            const stock = $(this).data('stock');

            const coincideNombre = nombre.includes(termino);
            const coincideInventario = filtroInventario === 'todos' || inventario === filtroInventario;
            const coincideStock = filtroStock === 'todos' || stock === filtroStock;

            if (coincideNombre && coincideInventario && coincideStock) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Modal de entrada
    $('.btn-entrada').on('click', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        
        $('#entrada_id_producto').val(id);
        $('#entrada_nombre_producto').val(nombre);
        $('#cantidad_entrada').val('');
        $('#observaciones_entrada').val('');
        $('#entradaModal').modal('show');
    });

    // Modal de salida
    $('.btn-salida').on('click', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const stock = $(this).data('stock');
        
        $('#salida_id_producto').val(id);
        $('#salida_nombre_producto').val(nombre);
        $('#salida_stock_actual').val(stock + ' unidades');
        $('#cantidad_salida').attr('max', stock).val('');
        $('#observaciones_salida').val('');
        $('#salidaModal').modal('show');
    });

    // Modal de historial
    $('.btn-historial').on('click', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        
        $('#historialModal .modal-title').html('<i class="fas fa-history text-info mr-2"></i>Historial - ' + nombre);
        
        $('#contenido-historial').html(`
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                <p class="text-muted">Cargando historial...</p>
            </div>
        `);
        
        $.ajax({
            url: 'ajax/obtener_movimientos.php',
            type: 'GET',
            data: { id_producto: id },
            success: function(response) {
                $('#contenido-historial').html(response);
            },
            error: function() {
                $('#contenido-historial').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Error al cargar el historial de movimientos
                    </div>
                `);
            }
        });
        
        $('#historialModal').modal('show');
    });

    // Manejar envío del formulario de nuevo producto
    $('#formNuevoProducto').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Creando...');
        
        $.ajax({
            url: 'ajax/crear_producto.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Producto creado correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'No fue posible crear el producto', 'error');
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Error al crear el producto', 'error');
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Manejar envío del formulario de entrada
    $('#formEntrada').on('submit', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Registrando...');
        
        $.ajax({
            url: 'ajax/registrar_entrada.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Entrada registrada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'No fue posible registrar la entrada', 'error');
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Error al registrar la entrada', 'error');
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Manejar envío del formulario de salida
    $('#formSalida').on('submit', function(e) {
        e.preventDefault();
        const cantidad = parseInt($('#cantidad_salida').val());
        const stockMaximo = parseInt($('#cantidad_salida').attr('max'));
        
        if (cantidad > stockMaximo) {
            Swal.fire('Error', 'La cantidad no puede ser mayor al stock disponible', 'error');
            return;
        }
        
        const $btn = $(this).find('button[type="submit"]');
        const originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Registrando...');
        
        $.ajax({
            url: 'ajax/registrar_salida.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Salida registrada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'No fue posible registrar la salida', 'error');
                    $btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Error al registrar la salida', 'error');
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>