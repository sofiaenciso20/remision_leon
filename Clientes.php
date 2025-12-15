<?php include 'views/layout/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-users"></i> Gestión de Clientes</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Clientes</h3>
                        <div class="card-tools">
                            <button class="btn btn-primary" id="btn-nuevo-cliente">
                                <i class="fas fa-plus"></i> Nuevo Cliente
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                         <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" id="buscar-cliente" class="form-control" placeholder="Buscar por nombre o NIT...">
                            </div>
                        </div>
                        <div id="alertas-clientes"></div>
                        <div class="table-responsive">
                            <table id="tabla-clientes" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>NIT/Cédula</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Carga dinámica -->
                                </tbody>
                            </table>
                        </div>
                         <div id="cargando" class="text-center" style="display: none;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                        </div>
                        <div id="sin-resultados" class="text-center" style="display: none;">
                             <p>No se encontraron clientes.</p>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                         <div id="info-paginacion"></div>
                         <ul id="paginacion" class="pagination pagination-sm m-0 float-right"></ul>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php include 'views/clientes/modals.php'; ?>
<?php include 'views/layout/footer.php'; ?>

<script>
$(document).ready(function() {
    let paginaActual = 1;

    function cargarClientes(pagina = 1) {
        paginaActual = pagina;
        const termino = $('#buscar-cliente').val();

        $('#cargando').show();
        $('#tabla-clientes tbody').empty();
        $('#sin-resultados').hide();

        $.ajax({
            url: 'ajax/listar_clientes.php',
            type: 'GET',
            dataType: 'json',
            data: { pagina, termino },
            success: function(data) {
                $('#cargando').hide();
                if(data.success && data.clientes.length) {
                    renderizarTabla(data.clientes);
                    renderizarPaginacion(data.paginacion);
                } else {
                    $('#sin-resultados').show();
                    $('#paginacion').empty();
                    $('#info-paginacion').empty();
                }
            },
            error: function() {
                $('#cargando').hide();
                mostrarAlerta('Error al cargar los clientes.', 'danger');
            }
        });
    }

    function renderizarTabla(clientes) {
        const tbody = $('#tabla-clientes tbody');
        tbody.empty();
        clientes.forEach(cliente => {
            const fila = `
                <tr>
                    <td>${cliente.id_cliente}</td>
                    <td>${escapeHTML(cliente.nombre_cliente)}</td>
                    <td>${escapeHTML(cliente.nit)}</td>
                    <td>${escapeHTML(cliente.telefono) || '-'}</td>
                    <td>${escapeHTML(cliente.correo) || '-'}</td>
                    <td>
                        <button class="btn btn-info btn-sm btn-ver" data-id="${cliente.id_cliente}"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-warning btn-sm btn-editar" data-id="${cliente.id_cliente}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm btn-eliminar" data-id="${cliente.id_cliente}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            tbody.append(fila);
        });
    }

    function renderizarPaginacion(p) {
        const paginacion = $('#paginacion');
        paginacion.empty();
        if(p.totalPaginas <= 1) {
             $('#info-paginacion').text(`Mostrando ${p.total} clientes`);
             return;
        }

        let html = '';
        html += `<li class="page-item ${p.pagina === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-pagina="${p.pagina - 1}">&laquo;</a></li>`;
        for(let i = 1; i <= p.totalPaginas; i++) {
            html += `<li class="page-item ${p.pagina === i ? 'active' : ''}"><a class="page-link" href="#" data-pagina="${i}">${i}</a></li>`;
        }
        html += `<li class="page-item ${p.pagina === p.totalPaginas ? 'disabled' : ''}"><a class="page-link" href="#" data-pagina="${p.pagina + 1}">&raquo;</a></li>`;
        paginacion.html(html);

        const inicio = (p.pagina - 1) * p.porPagina + 1;
        const fin = Math.min(inicio + p.porPagina - 1, p.total);
        $('#info-paginacion').text(`Mostrando ${inicio}-${fin} de ${p.total} clientes`);
    }

    // --- Event Handlers ---
    $('#buscar-cliente').on('input', function() {
        clearTimeout(this.delay);
        this.delay = setTimeout(() => cargarClientes(1), 500);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const pagina = $(this).data('pagina');
        if(pagina) cargarClientes(pagina);
    });

    $('#btn-nuevo-cliente').click(function() {
        $('#formCliente')[0].reset();
        $('#id_cliente').val('');
        $('#clienteModalLabel').text('Nuevo Cliente');
        $('#clienteModal .btn-primary').text('Crear');
        $('#clienteModal').modal('show');
    });

    $('#tabla-clientes').on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `ajax/obtener_cliente.php?id=${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.success) {
                    $('#id_cliente').val(data.cliente.id_cliente);
                    $('#nombre_cliente').val(data.cliente.nombre_cliente);
                    $('#nit').val(data.cliente.nit);
                    $('#tipo_cliente').val(data.cliente.tipo_cliente);
                    $('#direccion').val(data.cliente.direccion);
                    $('#telefono').val(data.cliente.telefono);
                    $('#correo').val(data.cliente.correo);
                    $('#clienteModalLabel').text('Editar Cliente');
                    $('#clienteModal .btn-primary').text('Actualizar');
                    $('#clienteModal').modal('show');
                } else {
                    mostrarAlerta('No se pudo cargar el cliente.', 'danger');
                }
            }
        });
    });

    $('#tabla-clientes').on('click', '.btn-ver', function() {
         const id = $(this).data('id');
        $.ajax({
            url: `ajax/obtener_cliente.php?id=${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if(data.success) {
                    $('#ver_id_cliente').text(data.cliente.id_cliente);
                    $('#ver_nombre_cliente').text(data.cliente.nombre_cliente);
                    $('#ver_nit').text(data.cliente.nit);
                    $('#ver_tipo_cliente').text(data.cliente.tipo_cliente);
                    $('#ver_direccion').text(data.cliente.direccion || '-');
                    $('#ver_telefono').text(data.cliente.telefono || '-');
                    $('#ver_correo').text(data.cliente.correo || '-');
                    $('#verClienteModal').modal('show');
                } else {
                     mostrarAlerta('No se pudieron cargar los detalles del cliente.', 'danger');
                }
            }
        });
    });

    $('#tabla-clientes').on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/eliminar_cliente.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { id_cliente: id },
                    success: function(data) {
                        if(data.success) {
                            mostrarAlerta('Cliente eliminado con éxito.', 'success');
                            cargarClientes(paginaActual);
                        } else {
                            mostrarAlerta(data.message || 'No se pudo eliminar el cliente.', 'danger');
                        }
                    }
                });
            }
        });
    });

    $('#formCliente').submit(function(e) {
        e.preventDefault();
        const id = $('#id_cliente').val();
        const url = id ? 'ajax/actualizar_cliente.php' : 'ajax/crear_cliente.php';
        const successMsg = id ? 'Cliente actualizado' : 'Cliente creado';

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                if(data.success) {
                    $('#clienteModal').modal('hide');
                    mostrarAlerta(successMsg + ' con éxito.', 'success');
                    cargarClientes(id ? paginaActual : 1);
                } else {
                    mostrarAlerta(data.message || 'Ocurrió un error.', 'danger');
                }
            },
            error: function() {
                 mostrarAlerta('Error de conexión.', 'danger');
            }
        });
    });

    function mostrarAlerta(mensaje, tipo) {
        const alerta = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                          ${mensaje}
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>`;
        $('#alertas-clientes').html(alerta);
    }

    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return str.toString().replace(/[&<>"']/g, function(match) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[match];
        });
    }

    // Initial Load
    cargarClientes();
});
</script>