<?php
$conn = new mysqli("127.0.0.1", "root", "BeautyRoot123!", "beautyalt");
$conn->set_charset("utf8");

$busqueda = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$filtroCategoria = isset($_GET['categoria']) ? $conn->real_escape_string($_GET['categoria']) : '';
$filtroDuracion = isset($_GET['duracion']) ? intval($_GET['duracion']) : 0;
$filtroPrecio = isset($_GET['precio']) ? $conn->real_escape_string($_GET['precio']) : '';

$sql = "SELECT s.*, u.nombre AS profesional, u.ubicacion, u.foto_perfil FROM servicios s JOIN usuarios u ON s.id_usuario = u.id WHERE 1=1";
if ($busqueda) $sql .= " AND (s.nombre LIKE '%$busqueda%' OR s.descripcion LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%')";
if ($filtroCategoria) $sql .= " AND s.categoria = '$filtroCategoria'";
if ($filtroDuracion > 0) $sql .= " AND s.duracion <= $filtroDuracion";
if ($filtroPrecio === 'asc') $sql .= " ORDER BY s.costo ASC";
if ($filtroPrecio === 'desc') $sql .= " ORDER BY s.costo DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Marketplace de Belleza | BeautyAlt</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #fdeef0; margin: 0; padding: 2rem; }
    h1 { text-align: center; color: #d66d7e; }
    .busqueda, .filtros { display: flex; justify-content: center; margin: 1rem auto; gap: 1rem; flex-wrap: wrap; }
    input, select { padding: 0.6rem; border-radius: 8px; border: 1px solid #ccc; font-size: 1rem; }
    .tarjetas { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem; }
    .tarjeta { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 8px 20px rgba(0,0,0,0.08); transition: 0.3s ease; }
    .tarjeta:hover { transform: translateY(-5px); }
    .tarjeta img { width: 100%; max-height: 160px; object-fit: cover; border-radius: 10px; }
    .nombre { font-weight: bold; color: #d35d6e; font-size: 1.1rem; margin-top: 0.8rem; }
    .profesional { color: #333; margin: 0.3rem 0; }
    .detalle { font-size: 0.9rem; color: #555; }
    .boton { margin-top: 0.8rem; display: block; text-align: center; background: #e48c94; color: white; padding: 0.6rem; border-radius: 8px; text-decoration: none; font-weight: bold; }
  </style>
</head>
<body>
  <h1>✨ Marketplace de Belleza | BeautyAlt ✨</h1>
  <form class="busqueda">
    <input type="text" name="q" placeholder="🔍 Buscar por servicio o profesional" value="<?= htmlspecialchars($busqueda) ?>">
    <select name="categoria">
      <option value="">Categoría</option>
      <option value="Maquillaje">Maquillaje</option>
      <option value="Uñas">Uñas</option>
      <option value="Pestañas">Pestañas</option>
      <option value="Barbería">Barbería</option>
      <option value="Spa">Spa</option>
    </select>
    <select name="duracion">
      <option value="">Duración</option>
      <option value="60">≤ 60 min</option>
      <option value="90">≤ 90 min</option>
      <option value="120">≤ 120 min</option>
    </select>
    <select name="precio">
      <option value="">Precio</option>
      <option value="asc">Menor precio</option>
      <option value="desc">Mayor precio</option>
    </select>
    <button type="submit">Filtrar</button>
  </form>

  <div class="tarjetas">
    <?php while($row = $resultado->fetch_assoc()): ?>
      <div class="tarjeta">
        <img src="<?= htmlspecialchars($row['foto_perfil'] ?: '/assets/img/default_user.png') ?>" alt="Foto de profesional">
        <div class="nombre"><?= htmlspecialchars($row['nombre']) ?></div>
        <div class="profesional">Con <?= htmlspecialchars($row['profesional']) ?> | $<?= number_format($row['costo'], 0) ?></div>
        <div class="detalle">Duración: <?= $row['duracion'] ?> min • Categoría: <?= $row['categoria'] ?></div>
        <div class="detalle">Ubicación: <?= htmlspecialchars($row['ubicacion']) ?></div>
        <a class="boton" href="https://wa.me/?text=Hola!%20Quiero%20reservar%20el%20servicio%20de%20<?= urlencode($row['nombre']) ?>">Reservar ahora</a>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
