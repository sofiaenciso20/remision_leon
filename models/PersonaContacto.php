<?php
// models/PersonaContacto.php
require_once __DIR__ . '/../config/database.php';

class PersonaContacto {
    private $conn;
    private $table_name = "personas_contacto";

    public $id_persona;
    public $nombre_persona;
    public $cargo;
    public $telefono;
    public $correo;
    public $id_cliente;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las personas de contacto
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_persona";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Crear nueva persona de contacto
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre_persona=:nombre_persona, cargo=:cargo, 
                      telefono=:telefono, correo=:correo, id_cliente=:id_cliente";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre_persona = htmlspecialchars(strip_tags($this->nombre_persona));
        
        // Manejar valores NULL para campos opcionales
        $cargo = !empty($this->cargo) ? htmlspecialchars(strip_tags($this->cargo)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $correo = !empty($this->correo) ? htmlspecialchars(strip_tags($this->correo)) : null;
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        
        // Vincular valores
        $stmt->bindParam(":nombre_persona", $this->nombre_persona);
        $stmt->bindParam(":cargo", $cargo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        // Log del error si hay problemas
        $errorInfo = $stmt->errorInfo();
        error_log("[DB_FIX] Error en crear PersonaContacto: " . print_r($errorInfo, true));
        return false;
    }

    // Obtener personas de contacto por cliente (para usar en remisiones)
    public function obtenerPorCliente($id_cliente) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_cliente = :id_cliente 
                  ORDER BY nombre_persona ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener persona de contacto por ID y cargar en el objeto
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_persona = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id_persona = $row['id_persona'];
            $this->nombre_persona = $row['nombre_persona'];
            $this->cargo = $row['cargo'];
            $this->telefono = $row['telefono'];
            $this->correo = $row['correo'];
            $this->id_cliente = $row['id_cliente'];
            return true;
        }
        return false;
    }

    // Obtener persona de contacto por ID y retornar como array
    public function obtenerPorIdArray($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_persona = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre_persona = :nombre_persona, 
                      cargo = :cargo, 
                      telefono = :telefono, 
                      correo = :correo, 
                      id_cliente = :id_cliente 
                  WHERE id_persona = :id_persona";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre_persona = htmlspecialchars(strip_tags($this->nombre_persona));
        
        // Manejar valores NULL para campos opcionales
        $cargo = !empty($this->cargo) ? htmlspecialchars(strip_tags($this->cargo)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $correo = !empty($this->correo) ? htmlspecialchars(strip_tags($this->correo)) : null;
        
        error_log("[DB_FIX] Actualizando persona - ID: $this->id_persona, Nombre: $this->nombre_persona, Cliente: $this->id_cliente");
        
        // Vincular valores
        $stmt->bindParam(":nombre_persona", $this->nombre_persona);
        $stmt->bindParam(":cargo", $cargo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":id_persona", $this->id_persona);
        
        // Ejecutar y verificar
        if ($stmt->execute()) {
            return true;
        }
        
        $errorInfo = $stmt->errorInfo();
        error_log("[DB_FIX] Error en actualizar PersonaContacto: " . print_r($errorInfo, true));
        return false;
    }

    // Método adicional: eliminar persona de contacto
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_persona = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_persona);
        
        if($stmt->execute()) {
            return true;
        }
        
        // Log del error si hay problemas
        $errorInfo = $stmt->errorInfo();
        error_log("[DB_FIX] Error en eliminar PersonaContacto: " . print_r($errorInfo, true));
        return false;
    }

    // Método adicional: verificar si existe la persona de contacto
    public function existe($id) {
        $query = "SELECT id_persona FROM " . $this->table_name . " WHERE id_persona = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Método adicional: obtener personas con información del cliente
    public function obtenerTodosConCliente() {
        $query = "SELECT pc.*, c.nombre_cliente 
                  FROM " . $this->table_name . " pc 
                  LEFT JOIN clientes c ON pc.id_cliente = c.id_cliente 
                  ORDER BY pc.nombre_persona";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método adicional: verificar si existe persona con el mismo nombre en el mismo cliente (excluyendo un ID)
    public function existePorNombreCliente($nombre_persona, $id_cliente, $excluir_id = null) {
        $query = "SELECT id_persona FROM " . $this->table_name . " 
                  WHERE nombre_persona = :nombre_persona AND id_cliente = :id_cliente";
        
        if ($excluir_id) {
            $query .= " AND id_persona != :excluir_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre_persona", $nombre_persona);
        $stmt->bindParam(":id_cliente", $id_cliente);
        
        if ($excluir_id) {
            $stmt->bindParam(":excluir_id", $excluir_id);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Método adicional: contar total de personas de contacto
    public function contarTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Método adicional: obtener personas por cliente con información básica
    public function obtenerBasicoPorCliente($id_cliente) {
        $query = "SELECT id_persona, nombre_persona, cargo 
                  FROM " . $this->table_name . " 
                  WHERE id_cliente = :id_cliente 
                  ORDER BY nombre_persona ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // Método adicional: obtener personas por cliente y tipo
    public function obtenerPorClienteYTipo($id_cliente, $tipo_persona = 'contacto') {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_cliente = :id_cliente AND tipo_persona = :tipo_persona
                  ORDER BY nombre_persona ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->bindParam(":tipo_persona", $tipo_persona);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método adicional: crear persona con tipo específico
    public function crearConTipo($tipo_persona = 'contacto') {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre_persona=:nombre_persona, cargo=:cargo, 
                      telefono=:telefono, correo=:correo, id_cliente=:id_cliente,
                      tipo_persona=:tipo_persona";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre_persona = htmlspecialchars(strip_tags($this->nombre_persona));
        
        // Manejar valores NULL para campos opcionales
        $cargo = !empty($this->cargo) ? htmlspecialchars(strip_tags($this->cargo)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $correo = !empty($this->correo) ? htmlspecialchars(strip_tags($this->correo)) : null;
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        
        // Vincular valores
        $stmt->bindParam(":nombre_persona", $this->nombre_persona);
        $stmt->bindParam(":cargo", $cargo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":tipo_persona", $tipo_persona);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        // Log del error si hay problemas
        $errorInfo = $stmt->errorInfo();
        error_log("[DB_FIX] Error en crear PersonaContacto con tipo: " . print_r($errorInfo, true));
        return false;
    }
}
?>