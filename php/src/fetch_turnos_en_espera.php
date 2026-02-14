<?php
include 'config.php';

$zona = $_GET['zona'];

// Verificar si el asesor ya está atendiendo a un cliente
session_start();
$asesor_id = $_SESSION['asesor_id'];
$sql_check_current = "SELECT COUNT(*) as active_turns FROM Turno WHERE asesor_identificacion = '$asesor_id' AND estado = 'atendiendo'";
$result_check_current = $conn->query($sql_check_current);
$active_turns = $result_check_current->fetch_assoc()['active_turns'];

$sql = "SELECT * FROM Turno WHERE zona = '$zona' AND estado = 'en_espera' AND asesor_identificacion IS NULL ORDER BY id";
$result = $conn->query($sql);

$output = '';

while ($turno = $result->fetch_assoc()) {
    $output .= '<li class="p-2 bg-white shadow-lg rounded-lg flex justify-between items-center">';
    $output .= 'Turno: ' . htmlspecialchars($turno['numero']);
    
    if ($active_turns > 0) {
        $output .= '<span class="text-gray-500 text-sm">Ya estás atendiendo a un cliente</span>';
    } else {
        $output .= '<form method="post" style="display:inline;">';
        $output .= '<input type="hidden" name="turno_id" value="' . $turno['id'] . '">';
        $output .= '<button type="submit" name="atender" class="bg-blue-500 text-white font-medium py-2 px-6 rounded-lg hover:bg-blue-600 transition duration-200">Atender Cliente</button>';
        $output .= '</form>';
    }
    
    $output .= '</li>';
}

echo $output;
?>
