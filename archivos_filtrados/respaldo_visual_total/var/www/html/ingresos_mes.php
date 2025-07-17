<?php
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mesActual = date('m');
$anioActual = date('Y');

$sql = "SELECT nombre_cliente, servicio, fecha, hora, monto, estado_pago 
        FROM citas 
        WHERE MONTH(fecha) = ? AND YEAR(fecha) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $mesActual, $anioActual);
$stmt->execute();
$result = $stmt->get_result();

$total_pagado = 0;
$total_pendiente = 0;
$total_deposito = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ingresos del Mes | BeautyAlt</title>
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
    .resumen {
      margin-top: 2rem;
      background: white;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>
<body>
  <h2>💰 Ingresos del mes de <?= date('F Y') ?></h2>

  <table>
    <tr>
      <th>Cliente</th>
      <th>Servicio</th>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Monto</th>
      <th>Estado</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): 
      switch ($row['estado_pago']) {
        case 'pagado':
          $total_pagado += $row['monto'];
          break;
        case 'pendiente':
          $total_pendiente += $row['monto'];
          break;
        case 'depósito':
        case 'deposito': // por si se escribe sin tilde
          $total_deposito += $row['monto'];
          break;
      }
    ?>
    <tr>
      <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
      <td><?= htmlspecialchars($row['servicio']) ?></td>
      <td><?= $row['fecha'] ?></td>
      <td><?= $row['hora'] ?></td>
      <td>$<?= number_format($row['monto'], 2) ?></td>
      <td><?= ucfirst($row['estado_pago']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

  <div class="resumen">
    <h3>📊 Resumen del mes</h3>
    <p><strong>Total pagado:</strong> $<?= number_format($total_pagado, 2) ?> pesos</p>
    <p><strong>Total pendiente:</strong> $<?= number_format($total_pendiente, 2) ?> pesos</p>
    <p><strong>Total en depósito:</strong> $<?= number_format($total_deposito, 2) ?> pesos</p>
    <p style="margin-top:1rem;"><strong>Total general:</strong> $<?= number_format($total_pagado + $total_pendiente + $total_deposito, 2) ?> pesos</p>
  </div>
</body>
</html>
