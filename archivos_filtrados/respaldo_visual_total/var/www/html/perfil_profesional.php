<?php
    
include_once 'includes/conexion.php';
    

    
$id_usuario = intval($_GET['id'] ?? 0);
    
if (!$id_usuario) {
    
    echo "<p style='color:red'>❌ ID de usuario no válido.</p>";
    
    exit;
    
}
    

    
// Verificar conexión a la base de datos
    
if (!$conn || $conn->connect_error) {
    
    die("<p style='color:red'>❌ Error de conexión a la base de datos: " . $conn->connect_error . "</p>");
    
}
    

    
// Consultar datos del usuario
    
$sql_usuario = "SELECT * FROM usuarios WHERE id = $id_usuario";
    
$res_usuario = $conn->query($sql_usuario);
    

    
if ($res_usuario && $res_usuario->num_rows > 0) {
    
    $usuario = $res_usuario->fetch_assoc();
    
} else {
    
    echo "<p style='color:red'>❌ Usuario no encontr…
[7:27 p.m., 18/6/2025] .: <?php
  
include_once 'includes/conexion.php';
  

  
$id_usuario = intval($_GET['id'] ?? 0);
  
if (!$id_usuario) {
  
    echo "<p style='color:red'>❌ ID de usuario no válido.</p>";
  
    exit;
  
}
  

  
// Verificar conexión a la base de datos
  
if (!$conn || $conn->connect_error) {
  
    die("<p style='color:red'>❌ Error de conexión a la base de datos: " . $conn->connect_error . "</p>");
  
}
  

  
// Consultar datos del usuario
  
$sql_usuario = "SELECT * FROM usuarios WHERE id = $id_usuario";
  
$res_usuario = $conn->query($sql_usuario);
  

  
if ($res_usuario && $res_usuario->num_rows > 0) {
  
    $usuario = $res_usuario->fetch_assoc();
  
} else {
  
    echo "<p style='color:red'>❌ Usuario no encontrado o error en la consulta.</p>";
  
    e…
[7:40 p.m., 18/6/2025] .: <?php
  
include_once 'includes/conexion.php';
  

  
$id_usuario = intval($_GET['id'] ?? 0);
  
if (!$id_usuario) {
  
    echo "<p style='color:red'>❌ ID de usuario no válido.</p>";
  
    exit;
  
}
  

  
// Verificar conexión a la base de datos
  
if (!$conn || $conn->connect_error) {
  
    die("<p style='color:red'>❌ Error de conexión a la base de datos: " . $conn->connect_error . "</p>");
  
}
  

  
// Consultar datos del usuario
  
$sql_usuario = "SELECT * FROM usuarios WHERE id = $id_usuario";
  
$res_usuario = $conn->query($sql_usuario);
  

  
if ($res_usuario && $res_usuario->num_rows > 0) {
  
    $usuario = $res_usuario->fetch_assoc();
  
} else {
  
    echo "<p style='color:red'>❌ Usuario no encontrado o error en la consulta.</p>";
  
    exit;
  
}
  

  
// Consultar último mensaje
  
$msg = null;
  
$sql_ult_msg = "SELECT * FROM mensajes WHERE id_receptor = $id_usuario ORDER BY enviado_en DESC LIMIT 1";
  
$res_ult_msg = $conn->query($sql_ult_msg);
  
if ($res_ult_msg && $res_ult_msg->num_rows > 0) {
  
    $msg = $res_ult_msg->fetch_assoc();
  
}
  
?><!DOCTYPE html><html lang="es"><head>  <meta charset="UTF-8">  <title>Perfil Profesional | BeautyAlt</title>  <meta name="viewport" content="width=device-width, initial-scale=1.0">  <style>
  
    body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #fdf1f4; }
  
    .header { background: #d66d7e; color: white; padding: 1rem 2rem; text-align: center; font-size: 1.4rem; font-weight: bold; }
  
    .container { max-width: 1000px; margin: auto; padding: 1rem; }
  

  
    .profile-top { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 1rem; }
  
    .profile-pic { position: relative; width: 140px; height: 140px; border-radius: 50%; overflow: hidden; border: 4px solid #fdd2da; }
  
    .profile-pic img { width: 100%; height: 100%; object-fit: cover; }
  
    .upload-btn { position: absolute; bottom: 5px; right: 5px; background: #e48c94; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; cursor: pointer; }
  

  
    .info-main { flex: 1; min-width: 250px; }
  
    .info-main h2 { margin: 0; font-size: 1.6rem; color: #d14b5e; }
  
    .info-main p { margin: 0.3rem 0; color: #444; font-size: 0.95rem; }
  

  
    .btn { display: inline-block; padding: 0.5rem 1rem; background: #d66d7e; color: white; border-radius: 8px; text-decoration: none; margin: 0.3rem 0.5rem 0 0; font-size: 0.9rem; }
  

  
    /* 🔧 TAB buttons ahora en una línea siempre */
  
    .tabs {
  
      display: flex;
  
      flex-wrap: nowrap;
  
      justify-content: center;
  
      gap: 0.5rem;
  
      overflow-x: auto;
  
      padding: 0.5rem 0;
  
      -webkit-overflow-scrolling: touch;
  
      scrollbar-width: none;
  
    }
  
    .tabs::-webkit-scrollbar {
  
      display: none;
  
    }
  
    .tabs button {
  
      flex: 0 0 auto;
  
      background: #fff;
  
      border: 2px solid #d66d7e;
  
      padding: 0.6rem 1.2rem;
  
      border-radius: 10px;
  
      color: #d66d7e;
  
      font-weight: bold;
  
      cursor: pointer;
  
      transition: 0.3s;
  
    }
  
    .tabs button.active, .tabs button:hover {
  
      background: #d66d7e;
  
      color: white;
  
    }
  

  
    .section { margin-top: 1.5rem; background: white; padding: 1rem; border-radius: 10px; box-shadow: 0 0 6px rgba(0,0,0,0.05); }
  

  
    .upload-form { margin-top: 1rem; }
  
    input[type="file"], input[type="text"] {
  
      display: block; margin: 0.5rem 0; padding: 0.5rem;
  
      width: 100%; max-width: 400px;
  
    }
  

  
    @media (max-width: 600px) {
  
      .profile-top { flex-direction: column; align-items: flex-start; }
  
    }
  
  </style></head><body>  <div class="header">BeautyAlt | Perfil Profesional</div>  <div class="container"><!-- Perfil -->

<div class="profile-top">

  <div class="profile-pic">

    <img src="<?= htmlspecialchars($usuario['foto_perfil'] ?? '/assets/img/default_user.png'); ?>" alt="Foto de perfil">

    <label class="upload-btn">Cambiar<input type="file" style="display:none"></label>

  </div>

  <div class="info-main">

    <h2><?= htmlspecialchars($usuario['nombre']); ?></h2>

    <p><strong>Ubicación:</strong> <?= htmlspecialchars($usuario['ubicacion'] ?? ''); ?></p>

    <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono'] ?? ''); ?></p>

    <p><strong>Especialidad:</strong> <?= htmlspecialchars($usuario['especialidad'] ?? ''); ?></p>

    <?php if (!empty($usuario['correo'])): ?>

      <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo']); ?></p>

    <?php endif; ?>

    <?php if (!empty($usuario['instagram'])): ?>

      <p><strong>Instagram:</strong> <a href="https://instagram.com/<?= htmlspecialchars($usuario['instagram']); ?>" target="_blank">@<?= htmlspecialchars($usuario['instagram']); ?></a></p>

    <?php endif; ?>

    <div>

      <a href="reservar_cita.php?id=<?= $id_usuario ?>" class="btn">Reservar Cita</a>

      <a href="#mensaje" class="btn" onclick="document.getElementById('mensaje').scrollIntoView({behavior: 'smooth'});">Mandar Mensaje</a>

      <a href="editar_perfil.php?id=<?= $id_usuario ?>" class="btn">Editar Perfil</a>

    </div>

  </div>

</div>



<!-- Tabs -->

<div class="tabs">

  <button onclick="scrollToSection('servicios')" class="tab-btn active">🧴 Servicios</button>

  <button onclick="scrollToSection('galeria')" class="tab-btn">🖼 Galería</button>

  <button onclick="scrollToSection('opiniones')" class="tab-btn">🌟 Opiniones</button>

  <button onclick="scrollToSection('informacion')" class="tab-btn">📄 Información</button>

</div>



<!-- Secciones -->

<div id="servicios" class="section">

  <h3>Servicios Disponibles</h3>

  <?php include 'includes/servicios.php'; ?>

</div>



<div id="galeria" class="section">

  <h3>Galería de Trabajos</h3>

  <div style="text-align: center; margin-top: 1rem;">

    <button class="btn" onclick="toggleUploadForm()">➕ Subir Fotos a Galería</button>

  </div>

  <form id="uploadForm" class="upload-form" action="galeria_subir.php" method="post" enctype="multipart/form-data" style="display:none;">

    <input type="file" name="imagenes[]" multiple required>

    <input type="text" name="descripcion" placeholder="Descripción general">

    <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

    <button type="submit" class="btn">Subir</button>

  </form>

  <?php include 'includes/galeria.php'; ?>

</div>



<div id="opiniones" class="section">

  <h3>Opiniones y Reputación</h3>

  <?php include 'includes/opiniones.php'; ?>

</div>



<div id="informacion" class="section">

  <h3>Información Profesional</h3>

  <p><?= nl2br(htmlspecialchars($usuario['descripcion'] ?? 'Este profesional aún no ha completado su descripción.')); ?></p>

</div>

  </div>  <!-- Script -->  <script>
  
    function scrollToSection(id) {
  
      document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
  
    }
  
    function toggleUploadForm() {
  
      const form = document.getElementById('uploadForm');
  
      form.style.display = (form.style.display === 'block') ? 'none' : 'block';
  
    }
  
  </script><!-- NUEVO COMPONENTE DE MENSAJE MEJORADO --><div class="chat-init-box" id="mensaje-privado">  <h3>Enviar mensaje privado a este profesional</h3>  <form id="formMensaje" action="procesar_mensaje.php" method="post"><input type="hidden" name="id_emisor" value="<?= $_SESSION['id_usuario'] ?? 0 ?>">

<input type="hidden" name="id_receptor" value="<?= $id_usuario ?>">

<textarea name="contenido" placeholder="Escribe tu mensaje aquí..." maxlength="800" required></textarea>

<div class="msg-footer">

  <span class="msg-char-count" id="charCount">0 / 800</span>

  <button type="submit">Enviar mensaje</button>

</div>

  </form></div><style>
  
.chat-init-box {
  
  background: #fff0f4;
  
  border-radius: 14px;
  
  padding: 1.8rem;
  
  box-shadow: 0 5px 14px rgba(209, 75, 94, 0.1);
  
  margin: 2.5rem auto;
  
  max-width: 700px;
  
  animation: fadeInUp 0.6s ease;
  
}
  
.chat-init-box h3 {
  
  color: #d14b5e;
  
  margin-bottom: 1.2rem;
  
  font-size: 1.3rem;
  
}
  
.chat-init-box textarea {
  
  width: 100%;
  
  border-radius: 10px;
  
  border: 1px solid #ccc;
  
  padding: 1rem;
  
  font-size: 1rem;
  
  resize: vertical;
  
  min-height: 120px;
  
  transition: border 0.3s ease;
  
}
  
.chat-init-box textarea:focus {
  
  border-color: #d66d7e;
  
  outline: none;
  
}
  
.msg-footer {
  
  display: flex;
  
  justify-content: space-between;
  
  align-items: center;
  
  margin-top: 0.8rem;
  
}
  
.msg-char-count {
  
  font-size: 0.85rem;
  
  color: #999;
  
}
  
.chat-init-box button {
  
  background: #d14b5e;
  
  border: none;
  
  padding: 0.8rem 1.6rem;
  
  color: white;
  
  border-radius: 8px;
  
  font-weight: bold;
  
  cursor: pointer;
  
  transition: background 0.3s ease;
  
}
  
.chat-init-box button:hover {
  
  background: #bb3f54;
  
}
  
.chat-preview-box {
  
  background: #fff8fb;
  
  margin-top: 1rem;
  
  padding: 1rem;
  
  border-left: 4px solid #d14b5e;
  
  border-radius: 10px;
  
}
  
.chat-bubble {
  
  background: #fdd2da;
  
  padding: 0.7rem 1rem;
  
  border-radius: 10px;
  
  color: #444;
  
  font-size: 0.95rem;
  
  position: relative;
  
}
  
.chat-bubble small {
  
  display: block;
  
  font-size: 0.75rem;
  
  color: #777;
  
  margin-top: 4px;
  
}
  
@keyframes fadeInUp {
  
  from {
  
    opacity: 0;
  
    transform: translateY(30px);
  
  }
  
  to {
  
    opacity: 1;
  
    transform: translateY(0);
  
  }
  
}
  
</style><script>
  
const textarea = document.querySelector('textarea[name="contenido"]');
  
const charCount = document.getElementById("charCount");
  

  
textarea.addEventListener("input", () => {
  
  charCount.textContent = ${textarea.value.length} / 800;
  
});
  

  
document.getElementById("formMensaje").addEventListener("submit", function(e) {
  
  e.preventDefault();
  
  const form = e.target;
  
  const formData = new FormData(form);
  

  
  fetch(form.action, {
  
    method: "POST",
  
    body: formData
  
  })
  
  .then(res => res.text())
  
  .then(res => {
  
    alert("Mensaje enviado correctamente 💌");
  
    textarea.value = '';
  
    charCount.textContent = "0 / 800";
  
    form.scrollIntoView({ behavior: "smooth" });
  
  })
  
  .catch(() => alert("Ocurrió un error al enviar el mensaje"));
  
});
  
</script></div><?php
  
$msg = null; // definir por defecto
  
if (isset($conn) && $conn) {
  
    $id_receptor = intval($id_usuario); // perfil actual
  
    $sql_ult_msg = "SELECT * FROM mensajes WHERE id_receptor = $id_receptor ORDER BY enviado_en DESC LIMIT 1";
  
    $res_ult_msg = $conn->query($sql_ult_msg);
  
    if ($res_ult_msg && $res_ult_msg->num_rows > 0) {
  
        $msg = $res_ult_msg->fetch_assoc();
  
    }
  
}
  
?>
</body>
</html>