<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = htmlspecialchars($_POST["correo"]);
    $clave = $_POST["clave"];

    // Conexión segura a la base de datos
   $conn = new mysqli("beautyait-mysql", "root", "BeautyRoot123!", "beautyalt");

    if ($conn->connect_error) {
        die("<h3>Error de conexión</h3><p>No te preocupes, a veces la energía se pausa. Intenta más tarde.</p>");
    }

    // Buscar el correo
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña
        if (password_verify($clave, $usuario["contrasena"])) {
            // Guardar sesión
            $_SESSION['correo'] = $correo;
            $_SESSION['nombre'] = $usuario['nombre'];

            // Redirigir al dashboard real
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<h3>Ups... la contraseña no coincide</h3>";
            echo "<p>No pasa nada, todas olvidamos cosas. <a href='recuperar_contrasena.html'>Recupérala aquí</a> y sigue avanzando.</p>";
        }
    } else {
        echo "<h3>Correo no encontrado</h3>";
        echo "<p>¿Tal vez aún no te has registrado? <a href='registro.html'>Hazlo aquí</a> y empieza tu historia con nosotras.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Acceso no permitido. Este lugar es solo para mujeres listas para brillar.</p>";
}
?>
