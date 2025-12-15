<?php
require_once 'config/database.php';
include 'views/layout/header.php';
?>

<style>
/* Estilos consistentes con index.php */
.content-header { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 1px solid #dee2e6; padding: 1.5rem 0; }
.card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border-radius: 0.5rem; }
.select-group { display: flex; gap: 8px; align-items: flex-start; }
.select-group .form-control { flex: 1; min-width: 0; }
.select-group .btn { white-space: nowrap; flex-shrink: 0; }
.select2-container { width: 100% !important; }
.producto-search-container { display:flex; gap:8px; align-items:center; }
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <h1 class="m-0 text-dark"><i class="fas fa-edit mr-2"></i>Editar Remisión</h1>
    </div>
</div>

<!-- Main content -->
<div class="content py-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <a href="listar_remisiones.php" class="btn btn-outline-info"><i class="fas fa-arrow-left mr-1"></i> Volver al Listado</a>
        </div>

        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <h3 class="card-title mb-0"><i class="fas fa-file-invoice text-primary mr-2"></i> Datos de la Remisión</h3>
            </div>

            <form id="formEditarRemision">
                <input type="hidden" id="id_remision" name="id_remision">

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="form-group">
                                <label for="numero_remision" class="form-label">Número de Remisión</label>
                                <input type="text" class="form-control form-control-lg" id="numero_remision" name="numero_remision" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="form-group">
                                <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                                <input type="date" class="form-control form-control-lg" id="fecha_emision" name="fecha_emision" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="form-group">
                                <label for="tipo_remision" class="form-label">Tipo de Remisión *</label>
                                <select id="tipo_remision" name="tipo_remision" class="form-control form-control-lg" required>
                                    <option value="Venta">Venta</option>
                                    <option value="Alquiler">Alquiler</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="cliente" class="form-label">Cliente *</label>
                                <select class="form-control select2-cliente" id="cliente" name="id_cliente" required></select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="persona_contacto" class="form-label">Persona que Recibe</label>
                                <select class="form-control" id="persona_contacto" name="id_persona"></select>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="persona_responsable" class="form-label">Persona Responsable</label>
                                <select class="form-control" id="persona_responsable" name="id_responsable"></select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-list mr-2"></i> Items de la Remisión</h5>
                        <button type="button" class="btn btn-success" onclick="agregarItem()"><i class="fas fa-plus mr-1"></i> Agregar Item</button>
                    </div>

                    <div id="items-container" class="mb-4"></div>

                    <div class="total-general-container">
                         <div class="row align-items-center">
                            <div class="col-md-8 text-md-right text-center mb-2 mb-md-0">
                                <h5 class="font-weight-bold mb-0">TOTAL GENERAL:</h5>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="alert alert-success py-2 font-weight-bold mb-0" id="total-general">$0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white py-3 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>

<script>
let contadorItems = 0;

$(document).ready(function() {
    const remisionId = new URLSearchParams(window.location.search).get('id');
    if (!remisionId) {
        Swal.fire('Error', 'ID de remisión no válido.', 'error').then(() => window.location.href = 'listar_remisiones.php');
        return;
    }
    $('#id_remision').val(remisionId);

    $('#cliente').select2({
        placeholder: 'Buscar cliente...',
        ajax: {
            url: 'ajax/buscar_clientes.php',
            dataType: 'json',
            delay: 250,
            data: params => ({ termino: params.term }),
            processResults: data => ({
                results: data.map(item => ({ id: item.id_cliente, text: item.nombre_cliente }))
            })
        }
    }).on('change', function(e) {
        // Solo cargar personas si el cambio es por interacción del usuario
        if(e.originalEvent) {
            cargarPersonasContacto($(this).val(), null);
        }
    });

    cargarRemisionParaEditar(remisionId);

    $('#formEditarRemision').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const items = [];
        $('.item-row').each(function() {
            items.push({
                id_producto: $(this).find('.id-producto').val() || null,
                descripcion: $(this).find('.descripcion').val(),
                cantidad: $(this).find('.cantidad').val(),
                valor_unitario: $(this).find('.valor-unitario').val()
            });
        });

        formData.append('items', JSON.stringify(items));

        $.ajax({
            url: 'ajax/actualizar_remision.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success').then(() => {
                        window.location.href = 'listar_remisiones.php';
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: () => Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error')
        });
    });
});

