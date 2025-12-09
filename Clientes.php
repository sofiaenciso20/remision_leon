<?php
// filepath: c:\xampp\htdocs\remisiones\Clientes.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Cliente.php';

// Incluir cabecera
include __DIR__ . '/views/layout/header.php';
?>

<style>
/* Estilos consistentes */
.content-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.04);
}
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
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

        <!-- Tabla de clientes -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h4 class="card-title mb-2 mb-md-0">
                        <i class="fas fa-list mr-2"></i> Clientes Registrados
                    </h4>
                    <!-- Buscador -->
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" id="buscador" class="form-control" placeholder="Buscar por nombre o NIT...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="cargarClientes(1)">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                        <tbody id="tabla-clientes-body">
                            <!-- Los datos se cargarán aquí vía AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center">
                    <div id="info-paginacion"></div>
                    <nav>
                        <ul class="pagination mb-0" id="paginacion-controles">
                            <!-- Los controles de paginación se generarán aquí -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales (Crear, Ver, Editar) -->
<!-- El código de los modales se mantiene igual que en tu archivo original -->
<!-- Modal Crear Cliente -->
<div class="modal fade" id="modalCrearCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0"><i class="fas fa-plus mr-2"></i> Crear Nuevo Cliente</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formCrearCliente">
                <div class="modal-body p-4">
                    <!-- Contenido del formulario de creación -->
                     <div class="row">
                        <div class="col-md-8 mb-3"><div class="form-group"><label for="nombre_cliente" class="form-label">Nombre del Cliente *</label><input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required></div></div>
                        <div class="col-md-4 mb-3"><div class="form-group"><label for="tipo_cliente" class="form-label">Tipo *</label><select class="form-control" id="tipo_cliente" name="tipo_cliente" required><option value="persona">Persona</option><option value="empresa">Empresa</option></select></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><div class="form-group"><label for="nit" class="form-label">NIT/Cédula *</label><input type="text" class="form-control" id="nit" name="nit" required></div></div>
                        <div class="col-md-6 mb-3"><div class="form-group"><label for="telefono_cliente" class="form-label">Teléfono</label><input type="text" class="form-control" id="telefono_cliente" name="telefono"></div></div>
                    </div>
                    <div class="form-group mb-3"><label for="direccion" class="form-label">Dirección</label><input type="text" class="form-control" id="direccion" name="direccion"></div>
                    <div class="form-group mb-3"><label for="correo_cliente" class="form-label">Correo Electrónico</label><input type="email" class="form-control" id="correo_cliente" name="correo"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Cliente -->
<div class="modal fade" id="modalVerCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
             <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-eye mr-2"></i> Detalles del Cliente</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
             <div class="modal-body p-4">
                <div id="detalles-cliente-body"></div>
             </div>
             <div class="modal-footer bg-light"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cerrar</button></div>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" role="dialog">
     <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-edit mr-2"></i> Editar Cliente</h4><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
            <form id="formEditarCliente">
                <input type="hidden" id="editar_id_cliente" name="id_cliente">
                <div class="modal-body p-4">
                    <!-- Contenido del formulario de edición -->
                    <div class="row">
                        <div class="col-md-8 mb-3"><div class="form-group"><label for="editar_nombre_cliente" class="form-label">Nombre del Cliente *</label><input type="text" class="form-control" id="editar_nombre_cliente" name="nombre_cliente" required></div></div>
                        <div class="col-md-4 mb-3"><div class="form-group"><label for="editar_tipo_cliente" class="form-label">Tipo *</label><select class="form-control" id="editar_tipo_cliente" name="tipo_cliente" required><option value="persona">Persona</option><option value="empresa">Empresa</option></select></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><div class="form-group"><label for="editar_nit" class="form-label">NIT/Cédula *</label><input type="text" class="form-control" id="editar_nit" name="nit" required></div></div>
                        <div class="col-md-6 mb-3"><div class="form-group"><label for="editar_telefono" class="form-label">Teléfono</label><input type="text" class="form-control" id="editar_telefono" name="telefono"></div></div>
                    </div>
                    <div class="form-group mb-3"><label for="editar_direccion" class="form-label">Dirección</label><input type="text" class="form-control" id="editar_direccion" name="direccion"></div>
                    <div class="form-group mb-3"><label for="editar_correo" class="form-label">Correo</label><input type="email" class="form-control" id="editar_correo" name="correo"></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancelar</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Actualizar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include __DIR__ . '/views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Cargar la primera página de clientes al iniciar
    cargarClientes(1);

    // Búsqueda al presionar Enter en el input
    $('#buscador').on('keypress', function(e) {
        if (e.which === 13) { // 13 es el código de la tecla Enter
            cargarClientes(1);
        }
    });

    // --- MANEJO DE MODALES ---
    // Crear cliente
    $('#formCrearCliente').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/crear_cliente.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalCrearCliente').modal('hide');
                    Swal.fire('¡Éxito!', 'Cliente creado correctamente', 'success');
                    cargarClientes(1); // Recargar la tabla
                } else {
                    Swal.fire('Error', response.message || 'No se pudo crear el cliente.', 'error');
                }
            }
        });
    });

    // Editar cliente
    $('#formEditarCliente').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/editar_cliente.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalEditarCliente').modal('hide');
                    Swal.fire('¡Éxito!', 'Cliente actualizado correctamente', 'success');
                    cargarClientes($('#paginacion-controles .active .page-link').text() || 1); // Recargar la página actual
                } else {
                    Swal.fire('Error', response.message || 'No se pudo actualizar el cliente.', 'error');
                }
            }
        });
    });
});

