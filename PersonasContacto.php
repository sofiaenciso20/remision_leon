<?php
// filepath: c:\xampp\htdocs\remisiones\PersonasContacto.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Cliente.php';

// Solo necesitamos la lista de clientes para los modales
$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);
$stmtClientes = $cliente->obtenerTodos();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

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
        <h1 class="m-0 text-dark"><i class="fas fa-address-book mr-2"></i>Gestión de Personas de Contacto</h1>
    </div>
</div>

<!-- Main content -->
<div class="content py-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="d-flex flex-column flex-md-row gap-2">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCrearPersonaContacto">
                    <i class="fas fa-plus mr-1"></i> Nueva Persona
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
                        <input type="text" id="buscador-personas" class="form-control" placeholder="Buscar por nombre o cliente...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="cargarPersonas(1)"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0"><div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th><th>Nombre</th><th>Cargo</th><th>Teléfono</th><th>Correo</th><th>Cliente</th><th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-personas-body"></tbody>
                </table>
            </div></div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="info-paginacion-personas"></div>
                    <nav><ul class="pagination mb-0" id="paginacion-controles-personas"></ul></nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<!-- Modal Crear Persona -->
<div class="modal fade" id="modalCrearPersonaContacto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-plus mr-2"></i> Crear Persona</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <form id="formCrearPersonaContacto">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3"><div class="form-group"><label>Nombre *</label><input type="text" name="nombre_persona" class="form-control" required></div></div>
                        <div class="col-md-6 mb-3"><div class="form-group"><label>Cargo</label><input type="text" name="cargo" class="form-control"></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><div class="form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control"></div></div>
                        <div class="col-md-6 mb-3"><div class="form-group"><label>Correo</label><input type="email" name="correo" class="form-control"></div></div>
                    </div>
                     <div class="form-group">
                         <label>Cliente Asociado *</label>
                         <select name="id_cliente" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($clientes as $clienteOpt): ?>
                            <option value="<?php echo $clienteOpt['id_cliente']; ?>"><?php echo htmlspecialchars($clienteOpt['nombre_cliente']); ?></option>
                            <?php endforeach; ?>
                         </select>
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

<!-- Modal Ver Persona -->
<div class="modal fade" id="modalVerPersonaContacto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-eye mr-2"></i> Detalles</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body p-4" id="detalles-persona-body"></div>
        <div class="modal-footer bg-light"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cerrar</button></div>
    </div></div>
</div>

<!-- Modal Editar Persona -->
<div class="modal fade" id="modalEditarPersonaContacto" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-edit mr-2"></i> Editar Persona</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <form id="formEditarPersonaContacto">
            <input type="hidden" id="editar_id_persona" name="id_persona">
            <div class="modal-body p-4">
                 <div class="row">
                    <div class="col-md-6 mb-3"><div class="form-group"><label>Nombre *</label><input type="text" id="editar_nombre_persona" name="nombre_persona" class="form-control" required></div></div>
                    <div class="col-md-6 mb-3"><div class="form-group"><label>Cargo</label><input type="text" id="editar_cargo" name="cargo" class="form-control"></div></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><div class="form-group"><label>Teléfono</label><input type="text" id="editar_telefono" name="telefono" class="form-control"></div></div>
                    <div class="col-md-6 mb-3"><div class="form-group"><label>Correo</label><input type="email" id="editar_correo" name="correo" class="form-control"></div></div>
                </div>
                 <div class="form-group">
                     <label>Cliente Asociado *</label>
                     <select id="editar_id_cliente" name="id_cliente" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($clientes as $clienteOpt): ?>
                        <option value="<?php echo $clienteOpt['id_cliente']; ?>"><?php echo htmlspecialchars($clienteOpt['nombre_cliente']); ?></option>
                        <?php endforeach; ?>
                     </select>
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
    cargarPersonas(1);
    $('#buscador-personas').on('keypress', function(e) { if (e.which === 13) cargarPersonas(1); });
    $('#formCrearPersonaContacto').on('submit', function(e) { e.preventDefault(); manejarSubmit(this, 'ajax/crear_persona_contacto.php', 'creada'); });
    $('#formEditarPersonaContacto').on('submit', function(e) { e.preventDefault(); manejarSubmit(this, 'ajax/editar_persona_contacto.php', 'actualizada'); });
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
                Swal.fire('¡Éxito!', `Persona ${actionText} correctamente`, 'success');
                cargarPersonas(actionText === 'actualizada' ? ($('#paginacion-controles-personas .active .page-link').text() || 1) : 1);
            } else {
                Swal.fire('Error', response.message || `No se pudo procesar la solicitud.`, 'error');
            }
        }
    });
}

