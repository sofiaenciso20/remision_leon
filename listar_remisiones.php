<?php include 'views/layout/header.php'; ?>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <h1 class="m-0 text-dark"><i class="fas fa-file-invoice mr-2"></i>Listado de Remisiones</h1>
    </div>
</div>

<!-- Main content -->
<div class="content py-4">
    <div class="container">
        <!-- Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                 <h4 class="card-title mb-0"><i class="fas fa-filter text-secondary mr-2"></i> Filtros de Búsqueda</h4>
            </div>
            <div class="card-body">
                <form id="formFiltros">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="buscar" class="form-label font-weight-bold">Buscar</label>
                            <input type="text" class="form-control" id="buscar" name="buscar" placeholder="Nº Remisión, Cliente, NIT...">
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                             <label for="fecha_creacion" class="form-label font-weight-bold">Fecha de Creación</label>
                             <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion">
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="id_cliente" class="form-label font-weight-bold">Empresa/Cliente</label>
                            <select class="form-control select2-cliente" id="id_cliente" name="id_cliente" style="width: 100%;"></select>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="id_persona" class="form-label font-weight-bold">Persona Encargada</label>
                            <select class="form-control select2-persona" id="id_persona" name="id_persona" style="width: 100%;"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Buscar</button>
                            <button type="button" id="limpiar-filtros" class="btn btn-outline-secondary"><i class="fas fa-eraser mr-1"></i> Limpiar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alertas y Resumen -->
        <div id="alertas-remisiones"></div>
        <div id="resumen-resultados" class="alert alert-light" style="display:none;"></div>


        <!-- Tabla de remisiones -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tabla-remisiones">
                        <thead class="table-light">
                            <tr>
                                <th>Número</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>NIT</th>
                                <th>Contacto</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenido dinámico -->
                        </tbody>
                    </table>
                </div>
                <div id="cargando" class="text-center p-4" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                <div id="sin-resultados" class="text-center p-4" style="display:none;">
                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                    <p class="mb-0">No se encontraron remisiones.</p>
                </div>
            </div>
             <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                 <div id="info-paginacion" class="text-muted"></div>
                 <nav id="controles-paginacion"></nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalVerRemision" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Remisión</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="contenidoRemision"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    let paginaActual = 1;

    function inicializarSelect2(selector, url, placeholder) {
        $(selector).select2({
            placeholder: placeholder,
            allowClear: true,
            width: '100%',
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: params => ({ termino: params.term }),
                processResults: data => ({ results: data.map(item => ({ id: item.id, text: item.text })) })
            }
        });
    }

    inicializarSelect2('.select2-cliente', 'ajax/buscar_clientes.php', 'Todos los clientes');
    inicializarSelect2('.select2-persona', 'ajax/buscar_personas_contacto.php', 'Todas las personas');

    function cargarRemisiones(pagina = 1) {
        paginaActual = pagina;
        const formData = $('#formFiltros').serializeArray();
        let params = { pagina: pagina };
        formData.forEach(item => {
            params[item.name] = item.value;
        });

        $('#cargando').show();
        $('#tabla-remisiones tbody').empty();
        $('#sin-resultados, #resumen-resultados').hide();

        $.ajax({
            url: 'ajax/listar_remisiones.php',
            type: 'GET',
            dataType: 'json',
            data: params,
            success: function(data) {
                $('#cargando').hide();
                if (data.success && data.remisiones.length > 0) {
                    renderizarTabla(data.remisiones);
                    renderizarPaginacion(data.paginacion);
                    $('#resumen-resultados').text(`Mostrando ${data.remisiones.length} de ${data.paginacion.total} remisiones.`).show();
                } else {
                    $('#sin-resultados').show();
                    $('#controles-paginacion, #info-paginacion').empty();
                }
            },
            error: function() {
                $('#cargando').hide();
                mostrarAlerta('Error al cargar las remisiones.', 'danger');
            }
        });
    }

    function renderizarTabla(remisiones) {
        const tbody = $('#tabla-remisiones tbody');
        tbody.empty();
        remisiones.forEach(rem => {
            const fecha = new Date(rem.fecha_emision).toLocaleDateString('es-CO');
            const fila = `
                <tr>
                    <td><strong>#${escapeHTML(rem.numero_remision)}</strong></td>
                    <td>${fecha}</td>
                    <td>${escapeHTML(rem.nombre_cliente)}</td>
                    <td>${escapeHTML(rem.nit)}</td>
                    <td>${escapeHTML(rem.nombre_persona) || '-'}</td>
                    <td class="text-center">
                        <button class="btn btn-info btn-sm btn-ver" data-id="${rem.id_remision}" title="Ver Detalles"><i class="fas fa-eye"></i></button>
                        <a href="editar_remision.php?id=${rem.id_remision}" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-edit"></i></a>
                        <a href="generar_pdf.php?id=${rem.id_remision}" target="_blank" class="btn btn-secondary btn-sm" title="Ver PDF"><i class="fas fa-file-pdf"></i></a>
                    </td>
                </tr>
            `;
            tbody.append(fila);
        });
    }

    function renderizarPaginacion(p) {
        const paginacion = $('#controles-paginacion');
        paginacion.empty();
        if (p.totalPaginas <= 1) {
            $('#info-paginacion').empty();
            return;
        }

        let html = '<ul class="pagination mb-0">';
        html += `<li class="page-item ${p.pagina === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-pagina="${p.pagina - 1}">Anterior</a></li>`;
        for (let i = 1; i <= p.totalPaginas; i++) {
            html += `<li class="page-item ${p.pagina === i ? 'active' : ''}"><a class="page-link" href="#" data-pagina="${i}">${i}</a></li>`;
        }
        html += `<li class="page-item ${p.pagina === p.totalPaginas ? 'disabled' : ''}"><a class="page-link" href="#" data-pagina="${p.pagina + 1}">Siguiente</a></li>`;
        html += '</ul>';
        paginacion.html(html);

        const inicio = (p.pagina - 1) * p.porPagina + 1;
        const fin = Math.min(inicio + p.porPagina - 1, p.total);
        $('#info-paginacion').text(`Página ${p.pagina} de ${p.totalPaginas}`);
    }

    // Eventos
    $('#formFiltros').submit(function(e) {
        e.preventDefault();
        cargarRemisiones(1);
    });

    $('#limpiar-filtros').click(function() {
        $('#formFiltros')[0].reset();
        $('.select2-cliente, .select2-persona').val(null).trigger('change');
        cargarRemisiones(1);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const pagina = $(this).data('pagina');
        if (pagina) cargarRemisiones(pagina);
    });

    $('#tabla-remisiones').on('click', '.btn-ver', function() {
        const id = $(this).data('id');
        $('#contenidoRemision').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
        $('#modalVerRemision').modal('show');
        $.ajax({
            url: 'ajax/ver_remision.php',
            type: 'POST',
            data: { id_remision: id },
            success: function(response) {
                 $('#contenidoRemision').html(response);
            },
            error: function() {
                $('#contenidoRemision').html('<div class="alert alert-danger">Error al cargar detalles.</div>');
            }
        });
    });

    function mostrarAlerta(mensaje, tipo) {
        $('#alertas-remisiones').html(`<div class="alert alert-${tipo} alert-dismissible fade show">${mensaje}<button type="button" class="close" data-dismiss="alert">&times;</button></div>`);
    }

    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return str.toString().replace(/[&<>"']/g, m => ({'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#39;'})[m]);
    }

    // Carga inicial
    cargarRemisiones();
});
</script>

<?php include 'views/layout/footer.php'; ?>