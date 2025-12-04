<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

header('Content-Type: application/json');

if (isset($_POST['id_cliente'])) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception('Error de conexión a la base de datos');
        }
        
        $personaContacto = new PersonaContacto($db);
        $personas = $personaContacto->obtenerPorCliente($_POST['id_cliente']);
        
        // Retornar array directo como esperan los otros endpoints del proyecto
        echo json_encode($personas);
    } catch (Exception $e) {
        error_log("Error al obtener personas de contacto: " . $e->getMessage());
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
