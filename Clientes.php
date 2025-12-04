<?php
// filepath: c:\xampp\htdocs\remisiones\Clientes.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

// Obtener todos los clientes
$stmt = $cliente->obtenerTodos();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ordenar los clientes por ID de menor a mayor
usort($clientes, function($a, $b) {
    return $a['id_cliente'] - $b['id_cliente'];
});

// Incluir cabecera
include __DIR__ . '/views/layout/header.php';
?>

<style>
/* Estilos minimalistas consistentes con listar_remisiones.php */
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

/* Efectos visuales */
.card-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #495057;
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

/* Animaciones suaves */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Info boxes mejoradas */
.info-box {
    border-radius: 0.375rem;
    overflow: hidden;
    margin-bottom: 1rem;
}

.info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
}

.info-box-content {
    padding: 0.75rem;
}

/* Badges mejorados */
.badge {
    font-weight: 500;
}

/* Text truncate */
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-users mr-2"></i>Gestión de Clientes
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
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCrearCliente">
                    <i class="fas fa-plus mr-1"></i> Nuevo Cliente
                </button>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al Inicio
                </a>
            </div>
        </div>

        <!-- Información de resultados -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-light fade-in">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            <strong>Mostrando <?php echo count($clientes); ?> cliente(s)</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de clientes -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">
                    <i class="fas fa-list mr-2"></i> Clientes Registrados
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaClientes">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">ID</th>
                                <th class="border-0">Nombre</th>
                                <th class="border-0">Tipo</th>
                                <th class="border-0">NIT</th>
                                <th class="border-0">Teléfono</th>
                                <th class="border-0">Correo</th>
                                <th class="border-0 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($clientes)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 empty-state">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-3">No se encontraron clientes</h5>
                                        <p class="text-muted mb-4">No hay clientes registrados en el sistema</p>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearCliente">
                                            <i class="fas fa-plus mr-1"></i> Agregar primer cliente
                                        </button>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($clientes as $cli): ?>
                                <tr class="fade-in">
                                    <td>
                                        <span class="text-muted">#<?php echo htmlspecialchars($cli['id_cliente']); ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($cli['nombre_cliente']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $cli['tipo_cliente'] == 'empresa' ? 'primary' : 'secondary'; ?>">
                                            <?php echo ucfirst($cli['tipo_cliente']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($cli['nit'])): ?>
                                            <span class="text-muted"><?php echo htmlspecialchars($cli['nit']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($cli['telefono'])): ?>
                                            <span class="text-muted"><?php echo htmlspecialchars($cli['telefono']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($cli['correo'])): ?>
                                            <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?php echo htmlspecialchars($cli['correo']); ?>">
                                                <?php echo htmlspecialchars($cli['correo']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-info" onclick="verCliente(<?php echo $cli['id_cliente']; ?>)" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="editarCliente(<?php echo $cli['id_cliente']; ?>)" title="Editar cliente">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Cliente -->
<div class="modal fade" id="modalCrearCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-plus mr-2"></i> Crear Nuevo Cliente
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formCrearCliente">
                <div class="modal-body p-4">
                    <div id="alertContainer"></div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <div class="form-group">
                                <label for="nombre_cliente" class="form-label">Nombre del Cliente *</label>
                                <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" placeholder="Ingrese el nombre completo" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="tipo_cliente" class="form-label">Tipo *</label>
                                <select class="form-control" id="tipo_cliente" name="tipo_cliente" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="empresa">Empresa</option>
                                    <option value="persona">Persona</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nit" class="form-label">NIT *</label>
                                <input type="text" class="form-control" id="nit" name="nit" placeholder="Número de identificación tributaria" required>
                                <small class="form-text text-muted">El NIT debe ser único para cada cliente.</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Número de contacto">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección completa">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="correo@ejemplo.com">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success" id="btnCrearCliente">
                        <i class="fas fa-save mr-1"></i> Crear Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Cliente -->
<div class="modal fade" id="modalVerCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-eye mr-2"></i> Detalles del Cliente
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-primary"><i class="fas fa-id-card"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">ID Cliente</span>
                                <span class="info-box-number" id="ver_id_cliente">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-success"><i class="fas fa-user"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nombre</span>
                                <span class="info-box-number" id="ver_nombre_cliente">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-tag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tipo</span>
                                <span class="info-box-number" id="ver_tipo_cliente">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-warning"><i class="fas fa-hashtag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">NIT</span>
                                <span class="info-box-number" id="ver_nit">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-info"><i class="fas fa-phone"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Teléfono</span>
                                <span class="info-box-number" id="ver_telefono_cliente">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-box bg-light">
                            <span class="info-box-icon bg-danger"><i class="fas fa-envelope"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Correo Electrónico</span>
                                <span class="info-box-number" id="ver_correo_cliente">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-box bg-light">
                    <span class="info-box-icon bg-dark"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dirección</span>
                        <span class="info-box-number" id="ver_direccion">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-edit mr-2"></i> Editar Cliente
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formEditarCliente">
                <input type="hidden" id="editar_id_cliente" name="id_cliente">
                <div class="modal-body p-4">
                    <div id="alertContainerEditarCliente"></div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <div class="form-group">
                                <label for="editar_nombre_cliente" class="form-label">Nombre del Cliente *</label>
                                <input type="text" class="form-control" id="editar_nombre_cliente" name="nombre_cliente" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="editar_tipo_cliente" class="form-label">Tipo *</label>
                                <select class="form-control" id="editar_tipo_cliente" name="tipo_cliente" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="empresa">Empresa</option>
                                    <option value="persona">Persona</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editar_nit" class="form-label">NIT *</label>
                                <input type="text" class="form-control" id="editar_nit" name="nit" required>
                                <small class="form-text text-muted">El NIT debe ser único para cada cliente.</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editar_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="editar_telefono" name="telefono">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="editar_direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="editar_direccion" name="direccion">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="editar_correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="editar_correo" name="correo">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning" id="btnEditarCliente">
                        <i class="fas fa-save mr-1"></i> Actualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/layout/footer.php'; ?>

<script>
// Función para validar el formulario
function validarFormulario() {
    const nombre = $('#nombre_cliente').val().trim();
    const tipo = $('#tipo_cliente').val();
    const nit = $('#nit').val().trim();
    
    if (!nombre) {
        mostrarAlerta('El nombre del cliente es obligatorio', 'danger');
        return false;
    }
    
    if (!tipo) {
        mostrarAlerta('Debe seleccionar un tipo de cliente', 'danger');
        return false;
    }
    
    if (!nit) {
        mostrarAlerta('El NIT es obligatorio', 'danger');
        return false;
    }
    
    // Si todo está bien, limpiar alertas y retornar true
    $('#alertContainer').empty();
    return true;
}

// Función para validar el formulario de edición
function validarFormularioEditarCliente() {
    const nombre = $('#editar_nombre_cliente').val().trim();
    const tipo = $('#editar_tipo_cliente').val();
    const nit = $('#editar_nit').val().trim();
    
    if (!nombre) {
        mostrarAlertaEditarCliente('El nombre del cliente es obligatorio', 'danger');
        return false;
    }
    
    if (!tipo) {
        mostrarAlertaEditarCliente('Debe seleccionar un tipo de cliente', 'danger');
        return false;
    }
    
    if (!nit) {
        mostrarAlertaEditarCliente('El NIT es obligatorio', 'danger');
        return false;
    }
    
    // Si todo está bien, limpiar alertas y retornar true
    $('#alertContainerEditarCliente').empty();
    return true;
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    $('#alertContainer').html(
        `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="fas fa-${tipo === 'danger' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`
    );
}

// Función para mostrar alertas en el modal de edición
function mostrarAlertaEditarCliente(mensaje, tipo) {
    $('#alertContainerEditarCliente').html(
        `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="fas fa-${tipo === 'danger' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`
    );
}

// Función para ver los detalles de un cliente
function verCliente(id) {
    console.log("Solicitando datos para cliente ID:", id);
    
    $.ajax({
        url: 'ajax/obtener_cliente.php',
        type: 'POST',
        data: { id_cliente: id },
        dataType: 'json',
        success: function(response) {
            console.log("Respuesta recibida:", response);
            
            if (response.success) {
                const cliente = response.data;
                console.log("Datos del cliente:", cliente);
                
                // Llenar los campos del modal de ver
                $('#ver_id_cliente').text(cliente.id_cliente || 'N/A');
                $('#ver_nombre_cliente').text(cliente.nombre_cliente || 'N/A');
                $('#ver_tipo_cliente').text(cliente.tipo_cliente ? cliente.tipo_cliente.charAt(0).toUpperCase() + cliente.tipo_cliente.slice(1) : '-');
                $('#ver_nit').text(cliente.nit || '-');
                $('#ver_telefono_cliente').text(cliente.telefono || '-');
                $('#ver_correo_cliente').text(cliente.correo || '-');
                $('#ver_direccion').text(cliente.direccion || '-');
                
                // Mostrar el modal
                $('#modalVerCliente').modal('show');
            } else {
                console.error("Error en respuesta:", response.message);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al cargar los datos del cliente'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            console.error("Status:", status);
            console.error("Respuesta completa:", xhr.responseText);
            
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'Error al cargar los datos del cliente: ' + error
            });
        }
    });
}

