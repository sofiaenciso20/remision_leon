<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

header("Content-Type: application/json");

$db = (new Database())->getConnection();
$personaContacto = new PersonaContacto($db);

try {
    // Asignar datos del POST al objeto
    $personaContacto->nombre_persona = trim($_POST["nombre_persona"]);
    $personaContacto->cargo = trim($_POST["cargo"]);
    $personaContacto->telefono = trim($_POST["telefono"]);
    $personaContacto->correo = trim($_POST["correo"]);
    $personaContacto->id_cliente = intval($_POST["id_cliente"]);

    if ($personaContacto->id_cliente <= 0) {
        throw new Exception("Cliente inválido");
    }

    // Crear la persona de contacto
    $nuevoId = $personaContacto->crear();

    if ($nuevoId) {
        // Obtener los datos completos de la persona recién creada
        $nuevaPersona = $personaContacto->obtenerPorId($nuevoId);

        echo json_encode([
            "success" => true,
            "message" => "Persona de contacto creada correctamente",
            "data" => $nuevaPersona // Enviar los datos de vuelta
        ]);
    } else {
        throw new Exception("No se pudo crear la persona de contacto.");
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
