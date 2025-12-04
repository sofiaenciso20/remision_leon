<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$db = (new Database())->getConnection();

$id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
$termino = isset($_POST['termino']) ? "%".$_POST['termino']."%" : "%%";

try {
    $sql = "SELECT id_responsable AS id, nombre_responsable AS text
            FROM personas_responsables
            WHERE id_cliente = :id_cliente
            AND nombre_responsable LIKE :termino
            ORDER BY nombre_responsable ASC";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->bindValue(':termino', $termino);
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    error_log("ERROR buscar_persona_responsable: " . $e->getMessage());
    echo json_encode([]);
}
