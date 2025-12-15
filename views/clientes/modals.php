<!-- Modal Crear/Editar Cliente -->
<div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCliente">
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <div class="form-group">
                        <label for="nombre_cliente">Nombre</label>
                        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
                    </div>
                    <div class="form-group">
                        <label for="nit">NIT/Cédula</label>
                        <input type="text" class="form-control" id="nit" name="nit" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_cliente">Tipo</label>
                        <select class="form-control" id="tipo_cliente" name="tipo_cliente" required>
                            <option value="persona">Persona</option>
                            <option value="empresa">Empresa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                     <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Cliente -->
<div class="modal fade" id="verClienteModal" tabindex="-1" aria-labelledby="verClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verClienteModalLabel">Detalles del Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">ID</dt>
                    <dd class="col-sm-8" id="ver_id_cliente"></dd>

                    <dt class="col-sm-4">Nombre</dt>
                    <dd class="col-sm-8" id="ver_nombre_cliente"></dd>

                    <dt class="col-sm-4">NIT/Cédula</dt>
                    <dd class="col-sm-8" id="ver_nit"></dd>

                    <dt class="col-sm-4">Tipo</dt>
                    <dd class="col-sm-8" id="ver_tipo_cliente"></dd>

                    <dt class="col-sm-4">Dirección</dt>
                    <dd class="col-sm-8" id="ver_direccion"></dd>

                    <dt class="col-sm-4">Teléfono</dt>
                    <dd class="col-sm-8" id="ver_telefono"></dd>

                    <dt class="col-sm-4">Correo</dt>
                    <dd class="col-sm-8" id="ver_correo"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>