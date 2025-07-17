<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil Profesional | BeautyAlt</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f3f3f5; }
    .header { background: #d66d7e; color: white; padding: 1rem 2rem; font-size: 1.6rem; font-weight: bold; }
    .container { max-width: 1100px; margin: 2rem auto; background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
    .profile-top { display: flex; gap: 2rem; border-bottom: 1px solid #eee; padding-bottom: 2rem; }
    .profile-pic { position: relative; width: 150px; height: 150px; border-radius: 50%; overflow: hidden; border: 4px solid #fdd2da; }
    .profile-pic img { width: 100%; height: 100%; object-fit: cover; }
    .upload-btn { position: absolute; bottom: 5px; right: 5px; background: #e48c94; color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; cursor: pointer; }
    .info-main { flex: 1; }
    .info-main h2 { margin: 0; font-size: 1.8rem; color: #d14b5e; }
    .info-main p { margin: 0.5rem 0; color: #444; }
    .action-btns { margin-top: 1rem; display: flex; gap: 1rem; }
    .btn { padding: 0.5rem 1rem; background: #d66d7e; color: white; border-radius: 8px; text-decoration: none; font-weight: bold; }
    .tabs { display: flex; gap: 2rem; margin-top: 2rem; border-bottom: 2px solid #eee; padding-bottom: 1rem; font-weight: bold; color: #555; }
    .tabs div:hover { cursor: pointer; color: #d66d7e; }
    .section { margin-top: 2rem; }
    .service, .photo, .review { background: #fff5f6; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; }
    .photo img { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; margin-right: 10px; }
    .review strong { color: #d14b5e; }
    .message-btn { float: right; background: #5a88e5; }
  </style>
</head>
<body>
  <div class="header">BeautyAlt | Perfil Profesional</div>
  <div class="container">
    <div class="profile-top">
      <div class="profile-pic">
        <img src="/assets/img/default_user.png" alt="Foto de perfil">
        <label class="upload-btn">
          Cambiar
          <input type="file" style="display:none">
        </label>
      </div>
      <div class="info-main">
        <h2>Valeria Olivos MUA</h2>
        <p>📍 Nuevo Laredo, Tamaulipas</p>
        <p>📱 867-123-4567</p>
        <p>💎 Embajadora BeautyAlt | Profesional Comprometida</p>
        <div class="action-btns">
          <a href="#" class="btn">Reservar Cita</a>
          <a href="#" class="btn message-btn">Mandar Mensaje</a>
          <a href="editar_perfil.php?id=1" class="btn">Editar Perfil</a>
        </div>
      </div>
    </div>

    <div class="tabs">
      <div>Servicios</div>
      <div>Galería</div>
      <div>Opiniones</div>
      <div>Información</div>
    </div>

    <div class="section">
      <h3>Servicios Disponibles</h3>
      <div class="service">
        <strong>Maquillaje Social</strong><br>
        Duración: 60 min · $1,000 · Categoría: Maquillaje
      </div>
      <div class="service">
        <strong>Peinado</strong><br>
        Duración: 45 min · $550 · Categoría: Peinados
      </div>
    </div>

    <div class="section">
      <h3>Galería de Trabajos</h3>
      <div class="photo">
        <img src="/uploads/galeria1.jpg">
        <img src="/uploads/galeria2.jpg">
      </div>
    </div>

    <div class="section">
      <h3>Opiniones y Reputación</h3>
      <div class="review">
        <strong>Alicia Ortega ★★★★★</strong><br>
        “Me encantó el resultado, muy profesional.”
      </div>
      <div class="review">
        <strong>Estefanía Ruiz ★★★★★</strong><br>
        “Excelente trato y puntualidad.”
      </div>
    </div>

    <div class="section">
      <h3>Información Profesional</h3>
      <p>Certificaciones, años de experiencia, especialidades y estilo de trabajo.</p>
    </div>
  </div>
</body>
</html>
