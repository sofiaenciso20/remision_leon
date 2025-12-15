<?php
// inventario.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Producto.php';
require_once __DIR__ . '/models/MovimientoInventario.php';
?>

<?php include __DIR__ . '/views/layout/header.php'; ?>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-boxes mr-2"></i>Inventario de Productos
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
        <div id="alertas-inventario"></div>

        <!-- Filtros y tabla de productos -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">
                    <i class="fas fa-filter text-secondary mr-2"></i> Filtros de Búsqueda
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" id="buscarProducto" placeholder="Buscar por nombre...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <select class="form-control" id="filtroInventario">
                            <option value="todos">Todos los productos</option>
                            <option value="con-inventario">Con inventario</option>
                            <option value="sin-inventario">Sin inventario</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
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

        <!-- Tabla de productos -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tablaInventario">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Maneja Inventario</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenido dinámico por AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="cargando" class="text-center p-4" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
                 <div id="sin-resultados" class="text-center p-4" style="display:none;">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="mb-0">No se encontraron productos.</p>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                 <div id="info-paginacion" class="text-muted"></div>
                 <nav id="controles-paginacion"></nav>
            </div>
        </div>
    </div>
</div>

<!-- Modals (se mantienen igual) -->
<?php include __DIR__ . '/views/inventario/modals.php'; ?>

