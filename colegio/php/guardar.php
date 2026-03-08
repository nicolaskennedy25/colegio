<?php
// php/guardar.php – Inserta un nuevo estudiante
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../registro.html");
    exit;
}

// Recoger y limpiar datos
$nombres       = trim($_POST['nombres']       ?? '');
$apellidos     = trim($_POST['apellidos']     ?? '');
$documento     = trim($_POST['documento']     ?? '');
$fecha_nac     = trim($_POST['fecha_nac']     ?? '');
$genero        = trim($_POST['genero']        ?? '');
$telefono      = trim($_POST['telefono']      ?? '');
$grado         = intval($_POST['grado']       ?? 0);
$grupo         = trim($_POST['grupo']         ?? '');
$año_matricula = intval($_POST['año_matricula'] ?? date('Y'));
$estado        = trim($_POST['estado']        ?? 'activo');
$acudiente     = trim($_POST['acudiente']     ?? '');
$tel_acudiente = trim($_POST['tel_acudiente'] ?? '');
$direccion     = trim($_POST['direccion']     ?? '');

// Validación básica
if (empty($nombres) || empty($apellidos) || empty($documento)) {
    die("❌ Faltan campos obligatorios. <a href='../registro.html'>Volver</a>");
}

$stmt = $conn->prepare(
    "INSERT INTO estudiantes
     (nombres, apellidos, documento, fecha_nac, genero, telefono,
      grado, grupo, año_matricula, estado, acudiente, tel_acudiente, direccion)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
    "ssssssisssss s",
    $nombres, $apellidos, $documento, $fecha_nac, $genero, $telefono,
    $grado, $grupo, $año_matricula, $estado, $acudiente, $tel_acudiente, $direccion
);

if ($stmt->execute()) {
    header("Location: ../lista.php?msg=guardado");
} else {
    header("Location: ../registro.html?error=1");
}
exit;
?>
