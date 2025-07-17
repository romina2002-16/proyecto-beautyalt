​<?php
$mysqli = new mysqli("127.0.0.1", "root", "BeautyRoot123!", "beautyalt");

if ($mysqli->connect_error) {
    die("❌ Falló la conexión: " . $mysqli->connect_error);
}

echo "✅ ¡Conexión a la base de datos exitosa!";
?>

