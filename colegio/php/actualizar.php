<?php
// php/actualizar.php – Actualiza un estudiante existente
require_once 'conexion.php';

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../lista.php");
    exit;
}

// Recibir y sanitizar datos
$id             = intval($_POST['id'] ?? 0);
$nombres        = trim($_POST['nombres'] ?? '');
$apellidos      = trim($_POST['apellidos'] ?? '');
$documento      = trim($_POST['documento'] ?? '');
$fecha_nac      = trim($_POST['fecha_nac'] ?? '');
$genero         = trim($_POST['genero'] ?? '');
$telefono       = trim($_POST['telefono'] ?? '');
$grado          = intval($_POST['grado'] ?? 0);
$grupo          = trim($_POST['grupo'] ?? '');
$año_matricula  = intval($_POST['año_matricula'] ?? date('Y'));
$estado         = trim($_POST['estado'] ?? 'activo');
$acudiente      = trim($_POST['acudiente'] ?? '');
$tel_acudiente  = trim($_POST['tel_acudiente'] ?? '');
$direccion      = trim($_POST['direccion'] ?? '');

// Validar campos obligatorios
if ($id <= 0 || empty($nombres) || empty($apellidos)) {
    die("❌ Datos inválidos. <a href='../lista.php'>Volver</a>");
}

// Preparar la consulta (nota: `año_matricula` entre backticks)
$stmt = $conn->prepare(
    "UPDATE estudiantes SET 
        nombres=?, apellidos=?, documento=?, fecha_nac=?, genero=?, telefono=?,
        grado=?, grupo=?, `año_matricula`=?, estado=?, acudiente=?, tel_acudiente=?, direccion=?
     WHERE id=?"
);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Vincular parámetros (14 variables → 14 tipos)
$stmt->bind_param(
    "ssssssisissssi",
    $nombres, $apellidos, $documento, $fecha_nac, $genero, $telefono,
    $grado, $grupo, $año_matricula, $estado, $acudiente, $tel_acudiente, $direccion, $id
);

// Ejecutar y manejar resultado
if ($stmt->execute()) {
    header("Location: ../lista.php?msg=actualizado");
} else {
    die("❌ Error al actualizar: " . $stmt->error . "<br><a href='../editar.php?id=$id'>Volver</a>");
}

exit;
?>
