<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$db = (new Database())->getConnection();

try {

    // Validación SOLO del nombre (cliente ya no es obligatorio)
    if (!isset($_POST['nombre']) || empty(trim($_POST['nombre']))) {
        throw new Exception("El nombre del responsable es obligatorio.");
    }

    // id_cliente puede venir o no
    $id_cliente = isset($_POST['id_cliente']) && $_POST['id_cliente'] !== "" 
                    ? intval($_POST['id_cliente']) 
                    : null;

    $nombre = trim($_POST['nombre']);
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;

    $sql = "INSERT INTO personas_responsables (id_cliente, nombre_responsable, correo, telefono)
            VALUES (:id_cliente, :nombre_responsable, :correo, :telefono)";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->bindValue(':nombre_responsable', $nombre);
    $stmt->bindValue(':correo', $correo);
    $stmt->bindValue(':telefono', $telefono);

    $stmt->execute();

    $id_nuevo = $db->lastInsertId();

    echo json_encode([
        'success' => true,
        'id' => $id_nuevo,
        'nombre_responsable' => $nombre
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
