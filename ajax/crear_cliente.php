<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception('Error de conexión a la base de datos');
        }
        
        $cliente = new Cliente($db);
        
        $cliente->nombre_cliente = $_POST['nombre_cliente'];
        $cliente->tipo_cliente = $_POST['tipo_cliente'];
        $cliente->nit = $_POST['nit'];
        $cliente->direccion = $_POST['direccion'] ?? null;
        $cliente->telefono = $_POST['telefono'] ?? null;
        $cliente->correo = $_POST['correo'] ?? null;
        
        $id_cliente = $cliente->crear();
        
        if ($id_cliente) {
            $clienteCreado = $cliente->obtenerPorId($id_cliente);
            echo json_encode([
                'success' => true,
                'message' => 'Cliente creado correctamente',
                'cliente' => $clienteCreado
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear el cliente'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
?>
