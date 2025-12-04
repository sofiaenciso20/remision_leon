<?php
// listar_remisiones.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Remision.php';
require_once __DIR__ . '/models/Cliente.php';

// Crear conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Instanciar el modelo Remision pasando la conexión
$remision = new Remision($db);
$cliente = new Cliente($db);

$termino = $_GET['buscar'] ?? '';
$id_cliente = $_GET['id_cliente'] ?? '';
$id_persona = $_GET['id_persona'] ?? '';
$fecha_creacion = $_GET['fecha_creacion'] ?? '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;

// Obtener el total de remisiones para la paginación
$total_remisiones = $remision->contarRemisiones($termino, $fecha_creacion, '', $id_cliente, $id_persona);
$total_paginas = ceil($total_remisiones / $por_pagina);
$offset = ($pagina - 1) * $por_pagina;

// Obtener remisiones con paginación y ordenadas por ID de menor a mayor
$stmt = $remision->obtenerRemisionesPaginadas($termino, $fecha_creacion, '', $offset, $por_pagina, $id_cliente, $id_persona);
$remisiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'views/layout/header.php';
?>

<style>
/* Estilos minimalistas basados en inventario.php */
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

/* Mejoras para filtros */
.filter-card {
    border-left: 4px solid #6c757d !important;
}

