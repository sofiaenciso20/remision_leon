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

    // Crear nueva persona de contacto
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre_persona=:nombre_persona, cargo=:cargo,
                      telefono=:telefono, correo=:correo, id_cliente=:id_cliente";

        $stmt = $this->conn->prepare($query);

        $this->nombre_persona = htmlspecialchars(strip_tags($this->nombre_persona));
        $cargo = !empty($this->cargo) ? htmlspecialchars(strip_tags($this->cargo)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $correo = !empty($this->correo) ? htmlspecialchars(strip_tags($this->correo)) : null;
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));

        $stmt->bindParam(":nombre_persona", $this->nombre_persona);
        $stmt->bindParam(":cargo", $cargo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":id_cliente", $this->id_cliente);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Obtener persona de contacto por ID con el nombre del cliente
    public function obtenerPorId($id) {
        $query = "SELECT pc.*, c.nombre_cliente
                  FROM " . $this->table_name . " pc
                  LEFT JOIN clientes c ON pc.id_cliente = c.id_cliente
                  WHERE pc.id_persona = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
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

        $this->nombre_persona = htmlspecialchars(strip_tags($this->nombre_persona));
        $cargo = !empty($this->cargo) ? htmlspecialchars(strip_tags($this->cargo)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $correo = !empty($this->correo) ? htmlspecialchars(strip_tags($this->correo)) : null;

        $stmt->bindParam(":nombre_persona", $this->nombre_persona);
        $stmt->bindParam(":cargo", $cargo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":id_persona", $this->id_persona);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function contarPersonasContacto($termino = '') {
        $query = "SELECT COUNT(pc.id_persona) as total
                  FROM " . $this->table_name . " pc
                  LEFT JOIN clientes c ON pc.id_cliente = c.id_cliente
                  WHERE 1=1";
        $params = [];

        if (!empty($termino)) {
            $query .= " AND (pc.nombre_persona LIKE :termino OR c.nombre_cliente LIKE :termino)";
            $params[':termino'] = '%' . $termino . '%';
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$row['total'];
    }

    public function obtenerPersonasContactoPaginadas($termino = '', $offset = 0, $limit = 10) {
        $query = "SELECT pc.*, c.nombre_cliente
                  FROM " . $this->table_name . " pc
                  LEFT JOIN clientes c ON pc.id_cliente = c.id_cliente
                  WHERE 1=1";
        $params = [];

        if (!empty($termino)) {
            $query .= " AND (pc.nombre_persona LIKE :termino OR c.nombre_cliente LIKE :termino)";
            $params[':termino'] = "%{$termino}%";
        }

        $query .= " ORDER BY pc.id_persona ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        if (!empty($termino)) {
            $stmt->bindValue(':termino', $params[':termino']);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
