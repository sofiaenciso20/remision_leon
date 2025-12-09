<?php
// listar_remisiones.php
include 'views/layout/header.php';
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
        <h1 class="m-0 text-dark"><i class="fas fa-file-invoice mr-2"></i>Listado de Remisiones</h1>
    </div>
</div>

<!-- Main content -->
<div class="content py-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="d-flex flex-column flex-md-row gap-2">
                <a href="index.php" class="btn btn-success"><i class="fas fa-plus mr-1"></i> Nueva Remisión</a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3"><h4 class="card-title mb-0"><i class="fas fa-filter text-secondary mr-2"></i> Filtros</h4></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Buscar por N°, Cliente o NIT</label><input type="text" class="form-control" id="busqueda-remision" placeholder="Término de búsqueda..."></div>
                    <div class="col-md-6 mb-3"><label>Filtrar por Fecha</label><input type="date" class="form-control" id="fecha-remision"></div>
                </div>
            </div>
        </div>

        <!-- Tabla de remisiones -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3"><h4 class="card-title mb-0"><i class="fas fa-list text-primary mr-2"></i> Remisiones</h4></div>
            <div class="card-body p-0"><div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Número</th><th>Fecha</th><th>Cliente</th><th>NIT</th><th>Contacto</th><th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-remisiones-body"></tbody>
                </table>
            </div></div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="info-paginacion-remisiones"></div>
                    <nav><ul class="pagination mb-0" id="paginacion-controles-remisiones"></ul></nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Remisión -->
<div class="modal fade" id="modalVerRemision" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header bg-light"><h4 class="modal-title mb-0"><i class="fas fa-eye mr-2"></i> Detalles de Remisión</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body p-4" id="detalles-remision-body"></div>
        <div class="modal-footer bg-light"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cerrar</button></div>
    </div></div>
</div>

<?php include 'views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    cargarRemisiones(1);
    $('#busqueda-remision').on('keypress', function(e) { if (e.which === 13) cargarRemisiones(1); });
    $('#fecha-remision').on('change', function() { cargarRemisiones(1); });
});

function cargarRemisiones(pagina) {
    const busqueda = $('#busqueda-remision').val();
    const fecha = $('#fecha-remision').val();

    $.ajax({
        url: 'ajax/listar_remisiones.php',
        method: 'POST',
        data: { pagina: pagina, busqueda: busqueda, fecha: fecha },
        dataType: 'json',
        beforeSend: function() { $('#tabla-remisiones-body').html('<tr><td colspan="6" class="text-center"><div class="spinner-border text-primary"></div></td></tr>'); },
        success: function(response) {
            if (response.success) {
                const { remisiones, paginacion } = response;
                $('#tabla-remisiones-body').empty();
                if (remisiones.length > 0) {
                    remisiones.forEach(r => $('#tabla-remisiones-body').append(crearFilaRemision(r)));
                } else {
                    $('#tabla-remisiones-body').append('<tr><td colspan="6" class="text-center empty-state"><i class="fas fa-search fa-2x text-muted mb-3"></i><p>No se encontraron remisiones.</p></td></tr>');
                }
                actualizarPaginacionRemisiones(paginacion);
            } else { Swal.fire('Error', response.message, 'error'); }
        },
        error: function() { Swal.fire('Error', 'Error de comunicación.', 'error'); }
    });
}

function crearFilaRemision(r) {
    const fecha = new Date(r.fecha_emision).toLocaleDateString('es-CO', { year: 'numeric', month: '2-digit', day: '2-digit' });
    return `
        <tr>
            <td><strong>#${r.numero_remision}</strong></td>
            <td>${fecha}</td>
            <td>${r.nombre_cliente || 'N/A'}</td>
            <td>${r.nit || 'N/A'}</td>
            <td>${r.nombre_persona || '-'}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-outline-info" onclick="verRemision(${r.id_remision})"><i class="fas fa-eye"></i></button>
                <a href="generar_pdf.php?id=${r.id_remision}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf"></i></a>
            </td>
        </tr>`;
}

function actualizarPaginacionRemisiones(paginacion) {
    const { pagina_actual, total_paginas, total_remisiones } = paginacion;
    $('#paginacion-controles-remisiones, #info-paginacion-remisiones').empty();
    if (total_remisiones > 0) {
        $('#info-paginacion-remisiones').text(`Página ${pagina_actual} de ${total_paginas} (${total_remisiones} remisiones)`);
        let html = '';
        const rango = 2;
        html += `<li class="page-item ${pagina_actual <= 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarRemisiones(${pagina_actual - 1});">Anterior</a></li>`;
        for (let i = Math.max(1, pagina_actual - rango); i <= Math.min(total_paginas, pagina_actual + rango); i++) {
            html += `<li class="page-item ${i === pagina_actual ? 'active' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarRemisiones(${i});">${i}</a></li>`;
        }
        html += `<li class="page-item ${pagina_actual >= total_paginas ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarRemisiones(${pagina_actual + 1});">Siguiente</a></li>`;
        $('#paginacion-controles-remisiones').html(html);
    }
}

function verRemision(id) {
    $.ajax({
        url: 'ajax/ver_remision.php',
        type: 'POST', data: { id_remision: id },
        beforeSend: function() { $('#detalles-remision-body').html('<div class="text-center"><div class="spinner-border text-primary"></div></div>'); },
        success: function(response) {
            $('#detalles-remision-body').html(response);
            $('#modalVerRemision').modal('show');
        },
        error: function() { Swal.fire('Error', 'No se pudieron cargar los detalles.', 'error'); }
    });
}
</script>
