<?php
// models/ItemRemisionado.php
require_once __DIR__ . '/../config/database.php';

class ItemRemisionado {
    private $conn;
    private $table_name = "items_remisionados";

    public $id_item;
    public $id_remision;
    public $id_producto;
    public $descripcion;
    public $cantidad;
    public $valor_unitario;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_remision = :id_remision, 
                      id_producto = :id_producto, 
                      descripcion = :descripcion, 
                      cantidad = :cantidad, 
                      valor_unitario = :valor_unitario";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id_remision", $this->id_remision);
        $stmt->bindParam(":id_producto", $this->id_producto);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":valor_unitario", $this->valor_unitario);
        
        if ($stmt->execute()) {
            $this->id_item = $this->conn->lastInsertId();
            return true;
        }
        
        error_log("Error al crear item remisionado: " . print_r($stmt->errorInfo(), true));
        return false;
    }

    public function crearItems($id_remision, $items) {
        try {
            $this->conn->beginTransaction();
            $items_procesados = 0;
            
            foreach ($items as $item) {
                // Validar que el item tenga los datos mínimos
                if (empty($item['descripcion']) || empty($item['cantidad'])) {
                    error_log("Item inválido, saltando: " . print_r($item, true));
                    continue;
                }

                $query = "INSERT INTO " . $this->table_name . " 
                         (id_remision, id_producto, descripcion, cantidad, valor_unitario) 
                         VALUES (:id_remision, :id_producto, :descripcion, :cantidad, :valor_unitario)";
                
                $stmt = $this->conn->prepare($query);
                
                $id_producto = !empty($item['id_producto']) ? $item['id_producto'] : null;
                $descripcion = $item['descripcion'];
                $cantidad = (int) $item['cantidad'];
                $valor_unitario = !empty($item['valor_unitario']) ? (float) $item['valor_unitario'] : 0.00;
                
                $stmt->bindParam(":id_remision", $id_remision);
                $stmt->bindParam(":id_producto", $id_producto);
                $stmt->bindParam(":descripcion", $descripcion);
                $stmt->bindParam(":cantidad", $cantidad);
                $stmt->bindParam(":valor_unitario", $valor_unitario);
                
                if ($stmt->execute()) {
                    $items_procesados++;
                } else {
                    error_log("Error al ejecutar insert del item: " . print_r($stmt->errorInfo(), true));
                    throw new Exception("Error al insertar item: " . implode(", ", $stmt->errorInfo()));
                }
            }
            
            if ($items_procesados === 0) {
                throw new Exception("No se pudo procesar ningún item");
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error en crearItems: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorRemision($id_remision) {
        try {
            $query = "SELECT ir.*, p.nombre_producto 
                     FROM " . $this->table_name . " ir
                     LEFT JOIN productos p ON ir.id_producto = p.id_producto
                     WHERE ir.id_remision = :id_remision 
                     ORDER BY ir.id_item ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_remision", $id_remision);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en obtenerPorRemision: " . $e->getMessage());
            return array();
        }
    }

    public function eliminarPorRemision($id_remision) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_remision = :id_remision";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_remision", $id_remision);
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Error en eliminarPorRemision: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarItem($id_item, $datos) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET id_producto = :id_producto, 
                         descripcion = :descripcion, 
                         cantidad = :cantidad, 
                         valor_unitario = :valor_unitario
                     WHERE id_item = :id_item";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":id_producto", $datos['id_producto']);
            $stmt->bindParam(":descripcion", $datos['descripcion']);
            $stmt->bindParam(":cantidad", $datos['cantidad']);
            $stmt->bindParam(":valor_unitario", $datos['valor_unitario']);
            $stmt->bindParam(":id_item", $id_item);
            
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Error en actualizarItem: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTotalPorRemision($id_remision) {
        try {
            $query = "SELECT SUM(cantidad * valor_unitario) as total 
                     FROM " . $this->table_name . " 
                     WHERE id_remision = :id_remision";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_remision", $id_remision);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ? (float) $result['total'] : 0.00;
            
        } catch (Exception $e) {
            error_log("Error en obtenerTotalPorRemision: " . $e->getMessage());
            return 0.00;
        }
    }

    public function contarItemsPorRemision($id_remision) {
        try {
            $query = "SELECT COUNT(*) as total_items 
                     FROM " . $this->table_name . " 
                     WHERE id_remision = :id_remision";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_remision", $id_remision);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_items'] ? (int) $result['total_items'] : 0;
            
        } catch (Exception $e) {
            error_log("Error en contarItemsPorRemision: " . $e->getMessage());
            return 0;
        }
    }
}
?>