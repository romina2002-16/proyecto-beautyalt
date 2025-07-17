<?php
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mesActual = date('m');
$anioActual = date('Y');

$sqlCitas = "SELECT COUNT(*) as total FROM citas WHERE MONTH(fecha) = ? AND YEAR(fecha) = ?";
$stmt = $conn->prepare($sqlCitas);
$stmt->bind_param("ss", $mesActual, $anioActual);
$stmt->execute();
$resultCitas = $stmt->get_result()->fetch_assoc();
$totalCitas = $resultCitas['total'] ?? 0;

$sqlClientas = "SELECT COUNT(DISTINCT correo_cliente) as total FROM citas WHERE MONTH(fecha) = ? AND YEAR(fecha) = ? AND correo_cliente IS NOT NULL";
$stmt = $conn->prepare($sqlClientas);
$stmt->bind_param("ss", $mesActual, $anioActual);
$stmt->execute();
$resultClientas = $stmt->get_result()->fetch_assoc();
$totalClientas = $resultClientas['total'] ?? 0;

$reputacion = "Estrella Dorada ✨";
if ($totalCitas >= 40) {
    $reputacion = "Galaxia de Belleza 🌌";
} elseif ($totalCitas >= 25) {
    $reputacion = "Aura de Éxito 💫";
} elseif ($totalCitas >= 10) {
    $reputacion = "Estrella Dorada ✨";
} elseif ($totalCitas >= 1) {
    $reputacion = "Luz Naciente 🌱";
} else {
    $reputacion = "Espacio en Calma 💤";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tu Reputación | BeautyAlt</title>
  <style>
    body {
      background: #fdeef0;
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
      color: #333;
    }
    .contenedor {
      max-width: 700px;
      background: white;
      margin: auto;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    h1 {
      text-align: center;
      color: #c96a76;
    }
    .resumen {
      margin-top: 2rem;
      font-size: 1.1rem;
    }
    .nivel {
      margin-top: 2rem;
      padding: 1.2rem;
      background: #fff7f8;
      border-left: 8px solid #f99cac;
      border-radius: 12px;
    }
    .nivel strong {
      color: #b24e5e;
    }
    .tabla {
      margin-top: 1.5rem;
    }
    .tabla table {
      width: 100%;
      border-collapse: collapse;
    }
    .tabla th, .tabla td {
      border: 1px solid #ddd;
      padding: 0.8rem;
    }
    .tabla th {
      background: #fcd4db;
      color: #444;
    }
  </style>
</head>
<body>
  <div class="contenedor">
    <h1>🌟 Tu Reputación del Mes</h1>

    <div class="resumen">
      <p><strong>Citas realizadas:</strong> <?= $totalCitas ?> en este mes.</p>
      <p><strong>Clientas distintas atendidas:</strong> <?= $totalClientas ?></p>
    </div>

    <div class="nivel">
      <p><strong>Nivel actual:</strong> <?= $reputacion ?></p>
      <p>Tu nivel se basa en el impacto emocional, la constancia y la conexión que generas con cada clienta. Es tu huella en la belleza emocional.</p>
    </div>

    <div class="tabla">
      <h3>🌸 Niveles de Reputación</h3>
      <table>
        <tr>
          <th>Nivel</th>
          <th>Citas requeridas</th>
          <th>Significado</th>
        </tr>
        <tr>
          <td>Galaxia de Belleza 🌌</td>
          <td>40+</td>
          <td>Dominas el universo de tu nicho con fuerza y luz.</td>
        </tr>
        <tr>
          <td>Aura de Éxito 💫</td>
          <td>25 - 39</td>
          <td>Brillas con fuerza y tu energía impacta más allá del servicio.</td>
        </tr>
        <tr>
          <td>Estrella Dorada ✨</td>
          <td>10 - 24</td>
          <td>Tu constancia y calidez ya son referencia.</td>
        </tr>
        <tr>
          <td>Luz Naciente 🌱</td>
          <td>1 - 9</td>
          <td>Has comenzado a brillar y a dejar huella.</td>
        </tr>
        <tr>
          <td>Espacio en Calma 💤</td>
          <td>0</td>
          <td>Este espacio está en pausa. El próximo mes puede ser el renacimiento.</td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>
