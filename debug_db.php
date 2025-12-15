<?php
require_once __DIR__ . '/config/database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception('Error de conexión a la base de datos');
    }

    echo "<h1>Contenido de la tabla personas_contacto</h1>";
    $stmt = $db->query("SELECT * FROM personas_contacto");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
        echo "<table border='1'><tr><th>id_persona</th><th>nombre_persona</th><th>id_cliente</th></tr>";
        foreach ($results as $row) {
            echo "<tr><td>" . htmlspecialchars($row['id_persona']) . "</td><td>" . htmlspecialchars($row['nombre_persona']) . "</td><td>" . htmlspecialchars($row['id_cliente']) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>La tabla personas_contacto está vacía.</p>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
