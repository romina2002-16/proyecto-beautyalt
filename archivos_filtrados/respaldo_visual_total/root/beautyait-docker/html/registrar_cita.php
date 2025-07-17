<?php
// Conexión a la base de datos
$conn = new mysqli("127.0.0.1", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
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

// Insertar en la tabla citas
$sql = "INSERT INTO citas (nombre_cliente, correo_cliente, telefono_cliente, direccion_cliente, referencia, fecha, hora, servicio, monto, estado_pago, creada_en)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssds", $nombre, $correo, $telefono, $direccion, $referencia, $fecha, $hora, $servicio, $monto, $estado);

if ($stmt->execute()) {
    // ✅ Redirigir al dashboard profesional
    header("Location: dashboard.php");
    exit;
} else {
    echo "Error al registrar la cita: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
