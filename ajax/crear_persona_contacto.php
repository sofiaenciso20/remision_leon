<?php
require_once __DIR__ . '/../config/database.php';
header("Content-Type: application/json");

$db = (new Database())->getConnection();

try {
    $nombre = trim($_POST["nombre_persona"]);
    $cargo = trim($_POST["cargo"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $id_cliente = intval($_POST["id_cliente"]);

    if ($id_cliente <= 0) {
        throw new Exception("Cliente inválido");
    }

    $sql = "INSERT INTO personas_contacto(nombre_persona, cargo, telefono, correo, id_cliente)
            VALUES(:nombre, :cargo, :telefono, :correo, :id_cliente)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':cargo' => $cargo,
        ':telefono' => $telefono,
        ':correo' => $correo,
        ':id_cliente' => $id_cliente
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Persona de contacto creada correctamente"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