function cargarClientes(pagina) {
    const busqueda = $('#buscador').val();

    $.ajax({
        url: 'ajax/listar_clientes.php',
        method: 'POST',
        data: {
            pagina: pagina,
            busqueda: busqueda
        },
        dataType: 'json',
        beforeSend: function() {
            $('#tabla-clientes-body').html('<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div></td></tr>');
        },
        success: function(response) {
            if (response.success) {
                const clientes = response.clientes;
                const paginacion = response.paginacion;

                // Limpiar tabla
                $('#tabla-clientes-body').empty();

                // Llenar tabla con nuevos datos
                if (clientes.length > 0) {
                    clientes.forEach(function(cliente) {
                        $('#tabla-clientes-body').append(`
                            <tr>
                                <td>#${cliente.id_cliente}</td>
                                <td>${cliente.nombre_cliente}</td>
                                <td><span class="badge bg-${cliente.tipo_cliente === 'empresa' ? 'primary' : 'secondary'}">${cliente.tipo_cliente}</span></td>
                                <td>${cliente.nit}</td>
                                <td>${cliente.telefono || '-'}</td>
                                <td>${cliente.correo || '-'}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info" onclick="verCliente(${cliente.id_cliente})"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-warning" onclick="editarCliente(${cliente.id_cliente})"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    $('#tabla-clientes-body').append('<tr><td colspan="7" class="text-center empty-state"><i class="fas fa-search fa-2x text-muted mb-3"></i><p>No se encontraron clientes para la búsqueda.</p></td></tr>');
                }

                // Actualizar controles de paginación
                actualizarPaginacion(paginacion);
            } else {
                Swal.fire('Error', response.message || 'No se pudieron cargar los clientes.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error de comunicación con el servidor.', 'error');
        }
    });
}

function actualizarPaginacion(paginacion) {
    const { pagina_actual, total_paginas, total_clientes } = paginacion;

    $('#paginacion-controles').empty();
    $('#info-paginacion').empty();

    if (total_clientes > 0) {
        $('#info-paginacion').text(`Página ${pagina_actual} de ${total_paginas} (${total_clientes} clientes)`);

        let htmlPaginacion = '';
        const rango = 2;

        // Botón "Anterior"
        htmlPaginacion += `<li class="page-item ${pagina_actual <= 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarClientes(${pagina_actual - 1});">Anterior</a></li>`;

        // Números de página
        for (let i = Math.max(1, pagina_actual - rango); i <= Math.min(total_paginas, pagina_actual + rango); i++) {
            htmlPaginacion += `<li class="page-item ${i === pagina_actual ? 'active' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarClientes(${i});">${i}</a></li>`;
        }

        // Botón "Siguiente"
        htmlPaginacion += `<li class="page-item ${pagina_actual >= total_paginas ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarClientes(${pagina_actual + 1});">Siguiente</a></li>`;

        $('#paginacion-controles').html(htmlPaginacion);
    }
}

function verCliente(id) {
     $.ajax({
        url: 'ajax/obtener_cliente.php',
        type: 'POST',
        data: { id_cliente: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const cliente = response.data;
                const detallesHtml = `
                    <p><strong>ID:</strong> ${cliente.id_cliente}</p>
                    <p><strong>Nombre:</strong> ${cliente.nombre_cliente}</p>
                    <p><strong>Tipo:</strong> ${cliente.tipo_cliente}</p>
                    <p><strong>NIT:</strong> ${cliente.nit}</p>
                    <p><strong>Teléfono:</strong> ${cliente.telefono || '-'}</p>
                    <p><strong>Correo:</strong> ${cliente.correo || '-'}</p>
                    <p><strong>Dirección:</strong> ${cliente.direccion || '-'}</p>
                `;
                $('#detalles-cliente-body').html(detallesHtml);
                $('#modalVerCliente').modal('show');
            } else {
                 Swal.fire('Error', 'No se pudieron cargar los detalles del cliente.', 'error');
            }
        }
    });
}

function editarCliente(id) {
    $.ajax({
        url: 'ajax/obtener_cliente.php',
        type: 'POST',
        data: { id_cliente: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const cliente = response.data;
                $('#editar_id_cliente').val(cliente.id_cliente);
                $('#editar_nombre_cliente').val(cliente.nombre_cliente);
                $('#editar_tipo_cliente').val(cliente.tipo_cliente);
                $('#editar_nit').val(cliente.nit);
                $('#editar_telefono').val(cliente.telefono);
                $('#editar_direccion').val(cliente.direccion);
                $('#editar_correo').val(cliente.correo);
                $('#modalEditarCliente').modal('show');
            } else {
                 Swal.fire('Error', 'No se pudieron cargar los datos para editar.', 'error');
            }
        }
    });
}

</script>

<?php
// Este bloque ya no es necesario, el footer ya está incluido
// include __DIR__ . '/views/layout/footer.php';
?>
