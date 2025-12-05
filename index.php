<?php
require_once 'config/database.php';
require_once 'models/Cliente.php';
require_once 'models/Remision.php';
require_once 'models/PersonaContacto.php';
require_once 'models/Producto.php';

// Manejar acciones
$action = $_GET['action'] ?? 'nueva_remision';

switch ($action) {
    case 'inventario':
        include 'inventario.php';
        exit;
    case 'nueva_remision':
    default:
        // Continuar con el código existente para nueva remisión
        break;
}

// Crear conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancias de los modelos pasando la conexión
$cliente = new Cliente($db);
$remision = new Remision($db);
$personaContacto = new PersonaContacto($db);
$producto = new Producto($db);

$siguiente_numero = $remision->generarNumeroRemision();

include 'views/layout/header.php';
?>

<style>
/* Ajustes CSS para que Select2 respete el ancho y botones se mantengan */
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
.select-group {
    display: flex;
    gap: 8px;
    align-items: flex-start;
}
.select-group .form-control {
    flex: 1;
    min-width: 0;
}
.select-group .btn {
    white-space: nowrap;
    flex-shrink: 0;
}
@media (max-width: 768px) {
    .select-group {
        flex-direction: column;
    }
    .select-group .btn {
        width: 100%;
    }
}
/* Asegurar que Select2 ocupe el 100% */
.select2-container {
    width: 100% !important;
}
.producto-search-container {
    display:flex;
    gap:8px;
    align-items:center;
}
</style>

<!-- Content Header -->
<div class="content-header bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-file-invoice mr-2"></i>Nueva Remisión
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
                <a href="listar_remisiones.php" class="btn btn-outline-info">
                    <i class="fas fa-list mr-1"></i> Ver Remisiones
                </a>
                <a href="inventario.php" class="btn btn-outline-secondary">
                    <i class="fas fa-boxes mr-1"></i> Ir a Inventario
                </a>
            </div>
        </div>

        <!-- Alertas -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipo_mensaje']; ?> alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <div><?php echo $_SESSION['mensaje']; ?></div>
                </div>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <div class="card card-hover shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-invoice text-primary mr-2"></i> Datos de la Remisión
                    </h3>
                </div>
            </div>

            <form id="formRemision">
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="form-group">
                                <label for="numero_remision" class="form-label">Número de Remisión</label>
                                <input type="text" class="form-control form-control-lg" id="numero_remision" name="numero_remision"
                                       value="<?php echo htmlspecialchars($siguiente_numero); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="form-group">
                                <label for="fecha_emision" class="form-label">Fecha de Emisión *</label>
                                <input type="date" class="form-control form-control-lg" id="fecha_emision" name="fecha_emision"
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <!-- Tipo de Remisión -->
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

                    <!-- Fila para Cliente, Persona que Recibe y Persona Responsable -->
                    <div class="row mb-4">
                        <!-- Cliente -->
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="cliente" class="form-label">Cliente *</label>
                                <div class="select-group">
                                    <select class="form-control select2-cliente" id="cliente" name="id_cliente" required>
                                        <option value="">Buscar cliente...</option>
                                    </select>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalCliente">
                                        <i class="fas fa-plus mr-1"></i> Nuevo
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Persona que recibe (id_persona) -->
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="persona_contacto" class="form-label">Persona que Recibe</label>
                                <div class="select-group">
                                    <select class="form-control" id="persona_contacto" name="id_persona">
                                        <option value="">Seleccione...</option>
                                    </select>
                                    <button type="button" class="btn btn-success" onclick="abrirModalPersonaContacto()">
                                        <i class="fas fa-plus mr-1"></i> Nueva
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Persona responsable (id_persona_responsable) -->
                        <div class="col-lg-4 mb-3">
                            <div class="form-group">
                                <label for="persona_responsable" class="form-label">Persona Responsable</label>
                                <div class="select-group">
                                    <select class="form-control" id="persona_responsable" name="id_persona_responsable">
                                        <option value="">Seleccione...</option>
                                    </select>
                                    <button type="button" class="btn btn-success" onclick="abrirModalPersonaResponsable()">
                                        <i class="fas fa-plus mr-1"></i> Nueva
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                                  placeholder="Ingrese observaciones adicionales..."></textarea>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                        <h5 class="mb-2 mb-md-0"><i class="fas fa-list mr-2"></i> Items de la Remisión</h5>
                        <button type="button" class="btn btn-success" onclick="agregarItem()">
                            <i class="fas fa-plus mr-1"></i> Agregar Item
                        </button>
                    </div>

                    <div id="items-container" class="mb-4">
                        <!-- Los items se agregarán aquí dinámicamente -->
                    </div>

                    <div class="total-general-container fade-in">
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

                <div class="card-footer bg-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-end">
                        <button type="button" class="btn btn-outline-secondary mb-2 mb-md-0 mr-md-2" onclick="limpiarFormulario()">
                            <i class="fas fa-broom mr-1"></i> Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Guardar Remisión
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-labelledby="modalClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0" id="modalClienteLabel">
                    <i class="fas fa-user-plus text-primary mr-2"></i> Nuevo Cliente
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCliente">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <div class="form-group">
                                <label for="nombre_cliente" class="form-label">Nombre del Cliente *</label>
                                <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="tipo_cliente" class="form-label">Tipo *</label>
                                <select class="form-control" id="tipo_cliente" name="tipo_cliente" required>
                                    <option value="persona">Persona</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="nit" class="form-label">NIT/Cédula *</label>
                                <input type="text" class="form-control" id="nit" name="nit" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="telefono_cliente" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono_cliente" name="telefono">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>

                    <div class="form-group mb-3">
                        <label for="correo_cliente" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo_cliente" name="correo">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear persona de contacto -->
