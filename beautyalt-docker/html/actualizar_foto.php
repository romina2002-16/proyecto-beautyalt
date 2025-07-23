<?php
session_start();
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if (!isset($_SESSION['id'])) {
    die("Acceso no autorizado.");
}

$id_usuario = $_SESSION['id'];

// Verificamos que haya un archivo subido sin errores
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tipo = $_FILES['foto']['type'];
    $permitidos = ['image/jpeg', 'image/png'];

    // Validamos tipo MIME
    if (in_array($tipo, $permitidos)) {
        // Obtenemos extensión segura
        $ext = $tipo === 'image/png' ? 'png' : 'jpg';

        // Definimos nombre y rutas
        $nombreArchivo = "perfil_{$id_usuario}_" . time() . "." . $ext;
        $rutaAbsoluta = "/var/www/html/uploads/" . $nombreArchivo;
        $rutaPublica = "uploads/" . $nombreArchivo;

        // Movemos archivo
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaAbsoluta)) {
            // Guardamos en la base de datos
            $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
            $stmt->bind_param("si", $rutaPublica, $id_usuario);
            $stmt->execute();

            // Redireccionamos
            header("Location: perfil_profesional.php?id=$id_usuario");
            exit;
        } else {
            echo "❌ Error al guardar la imagen. Revisa permisos de la carpeta /uploads.";
        }
    } else {
        echo "⚠️ Solo se permiten imágenes JPG o PNG.";
    }
} else {
    echo "📂 No se ha subido ninguna imagen válida.";
}
?>
