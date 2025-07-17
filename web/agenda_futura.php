<?php
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$hoy = date('Y-m-d');
$siguienteSemana = date('Y-m-d', strtotime('+7 days'));

$sql = "SELECT nombre_cliente, correo_cliente, telefono_cliente, fecha, hora, servicio, estado_pago 
        FROM citas 
        WHERE fecha >= ? AND fecha <= ?
        ORDER BY fecha ASC, hora ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hoy, $siguienteSemana);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agenda Futura | BeautyAlt</title>
  <style>
    body {
      background: #fdeef0;
      font-family: 'Segoe UI', sans-serif;
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
    td.estado-pagado {
      background-color: #d0f0d0;
    }
    td.estado-pendiente {
      background-color: #fff4c2;
    }
    td.estado-cancelado {
      background-color: #f8cccc;
    }
  </style>
</head>
<body>
  <h2>📅 Agenda de los próximos 7 días (<?= $hoy ?> → <?= $siguienteSemana ?>)</h2>
  <table>
    <tr>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Cliente</th>
      <th>Correo</th>
      <th>Teléfono</th>
      <th>Servicio</th>
      <th>Estado</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): 
      $estadoClass = 'estado-' . strtolower($row['estado_pago']);
    ?>
    <tr>
      <td><?= $row['fecha'] ?></td>
      <td><?= $row['hora'] ?></td>
      <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
      <td><?= htmlspecialchars($row['correo_cliente']) ?></td>
      <td><?= htmlspecialchars($row['telefono_cliente']) ?></td>
      <td><?= htmlspecialchars($row['servicio']) ?></td>
      <td class="<?= $estadoClass ?>"><?= ucfirst($row['estado_pago']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
