<?php
function getZonaNombreCompleto($zona) {
    switch ($zona) {
        case 'A':
            return 'Trámites Generales (A)';
        case 'B':
            return 'Solicitar Documentos (B)';
        case 'C':
            return 'Transacciones en Caja (C)';
        case 'D':
            return 'Asesorías (D)';
        default:
            return 'Zona Desconocida';
    }
}

function generarTurno($conn, $zona) {
    $sql = "SELECT MAX(CAST(SUBSTRING(numero, 2) AS UNSIGNED)) AS max_turno FROM Turno WHERE zona = '$zona'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['max_turno']) {
        $number = intval($row['max_turno']);
        
        if ($number < 99) {
            $number++;
        } else {
            $number = 1;
        }
        
        return $zona . str_pad($number, 2, '0', STR_PAD_LEFT);
    } else {
        return $zona . '01';
    }
}

function verificarCliente($conn, $identificacion) {
    $sql_verificar_cliente = "SELECT * FROM Cliente WHERE identificacion='$identificacion'";
    $result_verificar_cliente = $conn->query($sql_verificar_cliente);
    
    if ($result_verificar_cliente->num_rows > 0) {
        return $result_verificar_cliente->fetch_assoc();
    } else {
        return null;
    }
}
?>
