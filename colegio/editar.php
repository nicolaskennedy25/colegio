<?php
// editar.php - Editar datos de un estudiante
require_once 'php/conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: lista.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM estudiantes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$est = $resultado->fetch_assoc();

if (!$est) {
    header("Location: lista.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Estudiante – Colegio San Andrés</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body class="page-inner">

  <div class="bg-pattern"></div>

  <header class="navbar">
    <div class="logo">
      <span class="logo-icon">🎓</span>
      <span class="logo-text">Colegio San Andrés</span>
    </div>
    <nav>
      <a href="index.html">Inicio</a>
      <a href="registro.html">Registrar</a>
      <a href="lista.php">Estudiantes</a>
    </nav>
  </header>

  <main class="form-page">
    <div class="form-header">
      <h1>Editar <span class="accent">Estudiante</span></h1>
      <p>Modifica los datos de <strong><?= htmlspecialchars($est['nombres'].' '.$est['apellidos']) ?></strong></p>
    </div>

    <div class="form-container">
      <form action="php/actualizar.php" method="POST" id="formEditar">
        <input type="hidden" name="id" value="<?= $est['id'] ?>" />

        <div class="form-section">
          <h2 class="section-title">📌 Datos Personales</h2>
          <div class="form-grid">
            <div class="form-group">
              <label>Nombres *</label>
              <input type="text" name="nombres" value="<?= htmlspecialchars($est['nombres']) ?>" required />
            </div>
            <div class="form-group">
              <label>Apellidos *</label>
              <input type="text" name="apellidos" value="<?= htmlspecialchars($est['apellidos']) ?>" required />
            </div>
            <div class="form-group">
              <label>N° Documento *</label>
              <input type="text" name="documento" value="<?= htmlspecialchars($est['documento']) ?>" required />
            </div>
            <div class="form-group">
              <label>Fecha de Nacimiento *</label>
              <input type="date" name="fecha_nac" value="<?= $est['fecha_nac'] ?>" required />
            </div>
            <div class="form-group">
              <label>Género *</label>
              <select name="genero" required>
                <option value="M" <?= $est['genero']==='M'?'selected':'' ?>>Masculino</option>
                <option value="F" <?= $est['genero']==='F'?'selected':'' ?>>Femenino</option>
                <option value="O" <?= $est['genero']==='O'?'selected':'' ?>>Otro</option>
              </select>
            </div>
            <div class="form-group">
              <label>Teléfono</label>
              <input type="tel" name="telefono" value="<?= htmlspecialchars($est['telefono']) ?>" />
            </div>
          </div>
        </div>

        <div class="form-section">
          <h2 class="section-title">🏫 Datos Académicos</h2>
          <div class="form-grid">
            <div class="form-group">
              <label>Grado *</label>
              <select name="grado" required>
                <?php for ($g=1; $g<=11; $g++): ?>
                  <option value="<?= $g ?>" <?= $est['grado']==$g?'selected':'' ?>><?= $g ?>° <?= $g<=5?'Primaria':($g<=9?'Secundaria':'Media') ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Grupo *</label>
              <select name="grupo" required>
                <?php foreach (['A','B','C'] as $gr): ?>
                  <option value="<?= $gr ?>" <?= $est['grupo']===$gr?'selected':'' ?>><?= $gr ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Año Matrícula *</label>
              <input type="number" name="año_matricula" value="<?= $est['año_matricula'] ?>" min="2020" max="2030" required />
            </div>
            <div class="form-group">
              <label>Estado *</label>
              <select name="estado" required>
                <option value="activo"   <?= $est['estado']==='activo'?'selected':'' ?>>Activo</option>
                <option value="inactivo" <?= $est['estado']==='inactivo'?'selected':'' ?>>Inactivo</option>
                <option value="retirado" <?= $est['estado']==='retirado'?'selected':'' ?>>Retirado</option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-section">
          <h2 class="section-title">👨‍👩‍👦 Datos del Acudiente</h2>
          <div class="form-grid">
            <div class="form-group">
              <label>Nombre del Acudiente *</label>
              <input type="text" name="acudiente" value="<?= htmlspecialchars($est['acudiente']) ?>" required />
            </div>
            <div class="form-group">
              <label>Teléfono Acudiente *</label>
              <input type="tel" name="tel_acudiente" value="<?= htmlspecialchars($est['tel_acudiente']) ?>" required />
            </div>
            <div class="form-group full-width">
              <label>Dirección</label>
              <input type="text" name="direccion" value="<?= htmlspecialchars($est['direccion']) ?>" />
            </div>
          </div>
        </div>

        <div class="form-actions">
          <a href="lista.php" class="btn btn-outline">← Volver</a>
          <button type="submit" class="btn btn-primary">Guardar Cambios ✓</button>
        </div>

      </form>
    </div>
  </main>

  <footer class="footer">
    <p>© 2025 Colegio San Andrés · Sistema de Gestión Estudiantil</p>
  </footer>

</body>
</html>
