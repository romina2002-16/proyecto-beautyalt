<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $correo = htmlspecialchars($_POST["correo"]);
    $clave = $_POST["clave"];
    $especialidad = $_POST["especialidad"];

    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Conectar a la base de datos
    $conn = new mysqli("127.0.0.1", "root", "BeautyRoot123!", "beautyalt");

    if ($conn->connect_error) {
        die("<h3>Error de conexión</h3><p>Tal vez el universo solo quiere que respires unos segundos antes de seguir.</p>");
    }

    // Verificar si ya existe el correo
    $verificar = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $verificar->bind_param("s", $correo);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        echo "<h3>Ya estás registrada, hermosa</h3>";
        echo "<p>Tu correo ya forma parte de esta historia. <a href='login.html'>Inicia sesión aquí</a> y sigue brillando.</p>";
    } else {
        // Insertar nuevo registro
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, especialidad) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $correo, $clave_hash, $especialidad);

        if ($stmt->execute()) {
            echo "<h2>Bienvenida a BeautyAlt, $nombre!</h2>";
            echo "<p>Has sido registrada con éxito. Ahora puedes iniciar sesión y comenzar tu historia.</p>";
            echo "<a href='login.html'>Iniciar sesión</a>";

            // Guardar en archivo (opcional)
            $registro = "Nombre: $nombre | Correo: $correo | Clave: $clave_hash\n";
            file_put_contents("registros.txt", $registro, FILE_APPEND);
        } else {
            echo "<h3>Error inesperado</h3><p>Algo no salió como esperábamos. Inténtalo de nuevo más tarde.</p>";
        }

        $stmt->close();
    }

    $verificar->close();
    $conn->close();
} else {
    echo "<p>Acceso no permitido. Esta página solo está disponible para quienes están listas para comenzar.</p>";
}
?>
