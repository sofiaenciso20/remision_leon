<!-- views/modals/personas_contacto_modals.php -->

<!-- Modal Crear -->
<div class="modal fade" id="modalCrearPersonaContacto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCrearPersonaContacto">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Persona de Contacto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Nombre*</label><input type="text" name="nombre_persona" class="form-control" required></div>
                        <div class="col-md-6 form-group"><label>Cargo</label><input type="text" name="cargo" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control"></div>
                        <div class="col-md-6 form-group"><label>Correo</label><input type="email" name="correo" class="form-control"></div>
                    </div>
                    <div class="form-group">
                        <label>Cliente*</label>
                        <select name="id_cliente" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($clientes as $clienteOpt): ?>
                            <option value="<?php echo $clienteOpt['id_cliente']; ?>"><?php echo htmlspecialchars($clienteOpt['nombre_cliente']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver -->
<div class="modal fade" id="modalVerPersonaContacto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Persona</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-3">ID</dt><dd class="col-sm-9" id="ver_id_persona"></dd>
                    <dt class="col-sm-3">Nombre</dt><dd class="col-sm-9" id="ver_nombre_persona"></dd>
                    <dt class="col-sm-3">Cargo</dt><dd class="col-sm-9" id="ver_cargo"></dd>
                    <dt class="col-sm-3">Teléfono</dt><dd class="col-sm-9" id="ver_telefono"></dd>
                    <dt class="col-sm-3">Correo</dt><dd class="col-sm-9" id="ver_correo"></dd>
                    <dt class="col-sm-3">Cliente</dt><dd class="col-sm-9" id="ver_cliente"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditarPersonaContacto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditarPersonaContacto">
                <input type="hidden" name="id_persona" id="editar_id_persona">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Persona de Contacto</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Nombre*</label><input type="text" name="nombre_persona" id="editar_nombre_persona" class="form-control" required></div>
                        <div class="col-md-6 form-group"><label>Cargo</label><input type="text" name="cargo" id="editar_cargo" class="form-control"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>Teléfono</label><input type="text" name="telefono" id="editar_telefono" class="form-control"></div>
                        <div class="col-md-6 form-group"><label>Correo</label><input type="email" name="correo" id="editar_correo" class="form-control"></div>
                    </div>
                    <div class="form-group">
                        <label>Cliente*</label>
                        <select name="id_cliente" id="editar_id_cliente" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($clientes as $clienteOpt): ?>
                            <option value="<?php echo $clienteOpt['id_cliente']; ?>"><?php echo htmlspecialchars($clienteOpt['nombre_cliente']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
