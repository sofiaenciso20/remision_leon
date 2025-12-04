<?php
// ajax/editar_persona_contacto.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PersonaContacto.php';

header('Content-Type: application/json; charset=utf-8');

$database = new Database();
$db = $database->getConnection();
$personaContacto = new PersonaContacto($db);

try {
    error_log("[DB_FIX] Iniciando edición de persona de contacto...");
    error_log("[DB_FIX] POST data: " . print_r($_POST, true));
    
    // Validar que todos los campos requeridos estén presentes
    $required_fields = ['id_persona', 'nombre_persona', 'id_cliente'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("El campo $field es obligatorio");
        }
    }
    
    // Obtener los datos del formulario
    $id_persona = intval($_POST['id_persona']);
    $nombre_persona = trim($_POST['nombre_persona']);
    $cargo = isset($_POST['cargo']) ? trim($_POST['cargo']) : null;
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $id_cliente = intval($_POST['id_cliente']);

    // Validaciones básicas
    if ($id_persona <= 0) {
        throw new Exception('ID de persona de contacto no válido');
    }

    if (empty($nombre_persona)) {
        throw new Exception('El nombre de la persona es obligatorio');
    }

    if ($id_cliente <= 0) {
        throw new Exception('Debe seleccionar un cliente válido');
    }

    // Validar formato de email si se proporciona
    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del correo electrónico no es válido');
    }

    // Verificar que la persona existe
    $persona_existente = $personaContacto->obtenerPorId($id_persona);
    if (!$persona_existente) {
        throw new Exception('La persona de contacto no existe en la base de datos');
    }

    // Asignar los datos al modelo
    $personaContacto->id_persona = $id_persona;
    $personaContacto->nombre_persona = $nombre_persona;
    $personaContacto->cargo = $cargo;
    $personaContacto->telefono = $telefono;
    $personaContacto->correo = $correo;
    $personaContacto->id_cliente = $id_cliente;

    error_log("[DB_FIX] Datos asignados al modelo:");
    error_log("[DB_FIX] - ID: $personaContacto->id_persona");
    error_log("[DB_FIX] - Nombre: $personaContacto->nombre_persona");
    error_log("[DB_FIX] - Cargo: " . ($personaContacto->cargo ?: 'NULL'));
    error_log("[DB_FIX] - Teléfono: " . ($personaContacto->telefono ?: 'NULL'));
    error_log("[DB_FIX] - Correo: " . ($personaContacto->correo ?: 'NULL'));
    error_log("[DB_FIX] - Cliente ID: $personaContacto->id_cliente");

    error_log("[DB_FIX] Intentando actualizar persona ID: $id_persona");

    // Intentar actualizar la persona de contacto
    if ($personaContacto->actualizar()) {
        error_log("[DB_FIX] Actualización exitosa para persona ID: $id_persona");
        echo json_encode([
            'success' => true,
            'message' => 'Persona de contacto actualizada correctamente.'
        ]);
    } else {
        error_log("[DB_FIX] Error: actualizar() retornó false para persona ID: $id_persona");
        throw new Exception('No se pudo actualizar la persona de contacto en la base de datos. Verifique que los datos sean correctos.');
    }
} catch (Exception $e) {
    error_log("[DB_FIX] Error en editar_persona_contacto.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>