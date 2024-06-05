<?php
session_start();
include 'config.php';

if (!isset($_SESSION['asesor_id'])) {
    echo "No tienes permiso para realizar esta acción.";
    exit();
}

$asesor_id = $_SESSION['asesor_id'];
$turno_id = $_POST['turno_id'];

$sql_cliente = "SELECT cliente_id FROM Turno WHERE id='$turno_id' AND asesor_id='$asesor_id'";
$result_cliente = $conn->query($sql_cliente);

if ($result_cliente->num_rows > 0) {
    $row_cliente = $result_cliente->fetch_assoc();
    $cliente_id = $row_cliente['cliente_id'];

    $sql_turno = "DELETE FROM Turno WHERE id='$turno_id'";
    $sql_cliente_del = "DELETE FROM Cliente WHERE id='$cliente_id'";

    if ($conn->query($sql_turno) === TRUE && $conn->query($sql_cliente_del) === TRUE) {
        echo "Turno y cliente finalizados y eliminados exitosamente.";
    } else {
        echo "Error al finalizar el turno: " . $conn->error;
    }
} else {
    echo "Turno no encontrado o no autorizado.";
}
?>
