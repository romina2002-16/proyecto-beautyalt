<?php
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "❌ Error: ID no válido.";
    exit;
}

$id = intval($_POST['id']);
$nombre = $_POST['nombre_cliente'];
$correo = $_POST['correo_cliente'];
$telefono = $_POST['telefono_cliente'];
$direccion = $_POST['direccion_cliente'];
$referencia = $_POST['referencia'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$servicio = $_POST['servicio'];
$monto = $_POST['monto'];
$estado = $_POST['estado_pago'];

$sql = "UPDATE citas SET nombre_cliente=?, correo_cliente=?, telefono_cliente=?, direccion_cliente=?, referencia=?, fecha=?, hora=?, servicio=?, monto=?, estado_pago=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssdsi", $nombre, $correo, $telefono, $direccion, $referencia, $fecha, $hora, $servicio, $monto, $estado, $id);

if ($stmt->execute()) {
    echo "✅ Cita actualizada correctamente.";
} else {
    echo "❌ Error al actualizar la cita: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