<script>
$(document).ready(function() {
    let paginaActual = 1;
    const porPagina = 10;

    function cargarProductos(pagina = 1) {
        paginaActual = pagina;
        const termino = $('#buscarProducto').val();
        const inventario = $('#filtroInventario').val();
        const stock = $('#filtroStock').val();

        $('#cargando').show();
        $('#tablaInventario tbody').empty();
        $('#sin-resultados').hide();

        $.ajax({
            url: 'ajax/listar_productos.php',
            type: 'GET',
            dataType: 'json',
            data: {
                pagina: pagina,
                termino: termino,
                inventario: inventario,
                stock: stock
            },
            success: function(data) {
                $('#cargando').hide();
                if (data.success && data.productos.length > 0) {
                    renderizarTabla(data.productos);
                    renderizarPaginacion(data.paginacion);
                } else {
                    $('#sin-resultados').show();
                    $('#controles-paginacion').empty();
                    $('#info-paginacion').empty();
                }
            },
            error: function() {
                $('#cargando').hide();
                mostrarAlerta('Error al cargar los productos.', 'danger');
            }
        });
    }

    function renderizarTabla(productos) {
        const tbody = $('#tablaInventario tbody');
        tbody.empty();
        productos.forEach(producto => {
            const stock = producto.stock_actual || 0;
            const stock_minimo = producto.stock_minimo || 0;
            const maneja_inventario = parseInt(producto.maneja_inventario);

            let clase_stock = '';
            let estado = '<span class="badge bg-secondary">No aplica</span>';

            if (maneja_inventario) {
                if (stock <= stock_minimo) {
                    clase_stock = 'table-danger';
                    estado = '<span class="badge bg-danger">Bajo</span>';
                } else if (stock <= (stock_minimo + 10)) {
                    clase_stock = 'table-warning';
                    estado = '<span class="badge bg-warning text-dark">Medio</span>';
                } else {
                    clase_stock = 'table-success';
                    estado = '<span class="badge bg-success">Ok</span>';
                }
            }

            const fila = `
                <tr class="${clase_stock}">
                    <td>#${producto.id_producto}</td>
                    <td>${escapeHTML(producto.nombre_producto)}</td>
                    <td>${maneja_inventario ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>'}</td>
                    <td>${stock}</td>
                    <td>${maneja_inventario ? stock_minimo : '-'}</td>
                    <td>${estado}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            ${maneja_inventario ? `
                            <button class="btn btn-outline-primary btn-entrada" data-id="${producto.id_producto}" data-nombre="${escapeHTML(producto.nombre_producto)}" title="Registrar Entrada"><i class="fas fa-plus-circle"></i></button>
                            <button class="btn btn-outline-warning btn-salida" data-id="${producto.id_producto}" data-nombre="${escapeHTML(producto.nombre_producto)}" data-stock="${stock}" title="Registrar Salida"><i class="fas fa-minus-circle"></i></button>
                            ` : ''}
                            <button class="btn btn-outline-info btn-historial" data-id="${producto.id_producto}" data-nombre="${escapeHTML(producto.nombre_producto)}" title="Ver Historial"><i class="fas fa-history"></i></button>
                        </div>
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
            $('#info-paginacion').text(`Mostrando ${p.total} de ${p.total} productos`);
            return;
        }

        let html = '<ul class="pagination mb-0">';

        // Botón Anterior
        html += `<li class="page-item ${p.pagina === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-pagina="${p.pagina - 1}">Anterior</a></li>`;

        // Números de página
        for (let i = 1; i <= p.totalPaginas; i++) {
            html += `<li class="page-item ${p.pagina === i ? 'active' : ''}"><a class="page-link" href="#" data-pagina="${i}">${i}</a></li>`;
        }

        // Botón Siguiente
        html += `<li class="page-item ${p.pagina === p.totalPaginas ? 'disabled' : ''}"><a class="page-link" href="#" data-pagina="${p.pagina + 1}">Siguiente</a></li>`;

        html += '</ul>';
        paginacion.html(html);

        const inicio = (p.pagina - 1) * p.porPagina + 1;
        const fin = Math.min(inicio + p.porPagina - 1, p.total);
        $('#info-paginacion').text(`Mostrando ${inicio}-${fin} de ${p.total} productos`);
    }

    // Eventos
    $('#buscarProducto, #filtroInventario, #filtroStock').on('input change', function() {
        clearTimeout(this.delay);
        this.delay = setTimeout(() => cargarProductos(1), 500);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const pagina = $(this).data('pagina');
        if (pagina) {
            cargarProductos(pagina);
        }
    });

    // Delegación de eventos para botones de acción
    $('#tablaInventario').on('click', '.btn-entrada', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        $('#entrada_id_producto').val(id);
        $('#entrada_nombre_producto').val(nombre);
        $('#entradaModal').modal('show');
    });

    $('#tablaInventario').on('click', '.btn-salida', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const stock = $(this).data('stock');
        $('#salida_id_producto').val(id);
        $('#salida_nombre_producto').val(nombre);
        $('#salida_stock_actual').val(stock);
        $('#cantidad_salida').attr('max', stock);
        $('#salidaModal').modal('show');
    });

    $('#tablaInventario').on('click', '.btn-historial', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        $('#historialModalLabel').text('Historial de - ' + nombre);
        $('#contenido-historial').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
        $.get('ajax/obtener_movimientos.php', { id_producto: id }, function(data) {
            $('#contenido-historial').html(data);
        });
        $('#historialModal').modal('show');
    });

    // Envíos de formularios (AJAX)
    $('#formNuevoProducto').on('submit', function(e) { e.preventDefault(); manejarSubmit(this, 'ajax/crear_producto.php', 'Producto creado'); });
    $('#formEntrada').on('submit', function(e) { e.preventDefault(); manejarSubmit(this, 'ajax/registrar_entrada.php', 'Entrada registrada'); });
    $('#formSalida').on('submit', function(e) { e.preventDefault(); manejarSubmit(this, 'ajax/registrar_salida.php', 'Salida registrada'); });

    function manejarSubmit(form, url, successMsg) {
        const $form = $(form);
        const $btn = $form.find('button[type="submit"]');
        const originalHtml = $btn.html();

        if (form.id === 'formSalida') {
            const cantidad = parseInt($('#cantidad_salida').val());
            const max = parseInt($('#cantidad_salida').attr('max'));
            if (cantidad > max) {
                mostrarAlerta('La cantidad de salida no puede exceder el stock actual.', 'warning');
                return;
            }
        }

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(data) {
                if(data.success) {
                    $form.closest('.modal').modal('hide');
                    mostrarAlerta(successMsg + ' con éxito.', 'success');
                    cargarProductos(paginaActual);
                } else {
                    mostrarAlerta(data.message || 'Ocurrió un error.', 'danger');
                }
            },
            error: function() {
                mostrarAlerta('Error de conexión con el servidor.', 'danger');
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    }

    function mostrarAlerta(mensaje, tipo) {
        const alerta = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        $('#alertas-inventario').html(alerta);
    }

    function escapeHTML(str) {
        return str.toString().replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    // Carga inicial
    cargarProductos();
});
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
