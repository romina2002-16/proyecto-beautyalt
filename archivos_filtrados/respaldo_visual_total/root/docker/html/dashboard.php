
<?php
$conn = new mysqli("localhost", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");
if ($conn->connect_error) { die("Conexión fallida: " . $conn->connect_error); }

$citas_hoy = 0;
$ingresos_mes = 0;
$reputacion = "Estrella Dorada";

$sql_citas = "SELECT COUNT(*) AS total FROM citas WHERE fecha = CURDATE()";
$result_citas = $conn->query($sql_citas);
if ($row = $result_citas->fetch_assoc()) { $citas_hoy = $row["total"]; }

$sql_ingresos = "SELECT SUM(monto) AS total FROM citas WHERE estado_pago = 'pagado' AND MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
$result_ingresos = $conn->query($sql_ingresos);
if ($row = $result_ingresos->fetch_assoc()) { $ingresos_mes = $row["total"] ?? 0; }

$sqlFuturas = "SELECT COUNT(*) AS total FROM citas WHERE fecha >= CURDATE() AND fecha <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$resFuturas = $conn->query($sqlFuturas);
$totalFuturas = $resFuturas->fetch_assoc()['total'];

$sqlPendiente = "SELECT SUM(monto) AS total FROM citas WHERE estado_pago = 'pendiente'";
$resPendiente = $conn->query($sqlPendiente);
$ingresosPendientes = $resPendiente->fetch_assoc()['total'] ?? 0.00;

$sqlClientes = "SELECT COUNT(DISTINCT correo_cliente) AS total FROM citas WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE()) AND correo_cliente IS NOT NULL";
$resClientes = $conn->query($sqlClientes);
$totalClientas = $resClientes->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard | BeautyAlt</title>
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #fdeef0;
      color: #333;
    }
    .container {
      max-width: 960px;
      margin: auto;
      padding: 2rem;
    }
    .header {
      text-align: center;
      padding: 2rem 0;
    }
    .header h1 {
      font-size: 2rem;
      color: #e48c94;
    }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
    }
    .card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }
    .card:hover {
      transform: scale(1.03);
      cursor: pointer;
    }
    .card h2, .card h3 {
      font-size: 1.2rem;
      color: #c96a76;
      margin-bottom: 0.5rem;
    }
    .card p {
      font-size: 0.95rem;
      line-height: 1.4;
    }
    a {
      text-decoration: none;
      color: inherit;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Bienvenida al panel de BeautyAlt, Valeria</h1>
      <p>Este es tu espacio real, conectado con tu esfuerzo.</p>
    </div>

    <div class="card-grid">
      <a href="citas_hoy.php">
        <div class="card">
          <h2>Citas del día</h2>
          <p><strong><?php echo $citas_hoy; ?></strong> cita(s) agendada(s) hoy.</p>
        </div>
      </a>
      <a href="ingresos_mes.php">
        <div class="card">
          <h2>Ingresos del mes</h2>
          <p><strong>$<?php echo number_format($ingresos_mes, 2); ?></strong> pesos generados este mes.</p>
        </div>
      </a>
      <a href="reputacion.php">
        <div class="card">
          <h2>Nivel de reputación</h2>
          <p><strong><?php echo $reputacion; ?></strong> basado en tu desempeño y energía emocional.</p>
        </div>
      </a>
      <a href="agenda_futura.php">
        <div class="card">
          <h3>Agenda futura</h3>
          <p><strong><?php echo $totalFuturas; ?></strong> cita(s) próximas esta semana.</p>
          <p>Ingresos proyectados: <strong>$<?php echo number_format($ingresosPendientes, 2); ?></strong> pesos.</p>
        </div>
      </a>
      <a href="cartera_clientas.php">
        <div class="card">
          <h3>Cartera de clientas</h3>
          <p>Has atendido a <strong><?php echo $totalClientas; ?></strong> clientas distintas este mes.</p>
          <p>Conserva su confianza, su historia y su próxima cita.</p>
        </div>
      </a>
    </div>

    <div class="card" style="margin-top: 2rem; overflow-x: auto;">
      <h2>Agenda Visual</h2>
      <div id='calendar'></div>
    </div>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,dayGridDay'
    },
    locale: 'es',
    height: "auto",
    events: 'agenda_citas.php',
    eventClick: function(info) {
      const e = info.event;
      const x = e.extendedProps;
      const modal = document.getElementById('modalCita');
      const detalle = `
        <strong>Cliente:</strong> ${x.cliente}<br>
        <strong>Servicio:</strong> ${e.title}<br>
        <strong>Fecha:</strong> ${e.start.toLocaleDateString()}<br>
        <strong>Hora:</strong> ${x.hora}<br>
        <strong>Estado:</strong> ${x.estado_pago}<br>
        <strong>Teléfono:</strong> ${x.telefono}<br>
        <strong>Dirección:</strong> ${x.direccion}<br>
        <strong>Notas:</strong> ${x.referencia}<br><br>
        <a href="editar_cita.php?id=${e.id}" style="background:#5c6bc0;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;">✏️ Editar esta cita</a>
      `;
      document.getElementById("detalleCita").innerHTML = detalle;
      modal.style.display = "flex";
    }
  });

  calendar.render();
});

function cerrarModal() {
  document.getElementById("modalCita").style.display = "none";
}
</script>
<!-- MODAL BONITO PARA VER CITA -->
<div id="modalCita" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4); z-index:9999; justify-content:center; align-items:center;">
  <div style="background:white; padding:2rem; border-radius:16px; max-width:400px; width:90%; font-family:'Segoe UI'; box-shadow:0 0 30px rgba(0,0,0,0.2); position:relative;">
    <h3 style="margin-top:0; color:#c96a76;">✨ Detalles de la cita</h3>
    <div id="detalleCita" style="font-size:0.95rem; color:#444;"></div>
    <div style="text-align:right; margin-top:1.5rem;">
      <button onclick="cerrarModal()" style="background:#c96a76; color:white; border:none; padding:0.6rem 1.2rem; border-radius:8px; cursor:pointer;">Cerrar</button>
    </div>
  </div>
</div>
</body>
</html>
