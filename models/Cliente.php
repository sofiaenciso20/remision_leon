<?php
// models/Cliente.php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $conn;
    private $table_name = "clientes";

    public $id_cliente;
    public $nombre_cliente;
    public $tipo_cliente;
    public $nit;
    public $direccion;
    public $telefono;
    public $correo;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        // Primero verificar si el NIT ya existe
        $query_check = "SELECT id_cliente FROM " . $this->table_name . " WHERE nit = :nit";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':nit', $this->nit);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            throw new Exception("El NIT ya existe en la base de datos");
        }

        $query = "INSERT INTO " . $this->table_name . " 
        (nombre_cliente, tipo_cliente, nit, direccion, telefono, correo)
        VALUES (:nombre_cliente, :tipo_cliente, :nit, :direccion, :telefono, :correo)";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre_cliente = htmlspecialchars(strip_tags($this->nombre_cliente));
        $this->tipo_cliente = htmlspecialchars(strip_tags($this->tipo_cliente));
        $this->nit = htmlspecialchars(strip_tags($this->nit));
        
        // Manejar valores NULL para campos opcionales
        $direccion = !empty($this->direccion) ? htmlspecialchars(strip_tags($this->direccion)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $correo = !empty($this->correo) ? htmlspecialchars(strip_tags($this->correo)) : null;
        
        $stmt->bindParam(':nombre_cliente', $this->nombre_cliente);
        $stmt->bindParam(':tipo_cliente', $this->tipo_cliente);
        $stmt->bindParam(':nit', $this->nit);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        $errorInfo = $stmt->errorInfo();
        error_log("[DB_FIX] Error en crear Cliente: " . print_r($errorInfo, true));
        return false;
    }

    public function buscar($termino) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE (nombre_cliente LIKE :termino OR nit LIKE :termino) 
                  ORDER BY nombre_cliente ASC";
        
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();
        
        return $stmt;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  ORDER BY nombre_cliente ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id_cliente = $row['id_cliente'];
            $this->nombre_cliente = $row['nombre_cliente'];
            $this->tipo_cliente = $row['tipo_cliente'];
            $this->nit = $row['nit'];
            $this->direccion = $row['direccion'];
            $this->telefono = $row['telefono'];
            $this->correo = $row['correo'];
            return $row; // Retorna el array con los datos
        }
        return false;
    }

    public function contarTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function actualizar() {
        // Primero verificar si el NIT ya existe en otro cliente
        $query_check = "SELECT id_cliente FROM " . $this->table_name . " WHERE nit = :nit AND id_cliente != :id_cliente";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':nit', $this->nit);
        $stmt_check->bindParam(':id_cliente', $this->id_cliente);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() > 0) {
            throw new Exception("El NIT ya existe en otro cliente");
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET nombre_cliente = :nombre_cliente, 
                      tipo_cliente = :tipo_cliente, 
                      nit = :nit, 
                      direccion = :direccion, 
                      telefono = :telefono, 
                      correo = :correo
                  WHERE id_cliente = :id_cliente";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre_cliente = htmlspecialchars(strip_tags($this->nombre_cliente));
        $this->tipo_cliente = htmlspecialchars(strip_tags($this->tipo_cliente));
        $this->nit = htmlspecialchars(strip_tags($this->nit));
        
        // Manejar valores NULL para campos opcionales
        $direccion = !empty($this->direccion) ? htmlspecialchars(strip_tags($this->direccion)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $correo = !empty($this->correo) ? htmlspecialchars(strip_tags($this->correo)) : null;
        
        error_log("[DB_FIX] Actualizando cliente - ID: $this->id_cliente, Nombre: $this->nombre_cliente, NIT: $this->nit");
        
        $stmt->bindParam(':nombre_cliente', $this->nombre_cliente);
        $stmt->bindParam(':tipo_cliente', $this->tipo_cliente);
        $stmt->bindParam(':nit', $this->nit);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':id_cliente', $this->id_cliente);
        
        if ($stmt->execute()) {
            return true;
        }
        
        $errorInfo = $stmt->errorInfo();
        error_log("[DB_FIX] Error en actualizar Cliente: " . print_r($errorInfo, true));
        return false;
    }

    // Método adicional: eliminar cliente
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_cliente = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_cliente);
        
        if($stmt->execute()) {
            return true;
        }
        
        $errorInfo = $stmt->errorInfo();
        error_log("[DB_FIX] Error en eliminar Cliente: " . print_r($errorInfo, true));
        return false;
    }

    // Método adicional: verificar si existe el cliente por NIT (excluyendo un ID específico)
    public function existePorNit($nit, $excluir_id = null) {
        $query = "SELECT id_cliente FROM " . $this->table_name . " WHERE nit = :nit";
        
        if ($excluir_id) {
            $query .= " AND id_cliente != :excluir_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nit", $nit);
        
        if ($excluir_id) {
            $stmt->bindParam(":excluir_id", $excluir_id);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Método adicional: obtener cliente por NIT
    public function obtenerPorNit($nit) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nit = :nit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nit", $nit);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>