function cargarPersonas(pagina) {
    $.ajax({
        url: 'ajax/listar_personas_contacto.php',
        method: 'POST',
        data: { pagina: pagina, busqueda: $('#buscador-personas').val() },
        dataType: 'json',
        beforeSend: function() { $('#tabla-personas-body').html('<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary"></div></td></tr>'); },
        success: function(response) {
            if (response.success) {
                const { personas, paginacion } = response;
                $('#tabla-personas-body').empty();
                if (personas.length > 0) {
                    personas.forEach(p => $('#tabla-personas-body').append(crearFilaPersona(p)));
                } else {
                    $('#tabla-personas-body').append('<tr><td colspan="7" class="text-center empty-state"><i class="fas fa-search fa-2x text-muted mb-3"></i><p>No se encontraron personas.</p></td></tr>');
                }
                actualizarPaginacionPersonas(paginacion);
            } else { Swal.fire('Error', response.message, 'error'); }
        },
        error: function() { Swal.fire('Error', 'Error de comunicación.', 'error'); }
    });
}

function crearFilaPersona(p) {
    return `
        <tr>
            <td>#${p.id_persona}</td>
            <td>${p.nombre_persona}</td>
            <td>${p.cargo || '-'}</td>
            <td>${p.telefono || '-'}</td>
            <td>${p.correo || '-'}</td>
            <td>${p.nombre_cliente || 'N/A'}</td>
            <td class="text-center">
                <div class="d-flex justify-content-center gap-1">
                    <button class="btn btn-sm btn-outline-info" onclick="verPersona(${p.id_persona})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-outline-warning" onclick="editarPersona(${p.id_persona})"><i class="fas fa-edit"></i></button>
                </div>
            </td>
        </tr>`;
}

function actualizarPaginacionPersonas(paginacion) {
    const { pagina_actual, total_paginas, total_personas } = paginacion;
    $('#paginacion-controles-personas, #info-paginacion-personas').empty();
    if (total_personas > 0) {
        $('#info-paginacion-personas').text(`Página ${pagina_actual} de ${total_paginas} (${total_personas} personas)`);
        let html = '';
        const rango = 2;
        html += `<li class="page-item ${pagina_actual <= 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarPersonas(${pagina_actual - 1});">Anterior</a></li>`;
        for (let i = Math.max(1, pagina_actual - rango); i <= Math.min(total_paginas, pagina_actual + rango); i++) {
            html += `<li class="page-item ${i === pagina_actual ? 'active' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarPersonas(${i});">${i}</a></li>`;
        }
        html += `<li class="page-item ${pagina_actual >= total_paginas ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarPersonas(${pagina_actual + 1});">Siguiente</a></li>`;
        $('#paginacion-controles-personas').html(html);
    }
}

function verPersona(id) {
    $.ajax({
        url: 'ajax/obtener_persona_contacto.php',
        type: 'POST', data: { id_persona: id }, dataType: 'json',
        success: function(response) {
            if (response.success) {
                const p = response.data;
                $('#detalles-persona-body').html(`<p><strong>ID:</strong> ${p.id_persona}</p><p><strong>Nombre:</strong> ${p.nombre_persona}</p><p><strong>Cargo:</strong> ${p.cargo || '-'}</p><p><strong>Teléfono:</strong> ${p.telefono || '-'}</p><p><strong>Correo:</strong> ${p.correo || '-'}</p><p><strong>Cliente:</strong> ${p.nombre_cliente || 'N/A'}</p>`);
                $('#modalVerPersonaContacto').modal('show');
            } else { Swal.fire('Error', 'No se pudieron cargar los detalles.', 'error'); }
        }
    });
}

function editarPersona(id) {
    $.ajax({
        url: 'ajax/obtener_persona_contacto.php',
        type: 'POST', data: { id_persona: id }, dataType: 'json',
        success: function(response) {
            if (response.success) {
                const p = response.data;
                $('#editar_id_persona').val(p.id_persona);
                $('#editar_nombre_persona').val(p.nombre_persona);
                $('#editar_cargo').val(p.cargo);
                $('#editar_telefono').val(p.telefono);
                $('#editar_correo').val(p.correo);
                $('#editar_id_cliente').val(p.id_cliente);
                $('#modalEditarPersonaContacto').modal('show');
            } else { Swal.fire('Error', 'No se pudieron cargar los datos para editar.', 'error'); }
        }
    });
}
</script>
