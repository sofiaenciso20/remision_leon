<?php
// ajax/buscar_personas_contacto.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $personaContacto = new PersonaContacto($db);
    
    $termino = $_GET['termino'] ?? '';
    
    $query = "SELECT id_persona, nombre_persona, cargo, telefono 
              FROM personas_contacto 
              WHERE nombre_persona LIKE :termino 
              OR cargo LIKE :termino 
              OR telefono LIKE :termino
              ORDER BY nombre_persona ASC 
              LIMIT 20";
    
    $stmt = $db->prepare($query);
    $termino_busqueda = "%{$termino}%";
    $stmt->bindParam(':termino', $termino_busqueda);
    $stmt->execute();
    
    $personas = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $texto = $row['nombre_persona'];
        if (!empty($row['cargo'])) {
            $texto .= ' - ' . $row['cargo'];
        }
        if (!empty($row['telefono'])) {
            $texto .= ' (' . $row['telefono'] . ')';
        }
        
        $personas[] = [
            'id' => $row['id_persona'],
            'text' => $texto
        ];
    }
    
    echo json_encode($personas);
    
} catch (Exception $e) {
    error_log("Error en buscar_personas_contacto.php: " . $e->getMessage());
    echo json_encode([]);
}
?>