<div class="modal fade" id="modalPersonaContacto" tabindex="-1" role="dialog" aria-labelledby="modalPersonaContactoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0" id="modalPersonaContactoLabel">
                    <i class="fas fa-user-plus text-primary mr-2"></i> Nueva Persona que Recibe
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonaContacto">
                <div class="modal-body p-4">
                    <input type="hidden" id="cliente_persona_contacto" name="id_cliente">

                    <div class="form-group mb-3">
                        <label for="nombre_persona_contacto" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="nombre_persona_contacto" name="nombre_persona" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="cargo_persona_contacto" class="form-label">Cargo</label>
                                <input type="text" class="form-control" id="cargo_persona_contacto" name="cargo">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="telefono_persona_contacto" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono_persona_contacto" name="telefono">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="correo_persona_contacto" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo_persona_contacto" name="correo">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Guardar Persona
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear persona responsable (nuevo) -->
<div class="modal fade" id="modalPersonaResponsable" tabindex="-1" role="dialog" aria-labelledby="modalPersonaResponsableLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0" id="modalPersonaResponsableLabel">
                    <i class="fas fa-user-plus text-primary mr-2"></i> Nueva Persona Responsable
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPersonaResponsable">
                <div class="modal-body p-4">

                    <input type="hidden" id="cliente_persona_responsable" name="id_cliente">

                    <div class="form-group mb-3">
                        <label for="nombre_persona_responsable" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="nombre_persona_responsable" name="nombre_responsable" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="correo_persona_responsable" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo_persona_responsable" name="correo">
                    </div>

                    <div class="form-group mb-3">
                        <label for="telefono_persona_responsable" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono_persona_responsable" name="telefono">
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Guardar Responsable
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para crear producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-labelledby="modalProductoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title mb-0" id="modalProductoLabel">
                    <i class="fas fa-box text-primary mr-2"></i> Nuevo Producto
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formProducto">
                <div class="modal-body p-4">
                    <input type="hidden" id="item_index_producto" name="item_index">

                    <div class="form-group mb-3">
                        <label for="nombre_producto" class="form-label">Nombre del Producto *</label>
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required
                               placeholder="Ingrese el nombre del producto">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    cargarSiguienteNumero();

    // Inicializar Select2 para búsqueda de clientes
    $('#cliente').select2({
        placeholder: 'Buscar cliente...',
        allowClear: true,
        width: '100%',
        dropdownParent: $(document.body),
        ajax: {
            url: 'ajax/buscar_clientes.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { termino: params.term };
            },
            processResults: function (data) {
                if (!Array.isArray(data)) return { results: [] };
                return {
                    results: data.map(function(item) {
                        return { id: item.id || item.id_cliente, text: item.text || item.nombre_cliente || item.nombre };
                    })
                };
            },
            cache: true
        }
    });

    // Cambio de cliente
    $('#cliente').on('change', function() {
        const clienteId = $(this).val();

        if (clienteId) {
            cargarPersonasContacto(clienteId);
            cargarPersonasResponsable(clienteId);

            $('#cliente_persona_contacto').val(clienteId);
            $('#cliente_persona_responsable').val(clienteId);
        } else {
            $('#persona_contacto').empty().append('<option value="">Seleccione...</option>');
            $('#persona_responsable').empty().append('<option value="">Seleccione...</option>');
            $('#cliente_persona_contacto').val('');
            $('#cliente_persona_responsable').val('');
        }
    });

    // Crear remisión
    $('#formRemision').on('submit', function(e) {
        e.preventDefault();

        const items = [];
        let itemsValidos = true;

        $('.item-row').each(function() {
            const productoId = $(this).find('.id-producto').val();
            const descripcion = $(this).find('.descripcion').val();
            const cantidad = $(this).find('.cantidad').val();
            const valorUnitario = $(this).find('.valor-unitario').val();

            if (!descripcion || !cantidad) {
                itemsValidos = false;
                return;
            }

            items.push({
                id_producto: productoId || null,
                descripcion: descripcion,
                cantidad: parseInt(cantidad),
                valor_unitario: parseFloat(valorUnitario) || 0
            });
        });

        if (!itemsValidos) {
            Swal.fire('Error', 'Todos los items deben tener al menos descripción y cantidad', 'error');
            return;
        }

        if (items.length === 0) {
            Swal.fire('Error', 'Debe agregar al menos un item a la remisión', 'error');
            return;
        }

        const formData = new FormData(this);
        formData.append('items', JSON.stringify(items));

        $.ajax({
            url: 'ajax/crear_remision.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: `Remisión #${response.numero_remision || response.id_remision} creada correctamente`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Imprimir PDF',
                        cancelButtonText: 'Crear Nueva',
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(`generar_pdf.php?id=${response.id_remision}`, '_blank');
                        }
                        limpiarFormulario();
                        cargarSiguienteNumero();
                    });
                } else {
                    Swal.fire('Error', response.message || 'Error al crear remisión', 'error');
                }
            },
            error: function(xhr) {
                console.log('Error AJAX crear_remision:', xhr.responseText);
                Swal.fire('Error', 'Error al procesar la solicitud', 'error');
            }
        });
    });

    // Crear cliente
    $('#formCliente').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'ajax/crear_cliente.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalCliente').modal('hide');
                    $('#formCliente')[0].reset();

                    const id = response.cliente?.id_cliente || response.id_cliente;
                    const text = response.cliente?.nombre_cliente || response.nombre_cliente;

                    if (id && text) {
                        const newOption = new Option(text, id, true, true);
                        $('#cliente').append(newOption).trigger('change');
                    }

                    Swal.fire('¡Éxito!', 'Cliente creado correctamente', 'success');
                } else {
                    Swal.fire('Error', response.message || 'Error al crear el cliente', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Error al crear el cliente', 'error');
            }
        });
    });

    // Crear persona de contacto
    $('#formPersonaContacto').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'ajax/crear_persona_contacto.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalPersonaContacto').modal('hide');
                    $('#formPersonaContacto')[0].reset();

                    const nuevaPersona = response.persona || response.data;
                    const clienteId = $('#cliente').val();

                    cargarPersonasContacto(clienteId);
                    $('#persona_contacto').val(nuevaPersona.id_persona);

                    Swal.fire('¡Éxito!', 'Persona creada correctamente', 'success');
                } else {
                    Swal.fire('Error', response.message || 'Error al crear la persona', 'error');
                }
            }
        });
    });

    // Crear persona responsable
    $('#formPersonaResponsable').on('submit', function(e) {
        e.preventDefault();

        const clienteId = $('#cliente').val();
        if (!clienteId) {
            Swal.fire('Advertencia', 'Debe seleccionar un cliente antes de crear un responsable', 'warning');
            return;
        }

        const data = {
            id_cliente: $('#cliente_persona_responsable').val(),
            nombre_responsable: $('#nombre_persona_responsable').val(),
            correo: $('#correo_persona_responsable').val(),
            telefono: $('#telefono_persona_responsable').val()
        };

        $.ajax({
            url: 'ajax/crear_persona_responsable.php',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#modalPersonaResponsable').modal('hide');
                    $('#formPersonaResponsable')[0].reset();

                    const newId = response.id || response.id_responsable;

                    cargarPersonasResponsable(clienteId, newId);

                    Swal.fire('¡Éxito!', 'Persona responsable creada correctamente', 'success');
                } else {
                    Swal.fire('Error', response.message || 'Error al crear la persona responsable', 'error');
                }
            }
        });
    });

    // Inicialización primer item
    agregarItem();
});

