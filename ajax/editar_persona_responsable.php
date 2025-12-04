<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$db = (new Database())->getConnection();

$id = $_POST['id_responsable'] ?? null;
$nombre = $_POST['nombre_responsable'] ?? null;
$correo = $_POST['correo'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$cargo = $_POST['cargo'] ?? null;

if (!$id || !$nombre) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$sql = "UPDATE personas_responsables
        SET nombre_responsable = :nombre,
            correo = :correo,
            telefono = :telefono,
            cargo = :cargo
        WHERE id_responsable = :id";

$stmt = $db->prepare($sql);
$stmt->bindParam(':nombre', $nombre);
stmt->bindParam(':correo', $correo);
$stmt->bindParam(':telefono', $telefono);
$stmt->bindParam(':cargo', $cargo);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
