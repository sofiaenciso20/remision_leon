<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaResponsable.php';

header("Content-Type: application/json");

$response = ['success' => false, 'message' => 'Error desconocido.'];

try {
    $db = (new Database())->getConnection();
    $persona = new PersonaResponsable($db);

    // --- Server-side validation ---
    if (empty(trim($_POST['nombre_responsable']))) {
        throw new Exception("El nombre de la persona responsable es obligatorio.");
    }
    if (!empty(trim($_POST['correo'])) && !filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El formato del correo electrónico no es válido.");
    }
    // Asignar datos del POST al objeto
    $persona->nombre_responsable = trim($_POST['nombre_responsable']);
    $persona->correo = trim($_POST['correo']) ?: null;
    $persona->telefono = trim($_POST['telefono']) ?: null;
    $persona->id_cliente = !empty($_POST['id_cliente']) ? intval($_POST['id_cliente']) : null;


    $new_id = $persona->crear();

    if ($new_id) {
        $response = [
            'success' => true,
            'message' => 'Persona responsable creada correctamente.',
            'id_responsable' => $new_id
        ];
    } else {
        throw new Exception("No se pudo crear la persona responsable.");
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

?>
