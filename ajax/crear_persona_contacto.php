<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

header("Content-Type: application/json");

$response = ['success' => false, 'message' => 'Error desconocido.'];

try {
    $db = (new Database())->getConnection();
    $personaContacto = new PersonaContacto($db);

    // --- Server-side validation ---
    if (empty(trim($_POST['nombre_persona']))) {
        throw new Exception("El nombre de la persona es obligatorio.");
    }
    if (empty($_POST['id_cliente']) || !is_numeric($_POST['id_cliente'])) {
        throw new Exception("El cliente asociado no es válido.");
    }
     if (!empty(trim($_POST['correo'])) && !filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El formato del correo electrónico no es válido.");
    }

    // Asignar datos del POST al objeto
    $personaContacto->nombre_persona = trim($_POST["nombre_persona"]);
    $personaContacto->cargo = trim($_POST["cargo"]) ?: null;
    $personaContacto->telefono = trim($_POST["telefono"]) ?: null;
    $personaContacto->correo = trim($_POST["correo"]) ?: null;
    $personaContacto->id_cliente = intval($_POST["id_cliente"]);

    $nuevoId = $personaContacto->crear();

    if ($nuevoId) {
        $nuevaPersona = $personaContacto->obtenerPorId($nuevoId);
        $response = [
            "success" => true,
            "message" => "Persona de contacto creada correctamente",
            "data" => $nuevaPersona
        ];
    } else {
        throw new Exception("No se pudo crear la persona de contacto.");
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
