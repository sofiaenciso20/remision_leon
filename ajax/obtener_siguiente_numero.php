<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Remision.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    $remision = new Remision($db);
    $siguiente_numero = $remision->generarNumeroRemision();
    
    echo json_encode([
        'success' => true,
        'siguiente_numero' => $siguiente_numero,
        'message' => 'Número obtenido correctamente'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'siguiente_numero' => 1,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
