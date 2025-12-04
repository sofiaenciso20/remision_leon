<?php
// models/Producto.php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $conn;
    private $table_name = "productos";

    public $id_producto;
    public $nombre_producto;
    public $fecha_creacion;
    public $maneja_inventario;
    public $stock_actual;
    public $stock_minimo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los productos ORDENADOS POR ID ASCENDENTE
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_producto ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener producto por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_producto = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear() {
        // Verificar si el producto ya existe
        $query_check = "SELECT id_producto FROM " . $this->table_name . " WHERE nombre_producto = :nombre_producto";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':nombre_producto', $this->nombre_producto);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            throw new Exception("El producto ya existe en la base de datos");
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                 SET nombre_producto = :nombre_producto,
                     maneja_inventario = :maneja_inventario,
                     stock_actual = :stock_actual,
                     stock_minimo = :stock_minimo";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre_producto", $this->nombre_producto);
        $stmt->bindParam(":maneja_inventario", $this->maneja_inventario);
        $stmt->bindParam(":stock_actual", $this->stock_actual);
        $stmt->bindParam(":stock_minimo", $this->stock_minimo);
        
        if ($stmt->execute()) {
            $this->id_producto = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Actualizar stock
    public function actualizarStock($id_producto, $nuevo_stock) {
        $query = "UPDATE " . $this->table_name . " 
                  SET stock_actual = :stock_actual 
                  WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":stock_actual", $nuevo_stock);
        $stmt->bindParam(":id_producto", $id_producto);
        return $stmt->execute();
    }

    // Buscar productos ORDENADOS POR ID ASCENDENTE
    public function buscar($termino = "") {
        $query = "SELECT id_producto as id, nombre_producto as text, 
                         maneja_inventario, stock_actual, stock_minimo
                 FROM " . $this->table_name . " 
                 WHERE nombre_producto LIKE :termino 
                 ORDER BY id_producto ASC 
                 LIMIT 10";
        
        $stmt = $this->conn->prepare($query);
        $termino = '%' . $termino . '%';
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar información del producto
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                 SET nombre_producto = :nombre_producto,
                     maneja_inventario = :maneja_inventario,
                     stock_actual = :stock_actual,
                     stock_minimo = :stock_minimo
                 WHERE id_producto = :id_producto";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre_producto", $this->nombre_producto);
        $stmt->bindParam(":maneja_inventario", $this->maneja_inventario);
        $stmt->bindParam(":stock_actual", $this->stock_actual);
        $stmt->bindParam(":stock_minimo", $this->stock_minimo);
        $stmt->bindParam(":id_producto", $this->id_producto);
        
        return $stmt->execute();
    }

    // Eliminar producto
    public function eliminar($id_producto) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_producto", $id_producto);
        return $stmt->execute();
    }

    // Obtener productos con bajo stock ORDENADOS POR ID ASCENDENTE
    public function obtenerBajoStock() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE maneja_inventario = 1 AND stock_actual <= stock_minimo 
                  ORDER BY id_producto ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos que manejan inventario ORDENADOS POR ID ASCENDENTE
    public function obtenerConInventario() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE maneja_inventario = 1 
                  ORDER BY id_producto ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>