<?php
// php/eliminar.php – Elimina un estudiante por ID
require_once 'conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: ../lista.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM estudiantes WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../lista.php?msg=eliminado");
} else {
    header("Location: ../lista.php?msg=error");
}
exit;
?>
