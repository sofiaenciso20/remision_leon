<!-- Modal Nuevo Producto -->
<div class="modal fade" id="nuevoProductoModal" tabindex="-1" aria-labelledby="nuevoProductoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formNuevoProducto">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoProductoModalLabel"><i class="fas fa-plus-circle text-success"></i> Nuevo Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_producto">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="maneja_inventario" name="maneja_inventario" value="1" checked>
                        <label class="form-check-label" for="maneja_inventario">Maneja Inventario</label>
                    </div>
                    <hr>
                    <div id="inventario-fields">
                        <div class="form-group">
                            <label for="stock_inicial">Stock Inicial</label>
                            <input type="number" class="form-control" id="stock_inicial" name="stock_inicial" min="0" value="0">
                        </div>
                        <div class="form-group">
                            <label for="stock_minimo">Stock Mínimo</label>
                            <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Entrada -->
<div class="modal fade" id="entradaModal" tabindex="-1" aria-labelledby="entradaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEntrada">
                <div class="modal-header">
                    <h5 class="modal-title" id="entradaModalLabel"><i class="fas fa-plus-circle text-primary"></i> Registrar Entrada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="entrada_id_producto" name="id_producto">
                    <p>Producto: <strong id="entrada_nombre_producto"></strong></p>
                    <div class="form-group">
                        <label for="cantidad_entrada">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad_entrada" name="cantidad" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="observaciones_entrada">Observaciones</label>
                        <textarea class="form-control" id="observaciones_entrada" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Entrada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Salida -->
<div class="modal fade" id="salidaModal" tabindex="-1" aria-labelledby="salidaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSalida">
                <div class="modal-header">
                    <h5 class="modal-title" id="salidaModalLabel"><i class="fas fa-minus-circle text-warning"></i> Registrar Salida</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="salida_id_producto" name="id_producto">
                    <p>Producto: <strong id="salida_nombre_producto"></strong></p>
                    <p>Stock Actual: <strong id="salida_stock_actual"></strong></p>
                    <div class="form-group">
                        <label for="cantidad_salida">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad_salida" name="cantidad" min="1" required>
                    </div>
                     <div class="form-group">
                        <label for="observaciones_salida">Observaciones</label>
                        <textarea class="form-control" id="observaciones_salida" name="observaciones" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Registrar Salida</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Historial -->
<div class="modal fade" id="historialModal" tabindex="-1" aria-labelledby="historialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historialModalLabel"><i class="fas fa-history text-info"></i> Historial de Movimientos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="contenido-historial"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle de campos de inventario en el modal de nuevo producto
$('#maneja_inventario').change(function() {
    if(this.checked) {
        $('#inventario-fields').show();
    } else {
        $('#inventario-fields').hide();
    }
});
</script>