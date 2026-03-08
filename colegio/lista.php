<?php
// lista.php - Ver listado de estudiantes
require_once 'php/conexion.php';

$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$mensaje  = isset($_GET['msg']) ? $_GET['msg'] : '';

// Consulta con búsqueda opcional
if ($busqueda !== '') {
    $stmt = $conn->prepare(
        "SELECT * FROM estudiantes 
         WHERE nombres LIKE ? OR apellidos LIKE ? OR documento LIKE ? OR grado LIKE ?
         ORDER BY apellidos ASC"
    );
    $like = "%$busqueda%";
    $stmt->bind_param("ssss", $like, $like, $like, $like);
} else {
    $stmt = $conn->prepare("SELECT * FROM estudiantes ORDER BY apellidos ASC");
}
$stmt->execute();
$resultado = $stmt->get_result();

// Conteo total
$total_q   = $conn->query("SELECT COUNT(*) as total FROM estudiantes");
$total_row = $total_q->fetch_assoc();
$total     = $total_row['total'];

$activos_q   = $conn->query("SELECT COUNT(*) as c FROM estudiantes WHERE estado='activo'");
$activos_row = $activos_q->fetch_assoc();
$activos     = $activos_row['c'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lista de Estudiantes – Colegio San Andrés</title>
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
      <a href="lista.php" class="active">Estudiantes</a>
    </nav>
  </header>

  <main class="list-page">

    <?php if ($mensaje === 'guardado'): ?>
      <div class="alert alert-success">✅ Estudiante registrado exitosamente.</div>
    <?php elseif ($mensaje === 'actualizado'): ?>
      <div class="alert alert-success">✏️ Estudiante actualizado correctamente.</div>
    <?php elseif ($mensaje === 'eliminado'): ?>
      <div class="alert alert-success">🗑️ Estudiante eliminado del sistema.</div>
    <?php endif; ?>

    <div class="list-header">
      <div>
        <h1>Lista de <span class="accent">Estudiantes</span></h1>
        <p style="color:#6b7280;margin-top:0.3rem;">Gestión completa de estudiantes matriculados</p>
      </div>
      <div style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap;">
        <form method="GET" class="search-bar">
          <input type="text" name="buscar" placeholder="🔍 Buscar por nombre, doc, grado..." value="<?= htmlspecialchars($busqueda) ?>" />
          <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
          <?php if ($busqueda): ?>
            <a href="lista.php" class="btn btn-ghost btn-sm">✕ Limpiar</a>
          <?php endif; ?>
        </form>
        <a href="registro.html" class="btn btn-primary btn-sm">+ Nuevo</a>
      </div>
    </div>

    <div class="stats-bar">
      <div class="stat-chip">📊 Total: <strong><?= $total ?></strong></div>
      <div class="stat-chip">✅ Activos: <strong><?= $activos ?></strong></div>
      <div class="stat-chip">❌ Otros: <strong><?= $total - $activos ?></strong></div>
      <?php if ($busqueda): ?>
        <div class="stat-chip">🔍 Resultados: <strong><?= $resultado->num_rows ?></strong></div>
      <?php endif; ?>
    </div>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Documento</th>
            <th>Nombres y Apellidos</th>
            <th>Grado</th>
            <th>Grupo</th>
            <th>Acudiente</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($resultado->num_rows === 0): ?>
            <tr>
              <td colspan="8">
                <div class="empty-state">
                  <div class="empty-icon">🎒</div>
                  <p>No se encontraron estudiantes<?= $busqueda ? " para «$busqueda»" : '' ?>.</p>
                  <a href="registro.html" class="btn btn-primary" style="margin-top:1rem;">Registrar primero</a>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php $i = 1; while ($row = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['documento']) ?></td>
                <td><strong><?= htmlspecialchars($row['apellidos']) ?>, <?= htmlspecialchars($row['nombres']) ?></strong></td>
                <td><?= htmlspecialchars($row['grado']) ?>°</td>
                <td><?= htmlspecialchars($row['grupo']) ?></td>
                <td><?= htmlspecialchars($row['acudiente']) ?></td>
                <td>
                  <span class="badge badge-<?= $row['estado'] ?>">
                    <?= ucfirst($row['estado']) ?>
                  </span>
                </td>
                <td>
                  <div class="actions-cell">
                    <a href="editar.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">✏️ Editar</a>
                    <button onclick="confirmarEliminar(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nombres'].' '.$row['apellidos']) ?>')"
                            class="btn btn-danger btn-sm">🗑️</button>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal confirmación eliminar -->
  <div class="modal-overlay" id="modalEliminar">
    <div class="modal">
      <h3>⚠️ Confirmar Eliminación</h3>
      <p id="modalMsg">¿Estás seguro de que deseas eliminar este estudiante? Esta acción no se puede deshacer.</p>
      <div class="modal-actions">
        <button onclick="cerrarModal()" class="btn btn-ghost">Cancelar</button>
        <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">Sí, eliminar</a>
      </div>
    </div>
  </div>

  <footer class="footer">
    <p>© 2025 Colegio San Andrés · Sistema de Gestión Estudiantil</p>
  </footer>

  <script>
    function confirmarEliminar(id, nombre) {
      document.getElementById('modalMsg').textContent = `¿Deseas eliminar al estudiante "${nombre}"? Esta acción no se puede deshacer.`;
      document.getElementById('btnConfirmarEliminar').href = `php/eliminar.php?id=${id}`;
      document.getElementById('modalEliminar').classList.add('show');
    }
    function cerrarModal() {
      document.getElementById('modalEliminar').classList.remove('show');
    }
    document.getElementById('modalEliminar').addEventListener('click', function(e) {
      if (e.target === this) cerrarModal();
    });
  </script>

</body>
</html>
