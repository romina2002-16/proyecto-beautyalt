<?php
session_start();
$conn = new mysqli("localhost", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if (!isset($_SESSION['id'])) {
    die("Acceso denegado");
}

$id = $_SESSION['id'];
$usuario = $conn->query("SELECT * FROM usuarios WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $ubicacion = $_POST['ubicacion'];
    $telefono = $_POST['telefono'];
    $latitud = $_POST['latitud'] ?? null;
    $longitud = $_POST['longitud'] ?? null;
    $foto_perfil = $usuario['foto_perfil'];

    // Subida de imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $permitidos = ['image/jpeg', 'image/png'];
        if (in_array($_FILES['foto']['type'], $permitidos)) {
            $ext = $_FILES['foto']['type'] === 'image/png' ? 'png' : 'jpg';
            $nombreArchivo = "perfil_{$id}_" . time() . "." . $ext;
            $rutaAbsoluta = "/var/www/html/uploads/" . $nombreArchivo;
            $rutaParaBD = "uploads/" . $nombreArchivo;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaAbsoluta)) {
                $foto_perfil = $rutaParaBD;
            } else {
                echo "❌ Error al guardar imagen.";
                exit;
            }
        } else {
            echo "⚠️ Solo se permiten imágenes JPG o PNG.";
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, ubicacion=?, telefono=?, latitud=?, longitud=?, foto_perfil=? WHERE id=?");
    $stmt->bind_param("sssddsi", $nombre, $ubicacion, $telefono, $latitud, $longitud, $foto_perfil, $id);
    $stmt->execute();

    header("Location: perfil_profesional.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil | BeautyAlt</title>
    <style>
        body { background: #fdf3f5; font-family: 'Segoe UI', sans-serif; padding: 2rem; }
        .form-container {
            max-width: 600px;
            background: white;
            padding: 2rem;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.07);
        }
        h2 { text-align: center; color: #d66d7e; margin-bottom: 1rem; }
        input, label, button {
            width: 100%;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        input[type="text"], input[type="file"] {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background: #d66d7e;
            color: white;
            padding: 0.7rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        #map {
            height: 300px;
            width: 100%;
            margin-bottom: 1rem;
            border-radius: 10px;
        }
        .foto-preview {
            text-align: center;
            margin-bottom: 1rem;
        }
        .foto-preview img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f5b6c1;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Perfil</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="foto-preview">
                <img src="<?= htmlspecialchars($usuario['foto_perfil'] ?: 'assets/img/default_user.png') ?>" alt="Foto actual">
            </div>

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label>Ubicación:</label>
            <input type="text" name="ubicacion" id="autocomplete" value="<?= htmlspecialchars($usuario['ubicacion']) ?>" placeholder="Escribe tu dirección..." required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">

            <label>Foto de perfil (opcional):</label>
            <input type="file" name="foto" accept="image/*">

            <div id="map"></div>

            <!-- Campos ocultos para guardar lat/lon -->
            <input type="hidden" name="latitud" id="latitud" value="<?= htmlspecialchars($usuario['latitud'] ?? '') ?>">
            <input type="hidden" name="longitud" id="longitud" value="<?= htmlspecialchars($usuario['longitud'] ?? '') ?>">

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>

    <script>
      let map;
      let marker;

      function initMap() {
        const lat = parseFloat(document.getElementById("latitud").value) || 27.4762;
        const lng = parseFloat(document.getElementById("longitud").value) || -99.5164;

        map = new google.maps.Map(document.getElementById("map"), {
          center: { lat, lng },
          zoom: 13,
        });

        marker = new google.maps.Marker({
          position: { lat, lng },
          map,
          draggable: true,
        });

        const input = document.getElementById("autocomplete");
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo("bounds", map);

        autocomplete.addListener("place_changed", () => {
          const place = autocomplete.getPlace();
          if (!place.geometry || !place.geometry.location) return;

          const location = place.geometry.location;
          map.setCenter(location);
          marker.setPosition(location);

          document.getElementById("latitud").value = location.lat();
          document.getElementById("longitud").value = location.lng();
        });

        marker.addListener("dragend", () => {
          const pos = marker.getPosition();
          document.getElementById("latitud").value = pos.lat();
          document.getElementById("longitud").value = pos.lng();
        });
      }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&libraries=places&callback=initMap" async defer></script>
</body>
</html>
