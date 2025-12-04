<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$db = (new Database())->getConnection();
$id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : null;

try {
    if ($id_cliente > 0) {
        $sql = "SELECT id_persona, nombre_persona, cargo, telefono, correo
                FROM personas_contacto
                WHERE id_cliente = :id_cliente
                ORDER BY nombre_persona ASC";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $rows = [];
    }

    echo json_encode($rows);
} catch (PDOException $e) {
    error_log("ERROR obtener_personas_contacto: " . $e->getMessage());
    echo json_encode([]);
}
?>
