<?php
// models/PersonaResponsable.php
require_once __DIR__ . '/../config/database.php';

class PersonaResponsable {
    private $conn;
    private $table_name = "personas_responsables";

    public $id_responsable;
    public $nombre_responsable;
    public $correo;
    public $telefono;
    public $id_cliente;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerPorCliente($id_cliente) {
        $query = "SELECT id_responsable, nombre_responsable, correo, telefono
                  FROM " . $this->table_name . "
                  WHERE id_cliente = :id_cliente
                  ORDER BY nombre_responsable ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_cliente", $id_cliente);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
