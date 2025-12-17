<?php
// inventario.php
require_once __DIR__ . '/config/database.php';

// Ya no cargamos todos los productos aquí
include __DIR__ . '/views/layout/header.php';
?>

<style>
/* Estilos consistentes */
.content-header { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6; }
.card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
.table-hover tbody tr:hover { background-color: rgba(0, 123, 255, 0.04); }
.stock-bajo { background-color: #ffe6e6; border-left: 4px solid #dc3545; }
.stock-medio { background-color: #fff9e6; border-left: 4px solid #ffc107; }
.stock-ok { background-color: #e6ffe6; border-left: 4px solid #28a745; }
.badge-stock { font-size: 0.8em; font-weight: 500; }
.empty-state { text-align: center; padding: 3rem 1rem; }
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <h1 class="m-0 text-dark"><i class="fas fa-file-invoice mr-2"></i>Inventario de Productos</h1>
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

        <!-- Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0"><i class="fas fa-filter text-secondary mr-2"></i> Filtros</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3"><label>Buscar por Nombre</label><input type="text" class="form-control" id="buscarProducto" placeholder="Nombre..."></div>
                    <div class="col-md-4 mb-3"><label>Tipo de Inventario</label><select class="form-control" id="filtroInventario"><option value="todos">Todos</option><option value="con-inventario">Con inventario</option><option value="sin-inventario">Sin inventario</option></select></div>
                    <div class="col-md-4 mb-3"><label>Estado de Stock</label><select class="form-control" id="filtroStock"><option value="todos">Todos</option><option value="bajo">Bajo</option><option value="medio">Medio</option><option value="ok">Ok</option></select></div>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3"><h4 class="card-title mb-0"><i class="fas fa-list text-primary mr-2"></i> Productos</h4></div>
            <div class="card-body p-0"><div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th><th>Producto</th><th>Maneja Inv.</th><th>Stock Actual</th><th>Stock Mínimo</th><th>Estado</th><th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-inventario-body"></tbody>
                </table>
            </div></div>
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="info-paginacion-inventario"></div>
                    <nav><ul class="pagination mb-0" id="paginacion-controles-inventario"></ul></nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 1. Modal Nuevo Producto -->
<div class="modal fade" id="nuevoProductoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Formulario para un nuevo producto -->
                <form id="formNuevoProducto">
                    <div class="form-group">
                        <label for="nombre_producto">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="maneja_inventario" name="maneja_inventario">
                        <label class="form-check-label" for="maneja_inventario">Maneja Inventario</label>
                    </div>
                    <div id="campos_inventario" style="display:none;">
                        <div class="form-group">
                            <label for="stock_inicial">Stock Inicial</label>
                            <input type="number" class="form-control" id="stock_inicial" name="stock_inicial" value="0">
                        </div>
                        <div class="form-group">
                            <label for="stock_minimo">Stock Mínimo</label>
                            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" value="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarProducto">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Modal de Movimiento (Unificado) -->
<div class="modal fade" id="movimientoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movimientoModalLabel">Registrar Movimiento para <span id="nombreProductoMovimiento" class="font-weight-bold"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formMovimiento">
                    <input type="hidden" id="idProductoMovimiento" name="id_producto">
                    <p>Stock Actual: <strong id="stockActualLabel"></strong> unidades.</p>
                    <div class="form-group">
                        <label for="tipo_movimiento">Tipo de Movimiento</label>
                        <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarMovimiento">Guardar Movimiento</button>
            </div>
        </div>
    </div>
</div>


<!-- 3. Modal de Historial -->
<div class="modal fade" id="historialModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historialModalLabel">Historial de Movimientos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="historial-body">
                <!-- Contenido cargado por AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    // Carga inicial
    cargarInventario(1);

    // Filtros
    $('#buscarProducto').on('keypress', function(e) { if (e.which === 13) cargarInventario(1); });
    $('#filtroInventario, #filtroStock').on('change', () => cargarInventario(1));

    // Mostrar/ocultar campos de inventario al crear producto
    $('#maneja_inventario').on('change', function() {
        $('#campos_inventario').toggle(this.checked);
    });

    // Guardar nuevo producto
    $('#guardarProducto').on('click', function() {
        const form = $('#formNuevoProducto');
        const data = {
            nombre_producto: $('#nombre_producto').val(),
            maneja_inventario: $('#maneja_inventario').is(':checked') ? 1 : 0,
            stock_actual: $('#maneja_inventario').is(':checked') ? $('#stock_inicial').val() : 0,
            stock_minimo: $('#maneja_inventario').is(':checked') ? $('#stock_minimo').val() : 0
        };

        $.ajax({
            url: 'ajax/crear_producto.php',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log(response); // Debug
                if (response.success) {
                    $('#nuevoProductoModal').modal('hide');
                    form[0].reset();
                    Swal.fire({
                        title: 'Éxito',
                        text: response.message,
                        icon: 'success'
                    });
                    cargarInventario(1);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText); // Debug
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo crear el producto.',
                    icon: 'error'
                });
            }
        });
    });

    // Abrir modal de movimiento
    $('#tabla-inventario-body').on('click', '.btn-movimiento', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const stock = $(this).data('stock');

        $('#idProductoMovimiento').val(id);
        $('#nombreProductoMovimiento').text(nombre);
        $('#stockActualLabel').text(stock);
        $('#formMovimiento')[0].reset(); // Limpiar el formulario
        $('#movimientoModal').modal('show');
    });

    // Guardar movimiento (entrada/salida)
    $('#guardarMovimiento').on('click', function() {
        const tipo = $('#tipo_movimiento').val();
        const url = (tipo === 'entrada') ? 'ajax/registrar_entrada.php' : 'ajax/registrar_salida.php';
        const data = $('#formMovimiento').serialize();

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#movimientoModal').modal('hide');
                    Swal.fire({
                        title: 'Éxito',
                        text: response.message,
                        icon: 'success'
                    });
                    cargarInventario(1); // Recargar para ver el stock actualizado
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al registrar el movimiento.';
                Swal.fire({
                    title: 'Error',
                    text: errorMsg,
                    icon: 'error'
                });
            }
        });
    });

    // Abrir modal de historial
    $('#tabla-inventario-body').on('click', '.btn-historial', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');

        $('#historialModalLabel').text('Historial de: ' + nombre);
        $('#historial-body').html('<div class="text-center"><div class="spinner-border"></div></div>');

        $.ajax({
            url: 'ajax/obtener_movimientos.php',
            method: 'GET',
            data: { id_producto: id },
            success: function(response) {
                $('#historial-body').html(response);
            },
            error: function() {
                $('#historial-body').html('<div class="alert alert-danger">Error al cargar el historial.</div>');
            }
        });

        $('#historialModal').modal('show');
    });
});

