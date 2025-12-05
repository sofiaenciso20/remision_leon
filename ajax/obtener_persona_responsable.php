<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

header('Content-Type: application/json');

if (isset($_POST['id_cliente'])) {
    try {
        $database = new Database();
        $db = $database->getConnection();

        if (!$db) {
            throw new Exception('Error de conexión a la base de datos');
        }

        $personaResponsable = new PersonaResponsable($db);
        $personas = $personaResponsable->obtenerPorCliente($_POST['id_cliente']);

        echo json_encode($personas);
    } catch (Exception $e) {
        error_log("Error al obtener personas responsables: " . $e->getMessage());
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
