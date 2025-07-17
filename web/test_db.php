​<?php
$mysqli = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");

if ($mysqli->connect_error) {
    die("❌ Falló la conexión: " . $mysqli->connect_error);
}

echo "✅ ¡Conexión a la base de datos exitosa!";
?>

