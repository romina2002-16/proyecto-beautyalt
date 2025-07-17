<?php
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$hoy = date('Y-m-d');
$sql = "SELECT * FROM citas WHERE fecha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hoy);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas de Hoy | BeautyAlt</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #fdeef0;
      padding: 2rem;
    }
    h2 {
      text-align: center;
      color: #d66c7b;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 0.8rem;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background: #f9c5d1;
    }
  </style>
</head>
<body>
  <h2>✨ Citas agendadas para hoy (<?= $hoy ?>)</h2>
  <table>
    <tr>
      <th>Cliente</th>
      <th>Servicio</th>
      <th>Hora</th>
      <th>Teléfono</th>
      <th>Estado</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
      <td><?= htmlspecialchars($row['servicio']) ?></td>
      <td><?= $row['hora'] ?></td>
      <td><?= $row['telefono_cliente'] ?></td>
      <td><?= $row['estado_pago'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
