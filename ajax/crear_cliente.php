<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error desconocido.'];

try {
    $db = (new Database())->getConnection();
    $cliente = new Cliente($db);

    // --- Server-side validation ---
    if (empty(trim($_POST['nombre_cliente']))) {
        throw new Exception("El nombre del cliente es obligatorio.");
    }
    if (empty(trim($_POST['nit']))) {
        throw new Exception("El NIT o cédula es obligatorio.");
    }
    if (empty(trim($_POST['tipo_cliente']))) {
        throw new Exception("El tipo de cliente es obligatorio.");
    }
    if (!empty(trim($_POST['correo'])) && !filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El formato del correo electrónico no es válido.");
    }

    $cliente->nombre_cliente = trim($_POST['nombre_cliente']);
    $cliente->tipo_cliente = trim($_POST['tipo_cliente']);
    $cliente->nit = trim($_POST['nit']);
    $cliente->direccion = trim($_POST['direccion']) ?: null;
    $cliente->telefono = trim($_POST['telefono']) ?: null;
    $cliente->correo = trim($_POST['correo']) ?: null;

    $new_id = $cliente->crear();

    if ($new_id) {
        $cliente_creado = $cliente->obtenerPorId($new_id);
        $response = [
            'success' => true,
            'message' => 'Cliente creado correctamente.',
            'cliente' => $cliente_creado
        ];
    } else {
        throw new Exception("No se pudo crear el cliente. Verifique los datos.");
    }

} catch (Exception $e) {
    // Check for unique constraint violation (NIT)
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $response['message'] = 'Error: Ya existe un cliente con el mismo NIT/Cédula.';
    } else {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>
