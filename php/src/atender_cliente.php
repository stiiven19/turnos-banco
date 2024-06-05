<?php
session_start();
include 'config.php';

if (!isset($_SESSION['asesor_id'])) {
    echo "No tienes permiso para realizar esta acción.";
    exit();
}

$asesor_id = $_SESSION['asesor_id'];
$turno_id = $_POST['turno_id'];

$sql = "UPDATE Turno SET asesor_id = '$asesor_id' WHERE id = '$turno_id' AND asesor_id IS NULL";

if ($conn->query($sql) === TRUE) {
    echo "Cliente asignado exitosamente.";
} else {
    echo "Error al asignar cliente: " . $conn->error;
}
?>