// Función para editar un cliente
function editarCliente(id) {
    console.log("Editando cliente ID:", id);
    
    $.ajax({
        url: 'ajax/obtener_cliente.php',
        type: 'POST',
        data: { id_cliente: id },
        dataType: 'json',
        success: function(response) {
            console.log("Respuesta para edición:", response);
            
            if (response.success) {
                const cliente = response.data;
                
                // Llenar los campos del formulario de edición
                $('#editar_id_cliente').val(cliente.id_cliente);
                $('#editar_nombre_cliente').val(cliente.nombre_cliente || '');
                $('#editar_tipo_cliente').val(cliente.tipo_cliente || '');
                $('#editar_nit').val(cliente.nit || '');
                $('#editar_telefono').val(cliente.telefono || '');
                $('#editar_direccion').val(cliente.direccion || '');
                $('#editar_correo').val(cliente.correo || '');
                
                // Limpiar alertas
                $('#alertContainerEditarCliente').empty();
                
                // Mostrar el modal
                $('#modalEditarCliente').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al cargar los datos del cliente'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud de edición:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los datos del cliente: ' + error
            });
        }
    });
}

// Cuando el documento esté listo
$(document).ready(function() {
    console.log("Documento listo, inicializando DataTable...");
    
    // Inicializar DataTable con paginación cada 10 registros
    $('#tablaClientes').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10, // Mostrar 10 registros por página
        "lengthMenu": [10, 25, 50, 100], // Opciones de cantidad de registros por página
        "order": [[0, "asc"]], // Ordenar por la primera columna (ID) de forma ascendente
        "drawCallback": function(settings) {
            // Actualizar información de paginación
            const api = this.api();
            const pageInfo = api.page.info();
            console.log(`Página ${pageInfo.page + 1} de ${pageInfo.pages} páginas`);
        }
    });

    // Manejar envío del formulario de crear cliente
    $('#formCrearCliente').on('submit', function(e) {
        console.log("Formulario enviado, previniendo comportamiento por defecto...");
        
        // Prevenir el envío tradicional del formulario
        e.preventDefault();
        
        // Validar campos requeridos
        if (!validarFormulario()) {
            console.log("Validación fallida");
            return;
        }
        
        console.log("Enviando datos por AJAX...");
        
        // Mostrar indicador de carga
        $('#btnCrearCliente').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...').prop('disabled', true);
        
        // Obtener los datos del formulario
        const formData = $(this).serialize();
        console.log("Datos a enviar:", formData);
        
        // Enviar datos por AJAX
        $.ajax({
            url: 'ajax/crear_cliente.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta recibida:", response);
                $('#btnCrearCliente').html('<i class="fas fa-save mr-1"></i>Crear Cliente').prop('disabled', false);
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalCrearCliente').modal('hide');
                        // Limpiar formulario
                        $('#formCrearCliente')[0].reset();
                        $('#alertContainer').empty();
                        // Recargar la página para ver el nuevo cliente
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud:", error);
                $('#btnCrearCliente').html('<i class="fas fa-save mr-1"></i>Crear Cliente').prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la solicitud: ' + error
                });
            }
        });
    });

    // Manejar envío del formulario de edición
    $('#formEditarCliente').on('submit', function(e) {
        console.log("Formulario de edición de cliente enviado...");
        
        // Prevenir el envío tradicional del formulario
        e.preventDefault();
        
        // Validar campos requeridos
        if (!validarFormularioEditarCliente()) {
            console.log("Validación fallida");
            return;
        }
        
        console.log("Enviando datos de edición por AJAX...");
        
        // Mostrar indicador de carga
        $('#btnEditarCliente').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...').prop('disabled', true);
        
        // Obtener los datos del formulario
        const formData = $(this).serialize();
        console.log("Datos a enviar:", formData);
        
        // Enviar datos por AJAX
        $.ajax({
            url: 'ajax/editar_cliente.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta recibida:", response);
                $('#btnEditarCliente').html('<i class="fas fa-save mr-1"></i>Actualizar Cliente').prop('disabled', false);
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalEditarCliente').modal('hide');
                        // Recargar la página para ver los cambios
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud:", error);
                $('#btnEditarCliente').html('<i class="fas fa-save mr-1"></i>Actualizar Cliente').prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la solicitud: ' + error
                });
            }
        });
    });
});
</script>