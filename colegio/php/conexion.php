<?php
// php/conexion.php
// ── Configura aquí tus datos de MySQL ──
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // cambia por tu usuario
define('DB_PASS', '');            // cambia por tu contraseña
define('DB_NAME', 'colegio_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die("❌ Error de conexión a MySQL: " . $conn->connect_error);
}
?>
