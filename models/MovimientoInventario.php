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

    public function contarMovimientosPorProducto($id_producto) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function obtenerMovimientosPaginadosPorProducto($id_producto, $offset, $registros_por_pagina) {
        // Since there is no user table, we will return a static name.
        $query = "SELECT m.*, 'Sistema' as usuario_nombre
                  FROM " . $this->table_name . " m
                  WHERE m.id_producto = :id_producto
                  ORDER BY m.fecha_movimiento DESC
                  LIMIT :offset, :registros_por_pagina";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":registros_por_pagina", $registros_por_pagina, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