let contadorItems = 0;

function agregarItem() {
    contadorItems++;
    const itemHtml = `
        <div class="item-row fade-in" id="item-${contadorItems}">
            <div class="row align-items-end">
                <div class="col-md-5 col-lg-6 mb-2">
                    <div class="form-group">
                        <label for="producto-${contadorItems}">Producto *</label>
                        <div class="producto-search-container">
                            <select class="form-control select2-producto" id="producto-${contadorItems}"
                                    data-item-index="${contadorItems}" style="width: 100%;">
                                <option value="">Buscar producto...</option>
                            </select>
                            <button type="button" class="btn btn-outline-secondary btn-nuevo-producto"
                                    onclick="abrirModalProducto(${contadorItems})" title="Nuevo producto">
                                <i class="fas fa-plus"></i> Nuevo
                            </button>
                        </div>
                        <input type="hidden" class="id-producto" id="id_producto-${contadorItems}">
                        <input type="hidden" class="descripcion" id="descripcion-${contadorItems}">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2 mb-2">
                    <div class="form-group">
                        <label for="cantidad-${contadorItems}">Cantidad *</label>
                        <input type="number" class="form-control cantidad" id="cantidad-${contadorItems}"
                               min="1" value="1" required onchange="calcularTotalItem(${contadorItems})">
                    </div>
                </div>

                <div class="col-6 col-md-2 col-lg-2 mb-2">
                    <div class="form-group">
                        <label for="valor-unitario-${contadorItems}">Valor Unitario</label>
                        <input type="number" class="form-control valor-unitario" id="valor-unitario-${contadorItems}"
                               min="0" step="0.01" placeholder="0.00" onchange="calcularTotalItem(${contadorItems})">
                    </div>
                </div>

                <div class="col-6 col-md-2 col-lg-1 mb-2">
                    <div class="form-group">
                        <label for="total-item-${contadorItems}">Total</label>
                        <input type="text" class="form-control total-item text-center" id="total-item-${contadorItems}"
                               readonly placeholder="$0.00">
                    </div>
                </div>

                <div class="col-6 col-md-1 col-lg-1 mb-2">
                    <div class="form-group">
                        <label class="d-block d-md-none">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block" onclick="eliminarItem(${contadorItems})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    `;

    $('#items-container').append(itemHtml);

    // Inicializar select2 productos
    $(`#producto-${contadorItems}`).select2({
        placeholder: 'Buscar producto...',
        allowClear: true,
        width: '100%',
        dropdownParent: $(document.body),
        ajax: {
            url: 'ajax/buscar_productos.php',
            dataType: 'json',
            delay: 250,
            data: params => ({ termino: params.term }),
            processResults: function (data) {
                if (!Array.isArray(data)) return { results: [] };
                return {
                    results: data.map(item => ({
                        id: item.id || item.id_producto,
                        text: item.text || item.nombre_producto
                    }))
                };
            },
            cache: true
        }
    }).on('change', function() {
        const itemIndex = $(this).data('item-index');
        const selectedProduct = $(this).select2('data')[0];

        if (selectedProduct) {
            $(`#id_producto-${itemIndex}`).val(selectedProduct.id);
            $(`#descripcion-${itemIndex}`).val(selectedProduct.text);
        }
    });
}

