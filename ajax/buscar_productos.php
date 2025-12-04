<?php
require_once '../config/database.php';
require_once '../models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);

$termino = isset($_GET['termino']) ? $_GET['termino'] : '';

$resultados = $producto->buscar($termino);

echo json_encode($resultados);
?>