function cargarInventario(pagina) {
    const busqueda = $('#buscarProducto').val();
    const filtroInventario = $('#filtroInventario').val();
    const filtroStock = $('#filtroStock').val();

    $.ajax({
        url: 'ajax/listar_productos.php',
        method: 'POST',
        data: {
            pagina: pagina,
            busqueda: busqueda,
            filtroInventario: filtroInventario,
            filtroStock: filtroStock
        },
        dataType: 'json',
        beforeSend: function() {
            $('#tabla-inventario-body').html('<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary"></div></td></tr>');
        },
        success: function(response) {
            if (response.success) {
                const productos = response.productos;
                const paginacion = response.paginacion;
                $('#tabla-inventario-body').empty();

                if (productos.length > 0) {
                    productos.forEach(function(p) {
                        let estadoBadge = '<span class="badge bg-secondary badge-stock">No aplica</span>';
                        let claseFila = '';
                        if (p.maneja_inventario == 1) {
                            if (p.stock_actual <= p.stock_minimo) {
                                claseFila = 'stock-bajo';
                                estadoBadge = '<span class="badge bg-danger badge-stock">Bajo</span>';
                            } else if (p.stock_actual <= (parseInt(p.stock_minimo) + 10)) {
                                claseFila = 'stock-medio';
                                estadoBadge = '<span class="badge bg-warning badge-stock">Medio</span>';
                            } else {
                                claseFila = 'stock-ok';
                                estadoBadge = '<span class="badge bg-success badge-stock">Ok</span>';
                            }
                        }

                        $('#tabla-inventario-body').append(`
                            <tr class="${claseFila}">
                                <td>#${p.id_producto}</td>
                                <td>${p.nombre_producto}</td>
                                <td><span class="badge bg-${p.maneja_inventario == 1 ? 'success' : 'secondary'}">${p.maneja_inventario == 1 ? 'Sí' : 'No'}</span></td>
                                <td><strong>${p.stock_actual}</strong> <small class="text-muted">un.</small></td>
                                <td>${p.maneja_inventario == 1 ? p.stock_minimo + ' <small class="text-muted">un.</small>' : '-'}</td>
                                <td>${estadoBadge}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        ${p.maneja_inventario == 1 ? `
                                        <button class="btn btn-outline-success btn-movimiento" data-id="${p.id_producto}" data-nombre="${p.nombre_producto}" data-stock="${p.stock_actual}" title="Registrar Movimiento">
                                            <i class="fas fa-exchange-alt"></i> Movimiento
                                        </button>
                                        ` : ''}
                                        <button class="btn btn-outline-info btn-historial" data-id="${p.id_producto}" data-nombre="${p.nombre_producto}" title="Ver Historial">
                                            <i class="fas fa-history"></i> Historial
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    $('#tabla-inventario-body').append('<tr><td colspan="7" class="text-center empty-state"><i class="fas fa-search fa-2x text-muted mb-3"></i><p>No se encontraron productos.</p></td></tr>');
                }
                actualizarPaginacionInventario(paginacion);
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error de comunicación.', 'error');
        }
    });
}

function actualizarPaginacionInventario(paginacion) {
    const { pagina_actual, total_paginas, total_productos } = paginacion;
    $('#paginacion-controles-inventario').empty();
    $('#info-paginacion-inventario').empty();

    if (total_productos > 0) {
        $('#info-paginacion-inventario').text(`Página ${pagina_actual} de ${total_paginas} (${total_productos} productos)`);

        let html = '';
        const rango = 2;
        html += `<li class="page-item ${pagina_actual <= 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarInventario(${pagina_actual - 1});">Anterior</a></li>`;
        for (let i = Math.max(1, pagina_actual - rango); i <= Math.min(total_paginas, pagina_actual + rango); i++) {
            html += `<li class="page-item ${i === pagina_actual ? 'active' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarInventario(${i});">${i}</a></li>`;
        }
        html += `<li class="page-item ${pagina_actual >= total_paginas ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); cargarInventario(${pagina_actual + 1});">Siguiente</a></li>`;
        $('#paginacion-controles-inventario').html(html);
    }
}
</script>
