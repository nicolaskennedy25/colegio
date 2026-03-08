<?php
// php/actualizar.php – Actualiza un estudiante existente
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../lista.php");
    exit;
}

$id            = intval($_POST['id']           ?? 0);
$nombres       = trim($_POST['nombres']        ?? '');
$apellidos     = trim($_POST['apellidos']      ?? '');
$documento     = trim($_POST['documento']      ?? '');
$fecha_nac     = trim($_POST['fecha_nac']      ?? '');
$genero        = trim($_POST['genero']         ?? '');
$telefono      = trim($_POST['telefono']       ?? '');
$grado         = intval($_POST['grado']        ?? 0);
$grupo         = trim($_POST['grupo']          ?? '');
$año_matricula = intval($_POST['año_matricula'] ?? date('Y'));
$estado        = trim($_POST['estado']         ?? 'activo');
$acudiente     = trim($_POST['acudiente']      ?? '');
$tel_acudiente = trim($_POST['tel_acudiente']  ?? '');
$direccion     = trim($_POST['direccion']      ?? '');

if ($id <= 0 || empty($nombres) || empty($apellidos)) {
    die("❌ Datos inválidos. <a href='../lista.php'>Volver</a>");
}

$stmt = $conn->prepare(
    "UPDATE estudiantes SET
     nombres=?, apellidos=?, documento=?, fecha_nac=?, genero=?, telefono=?,
     grado=?, grupo=?, año_matricula=?, estado=?, acudiente=?, tel_acudiente=?, direccion=?
     WHERE id=?"
);
$stmt->bind_param(
    "ssssssisssssi",
    $nombres, $apellidos, $documento, $fecha_nac, $genero, $telefono,
    $grado, $grupo, $año_matricula, $estado, $acudiente, $tel_acudiente, $direccion, $id
);

if ($stmt->execute()) {
    header("Location: ../lista.php?msg=actualizado");
} else {
    header("Location: ../editar.php?id=$id&error=1");
}
exit;
?>
