<?php
// ajax/editar_cliente.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

try {
    error_log("[DB_FIX] Iniciando edición de cliente...");
    error_log("[DB_FIX] POST data: " . print_r($_POST, true));
    
    // Validar que todos los campos requeridos estén presentes
    $required_fields = ['id_cliente', 'nombre_cliente', 'tipo_cliente', 'nit'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("El campo $field es obligatorio");
        }
    }
    
    // Obtener los datos del formulario
    $id_cliente = intval($_POST['id_cliente']);
    $nombre_cliente = trim($_POST['nombre_cliente']);
    $tipo_cliente = trim($_POST['tipo_cliente']);
    $nit = trim($_POST['nit']);
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : null;
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;

    // Validaciones básicas
    if ($id_cliente <= 0) {
        throw new Exception('ID de cliente no válido');
    }

    if (empty($nombre_cliente)) {
        throw new Exception('El nombre del cliente es obligatorio');
    }

    if (empty($tipo_cliente)) {
        throw new Exception('El tipo de cliente es obligatorio');
    }

    if (empty($nit)) {
        throw new Exception('El NIT es obligatorio');
    }

    // Validar formato de email si se proporciona
    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del correo electrónico no es válido');
    }

    // Verificar que el cliente existe
    $cliente_existente = $cliente->obtenerPorId($id_cliente);
    if (!$cliente_existente) {
        throw new Exception('El cliente no existe en la base de datos');
    }

    // Asignar los datos al modelo
    $cliente->id_cliente = $id_cliente;
    $cliente->nombre_cliente = $nombre_cliente;
    $cliente->tipo_cliente = $tipo_cliente;
    $cliente->nit = $nit;
    $cliente->direccion = $direccion;
    $cliente->telefono = $telefono;
    $cliente->correo = $correo;

    error_log("[DB_FIX] Datos asignados al modelo:");
    error_log("[DB_FIX] - ID: $cliente->id_cliente");
    error_log("[DB_FIX] - Nombre: $cliente->nombre_cliente");
    error_log("[DB_FIX] - Tipo: $cliente->tipo_cliente");
    error_log("[DB_FIX] - NIT: $cliente->nit");
    error_log("[DB_FIX] - Dirección: " . ($cliente->direccion ?: 'NULL'));
    error_log("[DB_FIX] - Teléfono: " . ($cliente->telefono ?: 'NULL'));
    error_log("[DB_FIX] - Correo: " . ($cliente->correo ?: 'NULL'));

    error_log("[DB_FIX] Intentando actualizar cliente ID: $id_cliente");

    // Intentar actualizar el cliente
    if ($cliente->actualizar()) {
        error_log("[DB_FIX] Cliente actualizado exitosamente - ID: $id_cliente");
        echo json_encode([
            'success' => true,
            'message' => 'Cliente actualizado correctamente.'
        ]);
    } else {
        error_log("[DB_FIX] Error: actualizar() retornó false");
        throw new Exception('No se pudo actualizar el cliente en la base de datos. Verifique que los datos sean correctos.');
    }
} catch (Exception $e) {
    error_log("[DB_FIX] Error al editar cliente: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>