<?php
// models/Remision.php
require_once __DIR__ . '/../config/database.php';

class Remision {
    private $conn;
    private $table_name = "remisiones";

    // ... (existing properties)

    public function __construct($db) {
        $this->conn = $db;
    }

    private function construirCondiciones($termino, $fecha_creacion, $id_cliente, $id_persona) {
        $condiciones = " FROM " . $this->table_name . " r
                        LEFT JOIN clientes c ON r.id_cliente = c.id_cliente
                        LEFT JOIN personas_contacto pc ON r.id_persona = pc.id_persona
                        WHERE 1=1";
        $params = [];

        if (!empty($termino)) {
            $condiciones .= " AND (r.numero_remision LIKE :termino OR c.nombre_cliente LIKE :termino OR c.nit LIKE :termino)";
            $params[':termino'] = '%' . $termino . '%';
        }
        if (!empty($fecha_creacion)) {
            $condiciones .= " AND DATE(r.fecha_emision) = :fecha_creacion";
            $params[':fecha_creacion'] = $fecha_creacion;
        }
        if (!empty($id_cliente)) {
            $condiciones .= " AND r.id_cliente = :id_cliente";
            $params[':id_cliente'] = $id_cliente;
        }
        if (!empty($id_persona)) {
            $condiciones .= " AND r.id_persona = :id_persona";
            $params[':id_persona'] = $id_persona;
        }

        return ['condiciones' => $condiciones, 'params' => $params];
    }

    public function contarRemisiones($termino = '', $fecha_creacion = '', $id_cliente = '', $id_persona = '') {
        $filtro = $this->construirCondiciones($termino, $fecha_creacion, $id_cliente, $id_persona);
        $query = "SELECT COUNT(r.id_remision) as total " . $filtro['condiciones'];

        $stmt = $this->conn->prepare($query);
        $stmt->execute($filtro['params']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$row['total'];
    }

    public function obtenerRemisionesPaginadas($termino = '', $fecha_creacion = '', $offset = 0, $limit = 10, $id_cliente = '', $id_persona = '') {
        $filtro = $this->construirCondiciones($termino, $fecha_creacion, $id_cliente, $id_persona);

        $query = "SELECT r.id_remision, r.numero_remision, r.fecha_emision, c.nombre_cliente, c.nit, pc.nombre_persona, pc.telefono AS telefono_persona" .
                 $filtro['condiciones'] .
                 " ORDER BY r.id_remision DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        foreach ($filtro['params'] as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ... (all other existing methods like generarNumeroRemision, crear, obtenerPorIdConResponsable, etc., remain unchanged)
    public function generarNumeroRemision() {
        $query = "SELECT MAX(numero_remision) as ultimo_numero FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $ultimo_numero = $row['ultimo_numero'] ? $row['ultimo_numero'] : 0;
        return $ultimo_numero + 1;
    }

    public function crear() {
        if (empty($this->numero_remision)) {
            $this->numero_remision = $this->generarNumeroRemision();
        }
        $query = "INSERT INTO " . $this->table_name . " SET numero_remision=:numero_remision, tipo_remision=:tipo_remision, fecha_emision=:fecha_emision, id_cliente=:id_cliente, id_persona=:id_persona, id_responsable=:id_responsable, id_usuario=:id_usuario, observaciones=:observaciones, id_estado=:id_estado";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":numero_remision", $this->numero_remision);
        $stmt->bindParam(":tipo_remision", $this->tipo_remision);
        $stmt->bindParam(":fecha_emision", $this->fecha_emision);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":id_persona", $this->id_persona);
        $stmt->bindParam(":id_responsable", $this->id_responsable);
        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":observaciones", $this->observaciones);
        $stmt->bindParam(":id_estado", $this->id_estado);
        if ($stmt->execute()) {
            $this->id_remision = $this->conn->lastInsertId();
            return $this->id_remision;
        }
        return false;
    }

    public function lastInsertId(){
        return $this->id_remision;
    }

   public function obtenerPorIdConResponsable($id) {
    $query = "SELECT r.*, c.nombre_cliente, c.nit, c.direccion, c.telefono, pc.nombre_persona, pc.telefono AS telefono_persona, pc.cargo AS cargo_persona, pr.nombre_responsable AS nombre_responsable, pr.telefono AS telefono_responsable, pr.correo AS correo_responsable, e.nombre_estado
              FROM " . $this->table_name . " r
              LEFT JOIN clientes c ON r.id_cliente = c.id_cliente
              LEFT JOIN personas_contacto pc ON r.id_persona = pc.id_persona
              LEFT JOIN personas_responsables pr ON r.id_responsable = pr.id_responsable
              LEFT JOIN estados e ON r.id_estado = e.id_estado
              WHERE r.id_remision = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT r.*, c.nombre_cliente, c.nit, c.direccion, c.telefono, pc.nombre_persona, pc.telefono AS telefono_persona, pc.cargo AS cargo_persona, e.nombre_estado
                  FROM " . $this->table_name . " r
                  LEFT JOIN clientes c ON r.id_cliente = c.id_cliente
                  LEFT JOIN personas_contacto pc ON r.id_persona = pc.id_persona
                  LEFT JOIN estados e ON r.id_estado = e.id_estado
                  WHERE r.id_remision = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCompletaPorId($id) {
        $remision = $this->obtenerPorIdConResponsable($id);
        if ($remision) {
            $query_items = "SELECT ri.*, p.nombre_producto
                            FROM items_remisionados ri
                            LEFT JOIN productos p ON ri.id_producto = p.id_producto
                            WHERE ri.id_remision = :id_remision";
            $stmt_items = $this->conn->prepare($query_items);
            $stmt_items->bindParam(":id_remision", $id);
            $stmt_items->execute();
            $remision['items'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
        }
        return $remision;
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                  SET fecha_emision=:fecha_emision,
                      tipo_remision=:tipo_remision,
                      id_cliente=:id_cliente,
                      id_persona=:id_persona,
                      id_responsable=:id_responsable,
                      observaciones=:observaciones
                  WHERE id_remision = :id_remision";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":fecha_emision", $this->fecha_emision);
        $stmt->bindParam(":tipo_remision", $this->tipo_remision);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":id_persona", $this->id_persona);
        $stmt->bindParam(":id_responsable", $this->id_responsable);
        $stmt->bindParam(":observaciones", $this->observaciones);
        $stmt->bindParam(":id_remision", $this->id_remision);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>