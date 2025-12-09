<?php
// filepath: PersonasResponsables.php
require_once __DIR__ . '/config/database.php';
// No se necesita el modelo Cliente aquí por ahora, se manejará todo desde el modelo PersonaResponsable

include __DIR__ . '/views/layout/header.php';
?>

<style>
/* Estilos consistentes */
.content-header { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6; }
.card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
.table-hover tbody tr:hover { background-color: rgba(0, 123, 255, 0.04); }
.empty-state { text-align: center; padding: 3rem 1rem; }
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <h1 class="m-0 text-dark"><i class="fas fa-user-check mr-2"></i>Gestión de Personas Responsables</h1>
    </div>
</div>

<!-- Main content -->
<div class="content py-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="d-flex flex-column flex-md-row gap-2">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCrearPersonaResponsable">
                    <i class="fas fa-plus mr-1"></i> Nueva Persona Responsable
                </button>
                <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Volver al Inicio</a>
            </div>
        </div>

        <!-- Tabla -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h4 class="card-title mb-2 mb-md-0"><i class="fas fa-list mr-2"></i> Personas Registradas</h4>
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" id="buscador-personas-responsables" class="form-control" placeholder="Buscar por nombre...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="cargarPersonasResponsables(1)"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0"><div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-personas-responsables-body"></tbody>
                </table>
            </div></div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="info-paginacion-personas-responsables"></div>
                    <nav><ul class="pagination mb-0" id="paginacion-controles-personas-responsables"></ul></nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<!-- Modal Crear Persona Responsable -->
<div class="modal fade" id="modalCrearPersonaResponsable" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-plus mr-2"></i> Crear Persona Responsable</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <form id="formCrearPersonaResponsable">
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre_responsable" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Persona Responsable -->
<div class="modal fade" id="modalVerPersonaResponsable" tabindex="-1" role="dialog">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-eye mr-2"></i> Detalles</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body p-4" id="detalles-persona-responsable-body"></div>
        <div class="modal-footer bg-light"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cerrar</button></div>
    </div></div>
</div>

<!-- Modal Editar Persona Responsable -->
<div class="modal fade" id="modalEditarPersonaResponsable" tabindex="-1" role="dialog">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-edit mr-2"></i> Editar Persona Responsable</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <form id="formEditarPersonaResponsable">
            <input type="hidden" id="editar_id_responsable" name="id_responsable">
            <div class="modal-body p-4">
                 <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" id="editar_nombre_responsable" name="nombre_responsable" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancelar</button>
                <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Actualizar</button>
            </div>
        </form>
    </div></div>
</div>


<?php include __DIR__ . '/views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    cargarPersonasResponsables(1);
    $('#buscador-personas-responsables').on('keypress', function(e) { if (e.which === 13) cargarPersonasResponsables(1); });
    $('#formCrearPersonaResponsable').on('submit', function(e) { e.preventDefault(); manejarSubmit(this, 'ajax/crear_persona_responsable.php', 'creada'); });
    $('#formEditarPersonaResponsable').on('submit', function(e) { e.preventDefault(); manejarSubmit(this, 'ajax/editar_persona_responsable.php', 'actualizada'); });
});

function manejarSubmit(form, url, actionText) {
    $.ajax({
        url: url,
        type: 'POST',
        data: $(form).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $(form).closest('.modal').modal('hide');
                Swal.fire('¡Éxito!', `Persona responsable ${actionText} correctamente`, 'success');
                // Recargar en la página actual al editar, o en la primera al crear
                const paginaActual = actionText === 'actualizada' ? ($('#paginacion-controles-personas-responsables .active .page-link').text() || 1) : 1;
                cargarPersonasResponsables(paginaActual);
            } else {
                Swal.fire('Error', response.message || `No se pudo procesar la solicitud.`, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error de comunicación.', 'error');
        }
    });
}

