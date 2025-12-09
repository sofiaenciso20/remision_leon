<?php
// models/PersonaResponsable.php
require_once __DIR__ . '/../config/database.php';

class PersonaResponsable {
    private $conn;
    private $table_name = "personas_responsables";

    public $id_responsable;
    public $nombre_responsable;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crea una nueva persona responsable.
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre_responsable = :nombre_responsable";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_responsable = htmlspecialchars(strip_tags($this->nombre_responsable));

        // Vincular datos
        $stmt->bindParam(":nombre_responsable", $this->nombre_responsable);

        if ($stmt->execute()) {
            $this->id_responsable = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Obtiene una persona responsable por su ID.
     */
    public function obtenerPorId() {
        $query = "SELECT id_responsable, nombre_responsable FROM " . $this->table_name . " WHERE id_responsable = :id_responsable LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_responsable", $this->id_responsable);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre_responsable = $row['nombre_responsable'];
            return $row;
        }
        return null;
    }

    /**
     * Actualiza una persona responsable existente.
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " SET nombre_responsable = :nombre_responsable WHERE id_responsable = :id_responsable";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre_responsable = htmlspecialchars(strip_tags($this->nombre_responsable));
        $this->id_responsable = htmlspecialchars(strip_tags($this->id_responsable));

        // Vincular datos
        $stmt->bindParam(":nombre_responsable", $this->nombre_responsable);
        $stmt->bindParam(":id_responsable", $this->id_responsable);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Cuenta el número total de personas responsables, con filtro de búsqueda.
     */
    public function contarPersonasResponsables($busqueda = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $params = [];

        if (!empty($busqueda)) {
            $query .= " WHERE nombre_responsable LIKE :busqueda";
            $params[':busqueda'] = "%" . htmlspecialchars(strip_tags($busqueda)) . "%";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    }

    /**
     * Obtiene una lista paginada de personas responsables, con filtro de búsqueda.
     */
    public function obtenerPaginados($inicio, $filas_por_pagina, $busqueda = '') {
        $query = "SELECT id_responsable, nombre_responsable FROM " . $this->table_name;
        $params = [];

        if (!empty($busqueda)) {
            $query .= " WHERE nombre_responsable LIKE :busqueda";
            $params[':busqueda'] = "%" . htmlspecialchars(strip_tags($busqueda)) . "%";
        }

        $query .= " ORDER BY nombre_responsable ASC LIMIT :inicio, :filas_por_pagina";

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros de búsqueda
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        // Vincular parámetros de paginación
        $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindParam(':filas_por_pagina', $filas_por_pagina, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    /**
     * Obtiene todas las personas responsables.
     */
    public function obtenerTodos() {
        $query = "SELECT id_responsable, nombre_responsable FROM " . $this->table_name . " ORDER BY nombre_responsable ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