function calcularTotalItem(itemId) {
    const cantidad = parseFloat($(`#cantidad-${itemId}`).val()) || 0;
    const valorUnitario = parseFloat($(`#valor-unitario-${itemId}`).val()) || 0;
    const total = cantidad * valorUnitario;

    $(`#total-item-${itemId}`).val(total > 0 ? `$${total.toLocaleString('es-CO', {minimumFractionDigits: 2})}` : '$0.00');

    calcularTotalGeneral();
}

function calcularTotalGeneral() {
    let totalGeneral = 0;

    $('.item-row').each(function() {
        const cantidad = parseFloat($(this).find('.cantidad').val()) || 0;
        const valorUnitario = parseFloat($(this).find('.valor-unitario').val()) || 0;
        totalGeneral += cantidad * valorUnitario;
    });

    $('#total-general').text(totalGeneral > 0 ? `$${totalGeneral.toLocaleString('es-CO', {minimumFractionDigits: 2})}` : '$0.00');
}

function eliminarItem(id) {
    if ($('.item-row').length > 1) {
        $(`#item-${id}`).remove();
        calcularTotalGeneral();
    } else {
        Swal.fire('Advertencia', 'Debe mantener al menos un item', 'warning');
    }
}

function limpiarFormulario() {
    $('#formRemision')[0].reset();
    $('#cliente').val(null).trigger('change');
    $('#persona_contacto').empty().append('<option value="">Seleccione...</option>');
    $('#persona_responsable').empty().append('<option value="">Seleccione...</option>');
    $('#items-container').empty();
    contadorItems = 0;
    agregarItem();

    cargarSiguienteNumero();
}

