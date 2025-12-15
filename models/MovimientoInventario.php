<?php
// models/MovimientoInventario.php
require_once __DIR__ . '/../config/database.php';

class MovimientoInventario {
    private $conn;
    private $table_name = "movimientos_inventario";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrarMovimiento($id_producto, $tipo_movimiento, $cantidad, $stock_anterior, $stock_nuevo, $id_remision = null, $observaciones = '') {
        $query = "INSERT INTO " . $this->table_name . "
                  SET id_producto = :id_producto,
                      tipo_movimiento = :tipo_movimiento,
                      cantidad = :cantidad,
                      stock_anterior = :stock_anterior,
                      stock_nuevo = :stock_nuevo,
                      id_remision = :id_remision,
                      observaciones = :observaciones";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->bindParam(":tipo_movimiento", $tipo_movimiento);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":stock_anterior", $stock_anterior);
        $stmt->bindParam(":stock_nuevo", $stock_nuevo);
        $stmt->bindParam(":id_remision", $id_remision);
        $stmt->bindParam(":observaciones", $observaciones);

        return $stmt->execute();
    }

    public function obtenerPorProducto($id_producto) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE id_producto = :id_producto
                  ORDER BY fecha_movimiento DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