function cargarPersonasResponsables(pagina) {
    $.ajax({
        url: 'ajax/listar_personas_responsables.php',
        method: 'POST',
        data: { pagina: pagina, busqueda: $('#buscador-personas-responsables').val() },
        dataType: 'json',
        beforeSend: function() { $('#tabla-personas-responsables-body').html('<tr><td colspan="3" class="text-center"><div class="spinner-border text-primary"></div></td></tr>'); },
        success: function(response) {
            if (response.success) {
                const { personas, paginacion } = response;
                $('#tabla-personas-responsables-body').empty();
                if (personas.length > 0) {
                    personas.forEach(p => $('#tabla-personas-responsables-body').append(crearFilaPersona(p)));
                } else {
                    $('#tabla-personas-responsables-body').append('<tr><td colspan="3" class="text-center empty-state"><i class="fas fa-search fa-2x text-muted mb-3"></i><p>No se encontraron personas responsables.</p></td></tr>');
                }
                actualizarPaginacion(paginacion);
            } else {
                Swal.fire('Error', response.message || 'No se pudieron cargar los datos.', 'error');
            }
        },
        error: function() { Swal.fire('Error', 'Error de comunicación con el servidor.', 'error'); }
    });
}

function crearFilaPersona(p) {
    return `
        <tr>
            <td>#${p.id_responsable}</td>
            <td>${p.nombre_responsable}</td>
            <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                    <button class="btn btn-sm btn-outline-info" onclick="verPersonaResponsable(${p.id_responsable})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-outline-warning" onclick="editarPersonaResponsable(${p.id_responsable})"><i class="fas fa-edit"></i></button>
                </div>
            </td>
        </tr>`;
}

function actualizarPaginacion(paginacion) {
    const { pagina_actual, total_paginas, total_registros } = paginacion;
    const controles = $('#paginacion-controles-personas-responsables');
    const info = $('#info-paginacion-personas-responsables');

    controles.empty();
    info.empty();

    if (total_registros > 0) {
        info.text(`Página ${pagina_actual} de ${total_paginas} (${total_registros} registros)`);

        let html = '';
        const rango = 2;

        html += `<li class="page-item ${pagina_actual <= 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarPersonasResponsables(${pagina_actual - 1});">Anterior</a></li>`;

        for (let i = Math.max(1, pagina_actual - rango); i <= Math.min(total_paginas, pagina_actual + rango); i++) {
            html += `<li class="page-item ${i === pagina_actual ? 'active' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarPersonasResponsables(${i});">${i}</a></li>`;
        }

        html += `<li class="page-item ${pagina_actual >= total_paginas ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarPersonasResponsables(${pagina_actual + 1});">Siguiente</a></li>`;

        controles.html(html);
    }
}

function verPersonaResponsable(id) {
    $.ajax({
        url: 'ajax/obtener_persona_responsable.php',
        type: 'POST', data: { id_responsable: id }, dataType: 'json',
        success: function(response) {
            if (response.success) {
                const p = response.data;
                $('#detalles-persona-responsable-body').html(`<p><strong>ID:</strong> ${p.id_responsable}</p><p><strong>Nombre:</strong> ${p.nombre_responsable}</p>`);
                $('#modalVerPersonaResponsable').modal('show');
            } else {
                Swal.fire('Error', 'No se pudieron cargar los detalles.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error de comunicación.', 'error');
        }
    });
}

function editarPersonaResponsable(id) {
    $.ajax({
        url: 'ajax/obtener_persona_responsable.php',
        type: 'POST', data: { id_responsable: id }, dataType: 'json',
        success: function(response) {
            if (response.success) {
                const p = response.data;
                $('#editar_id_responsable').val(p.id_responsable);
                $('#editar_nombre_responsable').val(p.nombre_responsable);
                $('#modalEditarPersonaResponsable').modal('show');
            } else {
                Swal.fire('Error', 'No se pudieron cargar los datos para editar.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error de comunicación.', 'error');
        }
    });
}
</script>
