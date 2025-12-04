<?php
// ajax/buscar_clientes.php
// Endpoint para Select2: devuelve clientes en formato [{id,text, nit, ...}]

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

header('Content-Type: application/json; charset=utf-8');

// Permitir solo GET (Select2 hace GET por defecto) pero aceptar POST si se configuró así
$metodo = $_SERVER['REQUEST_METHOD'];
if (!in_array($metodo, ['GET','POST'])) {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$termino = '';
if ($metodo === 'GET') {
    $termino = isset($_GET['termino']) ? trim($_GET['termino']) : '';
} else { // POST
    $termino = isset($_POST['termino']) ? trim($_POST['termino']) : '';
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $clienteModel = new Cliente($db);

    // Si no hay término, traer un máximo (por performance) y permitir lazy search
    if ($termino === '') {
        $stmt = $db->prepare("SELECT id_cliente, nombre_cliente, nit FROM clientes ORDER BY nombre_cliente ASC LIMIT 30");
        $stmt->execute();
    } else {
        $stmt = $db->prepare("SELECT id_cliente, nombre_cliente, nit FROM clientes WHERE nombre_cliente LIKE :t OR nit LIKE :t ORDER BY nombre_cliente ASC LIMIT 30");
        $like = "%{$termino}%";
        $stmt->bindParam(':t', $like, PDO::PARAM_STR);
        $stmt->execute();
    }

    $results = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $results[] = [
            'id' => $row['id_cliente'],
            'text' => $row['nombre_cliente'] . (empty($row['nit']) ? '' : ' (' . $row['nit'] . ')'),
            'nit' => $row['nit'],
            'nombre' => $row['nombre_cliente']
        ];
    }

    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno', 'detalle' => $e->getMessage()]);
}
exit;
?>