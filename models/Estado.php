<?php
/// models/Estado.php
require_once __DIR__ . '/../config/database.php';

class Estado {
    private $conn;
    private $table_name = "estados";

    public $id_estado;
    public $nombre_estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_estado ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_estado = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id_estado = $row['id_estado'];
            $this->nombre_estado = $row['nombre_estado'];
            return true;
        }
        return false;
    }
}
?>
