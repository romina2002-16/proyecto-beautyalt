<?php
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// CREAR TABLA SI NO EXISTE
$conn->query("CREATE TABLE IF NOT EXISTS servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    duracion INT, -- en minutos
    costo DECIMAL(10,2),
    categoria VARCHAR(100),
    descripcion TEXT
)");

// AGREGAR SERVICIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $stmt = $conn->prepare("INSERT INTO servicios (nombre, duracion, costo, categoria, descripcion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sidss", $_POST['nombre'], $_POST['duracion'], $_POST['costo'], $_POST['categoria'], $_POST['descripcion']);
    $stmt->execute();
    header("Location: servicios.php");
    exit;
}

// ELIMINAR SERVICIO
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM servicios WHERE id = $id");
    header("Location: servicios.php");
    exit;
}

$servicios = $conn->query("SELECT * FROM servicios ORDER BY categoria, nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Servicios | BeautyAlt</title>
  <style>
    body { background: #fdeef0; font-family: 'Segoe UI'; padding: 2rem; }
    h1 { color: #e48c94; text-align: center; }
    form, table { max-width: 800px; margin: auto; background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); margin-bottom: 2rem; }
    input, select, textarea { width: 100%; padding: 0.8rem; margin: 0.5rem 0; border: 1px solid #ccc; border-radius: 8px; }
    button { background: #c96a76; color: white; border: none; padding: 0.8rem 1.2rem; border-radius: 8px; cursor: pointer; }
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 1rem; border-bottom: 1px solid #eee; }
    th { background: #f8d3d9; }
  </style>
</head>
<body>
<h1>Gestión de Servicios – BeautyAlt</h1>

<form method="POST">
  <input type="hidden" name="accion" value="agregar">
  <label>Nombre del servicio:</label>
  <input type="text" name="nombre" required>

  <label>Duración (minutos):</label>
  <input type="number" name="duracion" required>

  <label>Costo:</label>
  <input type="number" step="0.01" name="costo" required>

  <label>Categoría:</label>
  <select name="categoria" required>
    <option value="Uñas">Uñas</option>
    <option value="Pestañas">Pestañas</option>
    <option value="Maquillaje">Maquillaje</option>
    <option value="Cejas">Cejas</option>
    <option value="Cabello">Cabello</option>
    <option value="Facial">Facial</option>
    <option value="Masajes">Masajes</option>
  </select>

  <label>Descripción:</label>
  <textarea name="descripcion"></textarea>

  <button type="submit">Agregar servicio</button>
</form>

<table>
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Duración</th>
      <th>Costo</th>
      <th>Categoría</th>
      <th>Descripción</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    <?php while($s = $servicios->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($s['nombre']) ?></td>
        <td><?= $s['duracion'] ?> min</td>
        <td>$<?= number_format($s['costo'], 2) ?></td>
        <td><?= htmlspecialchars($s['categoria']) ?></td>
        <td><?= htmlspecialchars($s['descripcion']) ?></td>
        <td><a href="?eliminar=<?= $s['id'] ?>" onclick="return confirm('¿Eliminar servicio?')">Eliminar</a></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</body>
</html>
