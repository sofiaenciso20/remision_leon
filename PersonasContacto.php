<?php
// filepath: c:\xampp\htdocs\remisiones\PersonasContacto.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

$stmtClientes = $cliente->obtenerTodos();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/views/layout/header.php';
?>

<style>
/* Estilos consistentes */
.content-header { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6; padding: 1.5rem 0; }
.card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border-radius: 0.5rem; }
.card-header { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6; padding: 1rem 1.25rem; border-radius: 0.5rem 0.5rem 0 0 !important; }
.table thead th { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 2px solid #dee2e6; }
.modal-header { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6; }
.fade-in { animation: fadeIn 0.5s ease-in; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.empty-state { text-align: center; padding: 3rem 1rem; }
.btn-group-sm > .btn i { margin-right: 0; }
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCrearPersonaContacto">
                <i class="fas fa-plus mr-1"></i> Nueva Persona
            </button>
            <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Volver</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filtros</h4>
                    <input type="text" id="busqueda" class="form-control w-50" placeholder="Buscar por nombre o cliente...">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaPersonasContacto">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Cliente</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenido se cargará por AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="cargando" class="text-center p-4" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
                <div id="sin-resultados" class="text-center p-4 empty-state" style="display:none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron resultados</h5>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div id="info-paginacion"></div>
                <nav id="nav-paginacion"></nav>
            </div>
        </div>
    </div>
</div>

<!-- Modals (Crear, Ver, Editar) -->
<?php include 'views/modals/personas_contacto_modals.php'; ?>

<?php include __DIR__ . '/views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    let currentPage = 1;
    let timeoutId;

    function cargarPersonas(pagina = 1, termino = '') {
        $('#cargando').show();
        $('#tablaPersonasContacto tbody').hide();
        $('#sin-resultados').hide();

        $.ajax({
            url: 'ajax/listar_personas_contacto.php',
            type: 'GET',
            data: {
                pagina: pagina,
                termino: termino
            },
            dataType: 'json',
            success: function(response) {
                $('#cargando').hide();
                if (response.personas.length > 0) {
                    let html = '';
                    response.personas.forEach(p => {
                        html += `
                            <tr class="fade-in">
                                <td>#${p.id_persona}</td>
                                <td>${p.nombre_persona || ''}</td>
                                <td>${p.cargo || '-'}</td>
                                <td>${p.telefono || '-'}</td>
                                <td>${p.correo || '-'}</td>
                                <td>${p.nombre_cliente || '-'}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" onclick="verPersonaContacto(${p.id_persona})" title="Ver"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-outline-warning" onclick="editarPersonaContacto(${p.id_persona})" title="Editar"><i class="fas fa-edit"></i></button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    $('#tablaPersonasContacto tbody').html(html).show();
                    actualizarPaginacion(response.paginacion);
                } else {
                    $('#sin-resultados').show();
                    $('#tablaPersonasContacto tbody').empty();
                    actualizarPaginacion({ total_paginas: 0 });
                }
            },
            error: function() {
                $('#cargando').hide();
                Swal.fire('Error', 'No se pudo cargar la lista de personas.', 'error');
            }
        });
    }

    function actualizarPaginacion(paginacion) {
        const { pagina_actual, total_paginas, total_registros } = paginacion;
        currentPage = pagina_actual;

        $('#info-paginacion').text(`Mostrando ${((pagina_actual - 1) * 10) + 1} a ${Math.min(pagina_actual * 10, total_registros)} de ${total_registros} registros`);

        let navHtml = '<ul class="pagination mb-0">';
        if (total_paginas > 1) {
            navHtml += `<li class="page-item ${pagina_actual === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${pagina_actual - 1}">Anterior</a></li>`;
            for (let i = 1; i <= total_paginas; i++) {
                navHtml += `<li class="page-item ${i === pagina_actual ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            navHtml += `<li class="page-item ${pagina_actual === total_paginas ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${pagina_actual + 1}">Siguiente</a></li>`;
        }
        navHtml += '</ul>';
        $('#nav-paginacion').html(navHtml);
    }

    $('#busqueda').on('keyup', function() {
        clearTimeout(timeoutId);
        const termino = $(this).val();
        timeoutId = setTimeout(() => cargarPersonas(1, termino), 500);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            cargarPersonas(page, $('#busqueda').val());
        }
    });

    // Carga inicial
    cargarPersonas();
});

function verPersonaContacto(id) {
    $.ajax({
        url: 'ajax/obtener_persona_contacto.php',
        type: 'POST', // O GET, según tu implementación
        data: { id_persona: id }, // O 'id'
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const p = response.data;
                $('#ver_id_persona').text(p.id_persona);
                $('#ver_nombre_persona').text(p.nombre_persona);
                $('#ver_cargo').text(p.cargo || '-');
                $('#ver_telefono').text(p.telefono || '-');
                $('#ver_correo').text(p.correo || '-');
                $('#ver_cliente').text(p.nombre_cliente || '-');
                $('#modalVerPersonaContacto').modal('show');
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

function editarPersonaContacto(id) {
    $.ajax({
        url: 'ajax/obtener_persona_contacto.php',
        type: 'POST',
        data: { id_persona: id },
        dataType: 'json',
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
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

// Lógica para enviar formularios de Crear y Editar (simplificada, requiere implementación completa)
$('#formCrearPersonaContacto').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'ajax/crear_persona_contacto.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.success){
                $('#modalCrearPersonaContacto').modal('hide');
                Swal.fire('¡Éxito!', 'Persona creada correctamente.', 'success');
                cargarPersonas(currentPage, $('#busqueda').val());
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
});

$('#formEditarPersonaContacto').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'ajax/editar_persona_contacto.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.success){
                $('#modalEditarPersonaContacto').modal('hide');
                Swal.fire('¡Éxito!', 'Persona actualizada correctamente.', 'success');
                cargarPersonas(currentPage, $('#busqueda').val());
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
});
</script>
