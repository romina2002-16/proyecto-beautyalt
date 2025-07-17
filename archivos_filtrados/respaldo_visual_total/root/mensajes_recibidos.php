<?php
include_once 'includes/conexion.php';
$id_profesional = 1; // Cambia según login o sesión

$sql = "SELECT * FROM mensajes WHERE id_profesional = $id_profesional ORDER BY fecha DESC";
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mensajes Recibidos</title>
  <style>
    body { font-family: sans-serif; background: #f9f9f9; padding: 2rem; }
    .mensaje { background: #fff; margin-bottom: 1rem; padding: 1rem; border-radius: 10px; box-shadow: 0 0 5px #ccc; }
    .mensaje h4 { margin: 0 0 0.5rem; color: #d14b5e; }
    .mensaje small { color: #666; }
  </style>
</head>
<body>
  <h2>📬 Mensajes Recibidos</h2>
  <?php while($m = $res->fetch_assoc()): ?>
    <div class="mensaje">
      <h4><?= htmlspecialchars($m['nombre_remitente']) ?> (<?= htmlspecialchars($m['correo_remitente']) ?>)</h4>
      <small><?= $m['fecha'] ?></small>
      <p><?= nl2br(htmlspecialchars($m['mensaje'])) ?></p>
    </div>
  <?php endwhile; ?>
</body>
</html>
