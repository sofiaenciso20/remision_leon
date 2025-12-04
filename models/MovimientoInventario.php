<?php
class MovimientoInventario {
    private $conn;
    private $table_name = "movimientos_inventario";

    public $id_movimiento;
    public $id_producto;
    public $tipo_movimiento;
    public $cantidad;
    public $stock_anterior;
    public $stock_nuevo;
    public $motivo;
    public $id_remision;
    public $id_usuario;
    public $observaciones;
    public $fecha_movimiento;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_producto=:id_producto, tipo_movimiento=:tipo_movimiento, 
                      cantidad=:cantidad, stock_anterior=:stock_anterior, 
                      stock_nuevo=:stock_nuevo, motivo=:motivo, id_remision=:id_remision, 
                      id_usuario=:id_usuario, observaciones=:observaciones";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_producto", $this->id_producto);
        $stmt->bindParam(":tipo_movimiento", $this->tipo_movimiento);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":stock_anterior", $this->stock_anterior);
        $stmt->bindParam(":stock_nuevo", $this->stock_nuevo);
        $stmt->bindParam(":motivo", $this->motivo);
        $stmt->bindParam(":id_remision", $this->id_remision);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":observaciones", $this->observaciones);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function obtenerPorProducto($id_producto) {
        $query = "SELECT m.*, u.name as usuario_nombre 
                  FROM " . $this->table_name . " m 
                  LEFT JOIN usuarios u ON m.id_usuario = u.id 
                  WHERE m.id_producto = :id_producto 
                  ORDER BY m.fecha_movimiento DESC 
                  LIMIT 50";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>