.filter-card .card-body {
    padding: 1.25rem;
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

/* Paginación */
.pagination .page-link {
    border-radius: 0.375rem;
    margin: 0 2px;
    border: 1px solid #dee2e6;
    color: #495057;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

/* Observaciones */
.observaciones-responsive {
    max-height: 200px;
    overflow-y: auto;
    word-wrap: break-word;
    white-space: pre-wrap;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Select2 */
.select2-container--default .select2-selection--single {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    height: auto;
    padding: 0.375rem 0.75rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5;
    padding: 0;
}
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-file-invoice mr-2"></i>Listado de Remisiones
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
                <a href="index.php" class="btn btn-success">
                    <i class="fas fa-plus mr-1"></i> Nueva Remisión
                </a>
                <a href="inventario.php" class="btn btn-outline-secondary">
                    <i class="fas fa-boxes mr-1"></i> Ir a Inventario
                </a>
            </div>
        </div>

        <!-- Filtros -->
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
                    <form method="GET" id="formFiltros">
                        <div class="row">
                            <div class="col-md-6 col-lg-3 mb-3">
                                <label for="buscar" class="form-label fw-semibold">Palabra o Identificación</label>
                                <input type="text" class="form-control" id="buscar" name="buscar" 
                                       placeholder="Número de remisión, NIT..." 
                                       value="<?php echo htmlspecialchars($termino); ?>">
                                <small class="form-text text-muted">Busca en número, cliente o NIT</small>
                            </div>

                            <div class="col-md-6 col-lg-3 mb-3">
                                <label for="id_cliente" class="form-label fw-semibold">Empresa/Cliente</label>
                                <select class="form-control select2-cliente" id="id_cliente" name="id_cliente" style="width: 100%;">
                                    <option value="">Todos los clientes</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-3 mb-3">
                                <label for="id_persona" class="form-label fw-semibold">Persona Encargada</label>
                                <select class="form-control select2-persona" id="id_persona" name="id_persona" style="width: 100%;">
                                    <option value="">Todas las personas</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-3 mb-3">
                                <label for="fecha_creacion" class="form-label fw-semibold">Fecha de Creación</label>
                                <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion" 
                                       value="<?php echo htmlspecialchars($fecha_creacion); ?>">
                                <small class="form-text text-muted">Filtra por fecha exacta</small>
                            </div>

                            <div class="col-md-12 mb-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i> Buscar
                                </button>
                                <a href="listar_remisiones.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-eraser mr-1"></i> Limpiar Filtros
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Información de resultados -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-light fade-in">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            <strong>Mostrando <?php echo count($remisiones); ?> de <?php echo $total_remisiones; ?> remisiones</strong>
                        </div>
                        <?php if ($total_paginas > 0): ?>
                            <div class="text-muted">
                                Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de remisiones -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">
                    <i class="fas fa-file-invoice mr-2"></i> Lista de Remisiones
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Número</th>
                                <th class="border-0">Fecha</th>
                                <th class="border-0">Cliente</th>
                                <th class="border-0">NIT</th>
                                <th class="border-0">Persona Contacto</th>
                                <th class="border-0">Teléfono Contacto</th>
                                <th class="border-0 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($remisiones)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 empty-state">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-3">No se encontraron remisiones</h5>
                                        <p class="text-muted mb-4">No hay remisiones que coincidan con los filtros aplicados</p>
                                        <a href="listar_remisiones.php" class="btn btn-primary">
                                            <i class="fas fa-eraser mr-1"></i> Limpiar filtros
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($remisiones as $rem): ?>
                                    <tr class="fade-in">
                                        <td>
                                            <strong>#<?php echo $rem['numero_remision'] ?? 'N/A'; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo isset($rem['fecha_emision']) ? date('d/m/Y', strtotime($rem['fecha_emision'])) : 'N/A'; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($rem['nombre_cliente'] ?? 'N/A'); ?></div>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo htmlspecialchars($rem['nit'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($rem['nombre_persona'])): ?>
                                                <?php echo htmlspecialchars($rem['nombre_persona']); ?>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($rem['telefono_persona'])): ?>
                                                <span class="text-muted"><?php echo htmlspecialchars($rem['telefono_persona']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="verRemision(<?php echo $rem['id_remision'] ?? 0; ?>)"
                                                        title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="generar_pdf.php?id=<?php echo $rem['id_remision'] ?? 0; ?>" 
                                                   target="_blank" class="btn btn-outline-secondary"
                                                   title="Generar PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
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

        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <nav aria-label="Paginación">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($pagina > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?>&buscar=<?php echo urlencode($termino); ?>&id_cliente=<?php echo urlencode($id_cliente); ?>&id_persona=<?php echo urlencode($id_persona); ?>&fecha_creacion=<?php echo urlencode($fecha_creacion); ?>">
                                        <i class="fas fa-chevron-left mr-1"></i> Anterior
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left mr-1"></i> Anterior</span>
                                </li>
                            <?php endif; ?>

                            <?php 
                            $inicio = max(1, $pagina - 2);
                            $fin = min($total_paginas, $pagina + 2);
                            
                            for ($i = $inicio; $i <= $fin; $i++): 
                            ?>
                                <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($termino); ?>&id_cliente=<?php echo urlencode($id_cliente); ?>&id_persona=<?php echo urlencode($id_persona); ?>&fecha_creacion=<?php echo urlencode($fecha_creacion); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagina < $total_paginas): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?>&buscar=<?php echo urlencode($termino); ?>&id_cliente=<?php echo urlencode($id_cliente); ?>&id_persona=<?php echo urlencode($id_persona); ?>&fecha_creacion=<?php echo urlencode($fecha_creacion); ?>">
                                        Siguiente <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Siguiente <i class="fas fa-chevron-right ml-1"></i></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?> 
                            | Total de remisiones: <?php echo $total_remisiones; ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para ver detalles de remisión -->
<div class="modal fade" id="modalVerRemision" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0">
                    <i class="fas fa-file-invoice mr-2"></i> Detalles de Remisión
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="contenidoRemision">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                    <p class="text-muted">Cargando detalles de la remisión...</p>
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

<script>
$(document).ready(function() {
    $('.select2-cliente').select2({
        placeholder: 'Todos los clientes',
        allowClear: true,
        width: '100%',
        ajax: {
            url: 'ajax/buscar_clientes.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    termino: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('.select2-persona').select2({
        placeholder: 'Todas las personas',
        allowClear: true,
        width: '100%',
        ajax: {
            url: 'ajax/buscar_personas_contacto.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    termino: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    <?php if (!empty($id_cliente)): ?>
        $.ajax({
            url: 'ajax/obtener_cliente.php',
            method: 'POST',
            data: { id_cliente: <?php echo $id_cliente; ?> },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var option = new Option(response.data.nombre_cliente, response.data.id_cliente, true, true);
                    $('.select2-cliente').append(option).trigger('change');
                }
            }
        });
    <?php endif; ?>

    <?php if (!empty($id_persona)): ?>
        $.ajax({
            url: 'ajax/obtener_persona_contacto.php',
            method: 'POST',
            data: { id_persona: <?php echo $id_persona; ?> },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var option = new Option(response.data.nombre_persona, response.data.id_persona, true, true);
                    $('.select2-persona').append(option).trigger('change');
                }
            }
        });
    <?php endif; ?>
});

function verRemision(id) {
    if (id === 0) {
        Swal.fire('Error', 'ID de remisión no válido', 'error');
        return;
    }
    
    $('#contenidoRemision').html(`
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
            <p class="text-muted">Cargando detalles de la remisión...</p>
        </div>
    `);
    
    $.ajax({
        url: 'ajax/ver_remision.php',
        method: 'POST',
        data: { id_remision: id },
        success: function(response) {
            $('#contenidoRemision').html(response);
            $('#modalVerRemision').modal('show');
        },
        error: function() {
            $('#contenidoRemision').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Error al cargar los detalles de la remisión
                </div>
            `);
        }
    });
}
</script>

<?php include 'views/layout/footer.php'; ?>