function cargarRemisionParaEditar(id) {
    $.ajax({
        url: 'ajax/obtener_remision_completa.php',
        method: 'POST',
        data: { id_remision: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const remision = response.data;

                $('#numero_remision').val(remision.numero_remision);
                // Corregir el formato de la fecha, manejando fechas inválidas
                if (remision.fecha_emision && remision.fecha_emision.startsWith('0000-00-00')) {
                    $('#fecha_emision').val(''); // Dejar en blanco si la fecha no es válida
                } else if (remision.fecha_emision) {
                    $('#fecha_emision').val(remision.fecha_emision.split(' ')[0]);
                }
                $('#tipo_remision').val(remision.tipo_remision);
                $('#observaciones').val(remision.observaciones);

                // Cargar personas responsables (no depende de nada)
                cargarPersonasResponsable(remision.id_responsable);

                // Cargar cliente y LUEGO cargar contactos
                if (remision.id_cliente && remision.nombre_cliente) {
                    const clienteOption = new Option(remision.nombre_cliente, remision.id_cliente, true, true);
                    $('#cliente').append(clienteOption).trigger('change');

                    // Aseguramos que la carga de contactos se hace DESPUÉS de popular el cliente
                    // y con el ID de la persona correcta a seleccionar.
                    cargarPersonasContacto(remision.id_cliente, remision.id_persona);
                }


                $('#items-container').empty();
                if (remision.items && remision.items.length > 0) {
                    remision.items.forEach(item => agregarItem(item));
                } else {
                    agregarItem();
                }
            } else {
                Swal.fire('Error', response.message, 'error').then(() => window.location.href = 'listar_remisiones.php');
            }
        },
        error: () => Swal.fire('Error', 'No se pudo cargar la información de la remisión.', 'error')
    });
}

function cargarPersonasContacto(clienteId, seleccionarId) {
    if (!clienteId) return;

    // 1. Cargar la lista de personas para el cliente
    $.ajax({
        url: 'ajax/obtener_personas_contacto.php', // El endpoint que devuelve la LISTA
        method: 'POST',
        data: { id_cliente: clienteId },
        dataType: 'json',
        success: function(personas) {
            const select = $('#persona_contacto');
            select.empty().append('<option value="">Seleccione...</option>');

            let idEncontradoEnLista = false;
            if (Array.isArray(personas)) {
                personas.forEach(p => {
                    select.append(new Option(p.nombre_persona, p.id_persona));
                    if (p.id_persona == seleccionarId) {
                        idEncontradoEnLista = true;
                    }
                });
            }

            // 2. Si el ID a seleccionar está en la lista, simplemente lo seleccionamos.
            if (seleccionarId && idEncontradoEnLista) {
                select.val(seleccionarId);
            }
            // 3. Si no está en la lista (caso raro, ej: contacto eliminado), lo cargamos por separado y lo añadimos.
            else if (seleccionarId && !idEncontradoEnLista) {
                $.ajax({
                    url: 'ajax/obtener_persona_contacto.php',
                    method: 'GET',
                    data: { id: seleccionarId },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            const persona = response.data;
                            const option = new Option(persona.nombre_persona, persona.id_persona, true, true);
                            select.append(option).trigger('change');
                        }
                    }
                });
            }
        }
    });
}

function cargarPersonasResponsable(seleccionarId) {
    $.ajax({
        url: 'ajax/listar_todas_personas_responsables.php',
        method: 'GET',
        dataType: 'json',
        success: function(personas) {
            const select = $('#persona_responsable');
            select.empty().append('<option value="">Seleccione...</option>');
            if (Array.isArray(personas)) {
                personas.forEach(p => select.append(new Option(p.nombre_responsable, p.id_responsable)));
            }
            if (seleccionarId) select.val(seleccionarId);
        }
    });
}

function agregarItem(item = null) {
    contadorItems++;
    const itemId = `item-${contadorItems}`;
    const itemHtml = `
        <div class="item-row" id="${itemId}">
            <input type="hidden" class="id-producto" value="${item ? item.id_producto : ''}">
            <div class="row align-items-end">
                <div class="col-md-5 mb-2">
                    <label>Producto</label>
                    <input type="text" class="form-control descripcion" value="${item ? item.descripcion : ''}" placeholder="Descripción del item">
                </div>
                <div class="col-md-2 mb-2">
                    <label>Cantidad</label>
                    <input type="number" class="form-control cantidad" value="${item ? item.cantidad : 1}" min="1" onchange="calcularTotalGeneral()">
                </div>
                <div class="col-md-2 mb-2">
                    <label>Valor Unitario</label>
                    <input type="number" class="form-control valor-unitario" value="${item ? item.valor_unitario : 0}" min="0" onchange="calcularTotalGeneral()">
                </div>
                <div class="col-md-2 mb-2">
                    <label>Total</label>
                    <input type="text" class="form-control total-item" readonly>
                </div>
                <div class="col-md-1 mb-2">
                    <button type="button" class="btn btn-danger btn-block" onclick="eliminarItem('${itemId}')"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>`;
    $('#items-container').append(itemHtml);
    calcularTotalGeneral();
}

function calcularTotalGeneral() {
    let totalGeneral = 0;
    $('.item-row').each(function() {
        const cantidad = parseFloat($(this).find('.cantidad').val()) || 0;
        const valorUnitario = parseFloat($(this).find('.valor-unitario').val()) || 0;
        const totalItem = cantidad * valorUnitario;
        $(this).find('.total-item').val(`$${totalItem.toLocaleString('es-CO')}`);
        totalGeneral += totalItem;
    });
    $('#total-general').text(`$${totalGeneral.toLocaleString('es-CO')}`);
}

function eliminarItem(id) {
    if ($('.item-row').length > 1) {
        $(`#${id}`).remove();
        calcularTotalGeneral();
    } else {
        Swal.fire('Advertencia', 'Debe haber al menos un item.', 'warning');
    }
}
</script>
