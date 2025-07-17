<?php
$conn = new mysqli("localhost", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID no válido.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM citas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$cita = $resultado->fetch_assoc();

if (!$cita) {
    die("Error: La cita no fue encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cita | BeautyAlt</title>
  <style>
    body {
      background: #fdeef0;
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
    }
    .formulario {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    input, select, textarea {
      width: 100%;
      padding: 0.8rem;
      margin: 0.5rem 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    button {
      background: #c96a76;
      color: white;
      border: none;
      padding: 1rem;
      width: 100%;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    #respuesta {
      margin-top: 1rem;
      font-weight: bold;
      text-align: center;
      color: green;
    }
  </style>
</head>
<body>

  <div class="formulario">
    <h2>Editar cita</h2>
    <form id="formEditar">
      <input type="hidden" name="id" value="<?= $cita['id'] ?>">
      <input type="text" name="nombre_cliente" value="<?= htmlspecialchars($cita['nombre_cliente']) ?>" required>
      <input type="email" name="correo_cliente" value="<?= htmlspecialchars($cita['correo_cliente']) ?>" required>
      <input type="tel" name="telefono_cliente" value="<?= htmlspecialchars($cita['telefono_cliente']) ?>">
      <input type="text" name="direccion_cliente" value="<?= htmlspecialchars($cita['direccion_cliente']) ?>">
      <textarea name="referencia"><?= htmlspecialchars($cita['referencia']) ?></textarea>
      <input type="date" name="fecha" value="<?= $cita['fecha'] ?>" required>
      <input type="time" name="hora" value="<?= $cita['hora'] ?>" required>
      <input type="text" name="servicio" value="<?= htmlspecialchars($cita['servicio']) ?>" required>
      <input type="number" step="0.01" name="monto" value="<?= $cita['monto'] ?>" required>
      <select name="estado_pago" required>
        <option value="pendiente" <?= $cita['estado_pago'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
        <option value="pagado" <?= $cita['estado_pago'] === 'pagado' ? 'selected' : '' ?>>Pagado</option>
        <option value="cancelado" <?= $cita['estado_pago'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
      </select>
      <button type="submit">Guardar cambios</button>
    </form>
    <div id="respuesta"></div>
  </div>

<script>
  document.getElementById("formEditar").addEventListener("submit", function(e) {
    e.preventDefault();
    const datos = new FormData(this);

    fetch("actualizar_cita.php", {
      method: "POST",
      body: datos
    })
    .then(res => res.text())
    .then(data => {
      document.getElementById("respuesta").innerText = data;
    })
    .catch(err => {
      document.getElementById("respuesta").innerText = "Error al actualizar.";
    });
  });
</script>

</body>
</html>
