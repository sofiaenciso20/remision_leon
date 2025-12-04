<?php
// filepath: c:\xampp\htdocs\remisiones\PersonasContacto.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/PersonaContacto.php';
require_once __DIR__ . '/models/Cliente.php';

$database = new Database();
$db = $database->getConnection();
$personaContacto = new PersonaContacto($db);
$cliente = new Cliente($db);

// Obtener todas las personas de contacto ORDENADAS por ID de menor a mayor
$stmt = $personaContacto->obtenerTodos();
$personas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ordenar las personas por ID de menor a mayor
usort($personas, function($a, $b) {
    return $a['id_persona'] - $b['id_persona'];
});

// Obtener todos los clientes para el dropdown
$stmtClientes = $cliente->obtenerTodos();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

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

/* Text truncate */
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Detalles persona contacto */
.form-control-plaintext {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
}
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-address-book mr-2"></i>Gestión de Personas de Contacto
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
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCrearPersonaContacto">
                    <i class="fas fa-plus mr-1"></i> Nueva Persona de Contacto
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
                            <strong>Mostrando <?php echo count($personas); ?> persona(s) de contacto</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de personas de contacto -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">
                    <i class="fas fa-list mr-2"></i> Personas de Contacto Registradas
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaPersonasContacto">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">ID</th>
                                <th class="border-0">Nombre</th>
                                <th class="border-0">Cargo</th>
                                <th class="border-0">Teléfono</th>
                                <th class="border-0">Correo</th>
                                <th class="border-0">Cliente</th>
                                <th class="border-0 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($personas)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 empty-state">
                                        <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-3">No se encontraron personas de contacto</h5>
                                        <p class="text-muted mb-4">No hay personas de contacto registradas en el sistema</p>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearPersonaContacto">
                                            <i class="fas fa-plus mr-1"></i> Agregar primera persona
                                        </button>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($personas as $persona): ?>
                                <tr class="fade-in">
                                    <td>
                                        <span class="text-muted">#<?php echo htmlspecialchars($persona['id_persona']); ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($persona['nombre_persona']); ?></div>
                                    </td>
                                    <td>
                                        <?php if (!empty($persona['cargo'])): ?>
                                            <span class="text-muted"><?php echo htmlspecialchars($persona['cargo']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($persona['telefono'])): ?>
                                            <span class="text-muted"><?php echo htmlspecialchars($persona['telefono']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($persona['correo'])): ?>
                                            <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?php echo htmlspecialchars($persona['correo']); ?>">
                                                <?php echo htmlspecialchars($persona['correo']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        // Buscar el nombre del cliente asociado
                                        $nombreCliente = '-';
                                        foreach ($clientes as $cli) {
                                            if ($cli['id_cliente'] == $persona['id_cliente']) {
                                                $nombreCliente = htmlspecialchars($cli['nombre_cliente']);
                                                break;
                                            }
                                        }
                                        echo $nombreCliente;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-info" onclick="verPersonaContacto(<?php echo $persona['id_persona']; ?>)" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="editarPersonaContacto(<?php echo $persona['id_persona']; ?>)" title="Editar persona">
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

<!-- Modal Crear Persona de Contacto -->
<div class="modal fade" id="modalCrearPersonaContacto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-plus mr-2"></i> Crear Nueva Persona de Contacto
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formCrearPersonaContacto">
                <div class="modal-body p-4">
                    <div id="alertContainerPersona"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nombre_persona" class="form-label">Nombre de la Persona *</label>
                                <input type="text" class="form-control" id="nombre_persona" name="nombre_persona" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="cargo" class="form-label">Cargo</label>
                                <input type="text" class="form-control" id="cargo" name="cargo">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_cliente" class="form-label">Cliente *</label>
                        <select class="form-control" id="id_cliente" name="id_cliente" required>
                            <option value="">Seleccionar Cliente...</option>
                            <?php foreach ($clientes as $clienteOption): ?>
                                <option value="<?php echo $clienteOption['id_cliente']; ?>">
                                    <?php echo htmlspecialchars($clienteOption['nombre_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success" id="btnCrearPersonaContacto">
                        <i class="fas fa-save mr-1"></i> Crear Persona de Contacto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Persona de Contacto -->
<div class="modal fade" id="modalVerPersonaContacto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-eye mr-2"></i> Detalles de Persona de Contacto
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">ID</label>
                            <div class="form-control-plaintext" id="ver_id_persona">-</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Nombre</label>
                            <div class="form-control-plaintext" id="ver_nombre_persona">-</div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Cargo</label>
                            <div class="form-control-plaintext" id="ver_cargo">-</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <div class="form-control-plaintext" id="ver_telefono">-</div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="form-control-plaintext" id="ver_correo">-</div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label fw-semibold">Cliente</label>
                            <div class="form-control-plaintext" id="ver_cliente">-</div>
                        </div>
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

<!-- Modal Editar Persona de Contacto -->
<div class="modal fade" id="modalEditarPersonaContacto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-edit mr-2"></i> Editar Persona de Contacto
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formEditarPersonaContacto">
                <input type="hidden" id="editar_id_persona" name="id_persona">
                <div class="modal-body p-4">
                    <div id="alertContainerEditarPersona"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editar_nombre_persona" class="form-label">Nombre de la Persona *</label>
                                <input type="text" class="form-control" id="editar_nombre_persona" name="nombre_persona" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editar_cargo" class="form-label">Cargo</label>
                                <input type="text" class="form-control" id="editar_cargo" name="cargo">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editar_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="editar_telefono" name="telefono">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editar_correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="editar_correo" name="correo">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editar_id_cliente" class="form-label">Cliente *</label>
                        <select class="form-control" id="editar_id_cliente" name="id_cliente" required>
                            <option value="">Seleccionar Cliente...</option>
                            <?php foreach ($clientes as $clienteOption): ?>
                                <option value="<?php echo $clienteOption['id_cliente']; ?>">
                                    <?php echo htmlspecialchars($clienteOption['nombre_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning" id="btnEditarPersonaContacto">
                        <i class="fas fa-save mr-1"></i> Actualizar Persona de Contacto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/layout/footer.php'; ?>

<script>
// Función para validar el formulario de persona de contacto
function validarFormularioPersona() {
    const nombre = $('#nombre_persona').val().trim();
    const idCliente = $('#id_cliente').val();
    
    if (!nombre) {
        mostrarAlertaPersona('El nombre de la persona es obligatorio', 'danger');
        return false;
    }
    
    if (!idCliente) {
        mostrarAlertaPersona('Debe seleccionar un cliente', 'danger');
        return false;
    }
    
    // Si todo está bien, limpiar alertas y retornar true
    $('#alertContainerPersona').empty();
    return true;
}

// Función para validar el formulario de edición
function validarFormularioEditarPersona() {
    const nombre = $('#editar_nombre_persona').val().trim();
    const idCliente = $('#editar_id_cliente').val();
    
    if (!nombre) {
        mostrarAlertaEditarPersona('El nombre de la persona es obligatorio', 'danger');
        return false;
    }
    
    if (!idCliente) {
        mostrarAlertaEditarPersona('Debe seleccionar un cliente', 'danger');
        return false;
    }
    
    // Si todo está bien, limpiar alertas y retornar true
    $('#alertContainerEditarPersona').empty();
    return true;
}

// Función para mostrar alertas en el modal de persona
function mostrarAlertaPersona(mensaje, tipo) {
    $('#alertContainerPersona').html(
        `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="fas fa-${tipo === 'danger' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`
    );
}

// Función para mostrar alertas en el modal de edición
function mostrarAlertaEditarPersona(mensaje, tipo) {
    $('#alertContainerEditarPersona').html(
        `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="fas fa-${tipo === 'danger' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`
    );
}

// Función para ver los detalles de una persona de contacto - VERSIÓN CORREGIDA
function verPersonaContacto(id) {
    console.log("Solicitando datos para persona ID:", id);
    
    $.ajax({
        url: 'ajax/obtener_persona_contacto.php',
        type: 'POST',
        data: { id_persona: id },
        dataType: 'json',
        success: function(response) {
            console.log("Respuesta recibida:", response);
            
            if (response.success) {
                const persona = response.data;
                console.log("Datos de persona:", persona);
                
                // Llenar los campos del modal de ver
                $('#ver_id_persona').text(persona.id_persona || 'N/A');
                $('#ver_nombre_persona').text(persona.nombre_persona || 'N/A');
                $('#ver_cargo').text(persona.cargo || '-');
                $('#ver_telefono').text(persona.telefono || '-');
                $('#ver_correo').text(persona.correo || '-');
                $('#ver_cliente').text(persona.nombre_cliente || '-');
                
                // Mostrar el modal
                $('#modalVerPersonaContacto').modal('show');
            } else {
                console.error("Error en respuesta:", response.message);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al cargar los datos de la persona de contacto'
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
                text: 'Error al cargar los datos de la persona de contacto: ' + error
            });
        }
    });
}

// Función para editar una persona de contacto - VERSIÓN CORREGIDA
function editarPersonaContacto(id) {
    console.log("Editando persona ID:", id);
    
    $.ajax({
        url: 'ajax/obtener_persona_contacto.php',
        type: 'POST',
        data: { id_persona: id },
        dataType: 'json',
        success: function(response) {
            console.log("Respuesta para edición:", response);
            
            if (response.success) {
                const persona = response.data;
                
                // Llenar los campos del formulario de edición
                $('#editar_id_persona').val(persona.id_persona);
                $('#editar_nombre_persona').val(persona.nombre_persona || '');
                $('#editar_cargo').val(persona.cargo || '');
                $('#editar_telefono').val(persona.telefono || '');
                $('#editar_correo').val(persona.correo || '');
                $('#editar_id_cliente').val(persona.id_cliente);
                
                // Limpiar alertas
                $('#alertContainerEditarPersona').empty();
                
                // Mostrar el modal
                $('#modalEditarPersonaContacto').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al cargar los datos de la persona de contacto'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud de edición:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar los datos de la persona de contacto: ' + error
            });
        }
    });
}

// Cuando el documento esté listo
$(document).ready(function() {
    console.log("Documento listo, inicializando DataTable para personas de contacto...");
    
    // Inicializar DataTable con paginación cada 10 registros
    $('#tablaPersonasContacto').DataTable({
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

    // Manejar envío del formulario de persona de contacto
    $('#formCrearPersonaContacto').on('submit', function(e) {
        console.log("Formulario de persona de contacto enviado, previniendo comportamiento por defecto...");
        
        // Prevenir el envío tradicional del formulario
        e.preventDefault();
        
        // Validar campos requeridos
        if (!validarFormularioPersona()) {
            console.log("Validación fallida");
            return;
        }
        
        console.log("Enviando datos por AJAX...");
        
        // Mostrar indicador de carga
        $('#btnCrearPersonaContacto').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...').prop('disabled', true);
        
        // Obtener los datos del formulario
        const formData = $(this).serialize();
        console.log("Datos a enviar:", formData);
        
        // Enviar datos por AJAX
        $.ajax({
            url: 'ajax/crear_persona_contacto.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta recibida:", response);
                $('#btnCrearPersonaContacto').html('<i class="fas fa-save mr-1"></i> Crear Persona de Contacto').prop('disabled', false);
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalCrearPersonaContacto').modal('hide');
                        // Limpiar formulario
                        $('#formCrearPersonaContacto')[0].reset();
                        $('#alertContainerPersona').empty();
                        // Recargar la página para ver la nueva persona
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
                $('#btnCrearPersonaContacto').html('<i class="fas fa-save mr-1"></i> Crear Persona de Contacto').prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al procesar la solicitud: ' + error
                });
            }
        });
    });

    // Manejar envío del formulario de edición
    $('#formEditarPersonaContacto').on('submit', function(e) {
        console.log("Formulario de edición de persona de contacto enviado...");
        
        // Prevenir el envío tradicional del formulario
        e.preventDefault();
        
        // Validar campos requeridos
        if (!validarFormularioEditarPersona()) {
            console.log("Validación fallida");
            return;
        }
        
        console.log("Enviando datos de edición por AJAX...");
        
        // Mostrar indicador de carga
        $('#btnEditarPersonaContacto').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...').prop('disabled', true);
        
        // Obtener los datos del formulario
        const formData = $(this).serialize();
        console.log("Datos a enviar:", formData);
        
        // Enviar datos por AJAX
        $.ajax({
            url: 'ajax/editar_persona_contacto.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta recibida:", response);
                $('#btnEditarPersonaContacto').html('<i class="fas fa-save mr-1"></i> Actualizar Persona de Contacto').prop('disabled', false);
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalEditarPersonaContacto').modal('hide');
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
                $('#btnEditarPersonaContacto').html('<i class="fas fa-save mr-1"></i> Actualizar Persona de Contacto').prop('disabled', false);
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