<?php
$conn = new mysqli("mariadb", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Actualizar etiqueta emocional (AJAX)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['correo']) && isset($_POST['etiqueta'])) {
    $correo = $conn->real_escape_string($_POST['correo']);
    $etiqueta = $conn->real_escape_string($_POST['etiqueta']);
    $update = $conn->query("UPDATE citas SET etiqueta_emocional = '$etiqueta' WHERE correo_cliente = '$correo'");
    echo $update ? "ok" : "error";
    exit;
}

// Obtener clientas únicas
$clientas = $conn->query("
    SELECT 
        correo_cliente,
        nombre_cliente,
        telefono_cliente,
        MAX(fecha) AS ultima_cita,
        GROUP_CONCAT(DISTINCT servicio SEPARATOR ', ') AS servicios,
        SUM(monto) AS total_invertido,
        etiqueta_emocional
    FROM citas
    WHERE correo_cliente IS NOT NULL AND correo_cliente != ''
    GROUP BY correo_cliente
    ORDER BY ultima_cita DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cartera de Clientas | BeautyAlt</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #fdeef0;
      margin: 0;
      padding: 2rem;
    }
    h1 {
      text-align: center;
      color: #e48c94;
    }
    input[type="text"] {
      width: 80%;
      padding: 1rem;
      font-size: 1rem;
      margin: 1rem auto;
      display: block;
      border: 1px solid #ccc;
      border-radius: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }
    th, td {
      padding: 1rem;
      border-bottom: 1px solid #eee;
      text-align: left;
      font-size: 0.95rem;
    }
    th {
      background-color: #f8d3d9;
      color: #333;
    }
    tr:hover {
      background-color: #fff3f3;
      cursor: pointer;
    }
    select {
      padding: 0.4rem;
      border-radius: 6px;
      font-size: 0.9rem;
    }
    .historial {
      display: none;
      background: #fff7f9;
      padding: 1rem;
      border: 1px solid #ddd;
      margin-top: 0.5rem;
      border-radius: 8px;
    }
  </style>
  <script>
    function buscarCliente() {
      const input = document.getElementById("busqueda").value.toLowerCase();
      const filas = document.querySelectorAll("tbody tr.principal");
      filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(input) ? "" : "none";
      });
    }

    function actualizarEtiqueta(correo, select) {
      const etiqueta = select.value;
      const formData = new FormData();
      formData.append("correo", correo);
      formData.append("etiqueta", etiqueta);

      fetch("", { method: "POST", body: formData })
        .then(res => res.text())
        .then(respuesta => {
          if (respuesta !== "ok") {
            alert("Error al guardar etiqueta.");
          }
        });
    }

    function toggleHistorial(id) {
      const historial = document.getElementById("historial-" + id);
      if (historial.style.display === 'block') {
        historial.style.display = 'none';
      } else {
        historial.style.display = 'block';
      }
    }
  </script>
</head>
<body>
  <h1>Cartera de Clientas – BeautyAlt</h1>

  <input id="busqueda" type="text" onkeyup="buscarCliente()" placeholder="🔍 Buscar por nombre, correo o teléfono" />

  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Servicios</th>
        <th>Última cita</th>
        <th>Total invertido</th>
        <th>Etiqueta emocional</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $clientas->fetch_assoc()):
        $correo = $conn->real_escape_string($row['correo_cliente']);
        $id = md5($correo);
        $historial = $conn->query("SELECT fecha, hora, servicio, estado_pago FROM citas WHERE correo_cliente = '$correo' ORDER BY fecha DESC");
        $etiqueta_actual = $row['etiqueta_emocional'];
      ?>
        <tr class="principal" onclick="toggleHistorial('<?php echo $id ?>')">
          <td><?php echo htmlspecialchars($row['nombre_cliente']); ?></td>
          <td><?php echo htmlspecialchars($row['correo_cliente']); ?></td>
          <td><?php echo htmlspecialchars($row['telefono_cliente']); ?></td>
          <td><?php echo htmlspecialchars($row['servicios']); ?></td>
          <td><?php echo htmlspecialchars($row['ultima_cita']); ?></td>
          <td>$<?php echo number_format($row['total_invertido'], 2); ?></td>
          <td>
            <select onchange="actualizarEtiqueta('<?php echo htmlspecialchars($row['correo_cliente']); ?>', this)">
              <option value="">Selecciona</option>
              <option value="VIP" <?= $etiqueta_actual == "VIP" ? "selected" : "" ?>>🌟 VIP</option>
              <option value="Conectada emocionalmente" <?= $etiqueta_actual == "Conectada emocionalmente" ? "selected" : "" ?>>❤️ Conectada emocionalmente</option>
              <option value="Ansiosa/insegura" <?= $etiqueta_actual == "Ansiosa/insegura" ? "selected" : "" ?>>🤯 Ansiosa/Insegura</option>
              <option value="Precio sensible" <?= $etiqueta_actual == "Precio sensible" ? "selected" : "" ?>>💸 Precio sensible</option>
              <option value="Irregular" <?= $etiqueta_actual == "Irregular" ? "selected" : "" ?>>🔄 Irregular</option>
              <option value="Recomendadora" <?= $etiqueta_actual == "Recomendadora" ? "selected" : "" ?>>📣 Recomendadora</option>
              <option value="Intensa mental" <?= $etiqueta_actual == "Intensa mental" ? "selected" : "" ?>>🧠 Intensa mental</option>
              <option value="Tranquila" <?= $etiqueta_actual == "Tranquila" ? "selected" : "" ?>>🧘 Tranquila</option>
            </select>
          </td>
        </tr>
        <tr id="historial-<?php echo $id ?>" class="historial">
          <td colspan="7">
            <strong>Historial de citas:</strong><br>
            <?php while($cita = $historial->fetch_assoc()): ?>
              📅 <strong><?php echo $cita['fecha']; ?></strong> a las <strong><?php echo $cita['hora']; ?></strong> — 
              <em><?php echo htmlspecialchars($cita['servicio']); ?></em> 
              [<strong><?php echo ucfirst($cita['estado_pago']); ?></strong>]<br>
            <?php endwhile; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
