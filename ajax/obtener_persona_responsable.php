<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$db = (new Database())->getConnection();

$id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;

try {

    if ($id_cliente > 0) {

        // Si viene cliente → filtra por cliente
        $sql = "SELECT id_responsable AS id_persona, nombre_responsable AS nombre_persona 
                FROM personas_responsables 
                WHERE id_cliente = :id_cliente 
                ORDER BY nombre_responsable ASC";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

    } else {

        // Si NO viene cliente → trae TODOS los responsables
        $sql = "SELECT id_responsable AS id_persona, nombre_responsable AS nombre_persona 
                FROM personas_responsables 
                ORDER BY nombre_responsable ASC";

        $stmt = $db->query($sql);
    }

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    error_log("ERROR obtener_personas_responsable: " . $e->getMessage());
    echo json_encode([]);
}