function cargarPersonasContacto(clienteId) {
    $.ajax({
        url: 'ajax/obtener_personas_contacto.php',
        method: 'POST',
        data: { id_cliente: clienteId },
        dataType: 'json',
        success: function(personas) {
            $('#persona_contacto').empty().append('<option value="">Seleccione...</option>');

            if (Array.isArray(personas)) {
                personas.forEach(function(persona) {
                    $('#persona_contacto').append(
                        `<option value="${persona.id_persona}">${persona.nombre_persona}</option>`
                    );
                });
            }
        }
    });
}

function cargarPersonasResponsable(clienteId, seleccionarId = null) {
    $.ajax({
        url: 'ajax/obtener_persona_responsable.php',
        method: 'POST',
        data: { id_cliente: clienteId },
        dataType: 'json',
        success: function(personas) {
            $('#persona_responsable').empty().append('<option value="">Seleccione...</option>');

            if (Array.isArray(personas)) {
                personas.forEach(function(persona) {
                    const id = persona.id_persona || persona.id || persona.id_responsable;
                    const nombre = persona.nombre_persona || persona.nombre || persona.nombre_responsable;

                    const selectedAttr = (seleccionarId && seleccionarId == id) ? 'selected' : '';

                    $('#persona_responsable').append(
                        `<option value="${id}" ${selectedAttr}>${nombre}</option>`
                    );
                });
            }

            if (seleccionarId) {
                $('#persona_responsable').val(seleccionarId);
            }
        }
    });
}

function cargarSiguienteNumero() {
    $.ajax({
        url: 'ajax/obtener_siguiente_numero.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#numero_remision').val(response.success ? response.siguiente_numero : 1);
        }
    });
}

function abrirModalPersonaContacto() {
    const clienteId = $('#cliente').val();
    if (!clienteId) {
        Swal.fire('Advertencia', 'Debe seleccionar un cliente primero', 'warning');
        return;
    }
    $('#cliente_persona_contacto').val(clienteId);
    $('#modalPersonaContacto').modal('show');
}

function abrirModalPersonaResponsable() {
    const clienteId = $('#cliente').val();
    if (!clienteId) {
        Swal.fire('Advertencia', 'Debe seleccionar un cliente primero', 'warning');
        return;
    }
    $('#cliente_persona_responsable').val(clienteId);
    $('#modalPersonaResponsable').modal('show');
}

function abrirModalProducto(itemIndex) {
    $('#item_index_producto').val(itemIndex);
    $('#modalProducto').modal('show');
}
</script>


<?php include 'views/layout/footer.php'; ?>
