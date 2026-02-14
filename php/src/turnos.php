<?php
include 'config.php';

$sql_turnos_asignados = "SELECT Asesor.nombre AS asesor_nombre, Turno.numero 
                        FROM Turno 
                        LEFT JOIN Asesor ON Turno.asesor_identificacion = Asesor.identificacion
                        WHERE Turno.estado = 'atendiendo'";
$result_turnos_asignados = $conn->query($sql_turnos_asignados);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Turnos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        overflow: hidden; 
        background-color: #ffffff; 
    }
    .container {
        display: flex;
        height: 100vh;
    }
    .logo {
        width: 50%;
        background-color: #007BFF;
        display: flex;
        justify-content: center;
        padding: 0px;
    }
    .logo img {
        max-width: 100%; 
        max-height: 100%;
    }
    .turnos {
        width: 60%;
        background-color: #ffffff;
        overflow: hidden; 
        display: flex;
        flex-direction: column; 
        justify-content: center;
        align-items: center;
    }
    .tabla-turnos {
        width: 100%; 
        max-height: 80%; 
        overflow-y: auto; 
        border-collapse: separate;
        border-spacing: 3px; 
        border-radius: 12px; 
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); 
        font-size: 35px; 
        table-layout: fixed; 
    }
    .tabla-turnos th, .tabla-turnos td {
        padding: 12px; 
        text-align: center;
        background-color: #25A7E1; 
        color: white; 
        border-radius: 8px; 
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis; 
    }
    .tabla-turnos th {
        font-weight: bold; 
    }
    .tabla-turnos td {
        font-weight: normal; 
    }
    
</style>

</head>
<body>

<div class="container">
    <div class="logo">
        <img src="logo-banco.png" alt="Logo del banco">
    </div>
    <div class="turnos">
        <?php if ($result_turnos_asignados->num_rows > 0): ?>
            <table class="tabla-turnos">
                <thead>
                    <tr>
                        <th colspan="2">Turnos Asignados</th>
                    </tr>
                    <tr>
                        <th>Asesor</th>
                        <th>Turno</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result_turnos_asignados->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["asesor_nombre"]; ?></td>
                            <td><?php echo $row["numero"]; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-lg text-center py-8">No hay turnos siendo atendidos actualmente.</p>
        <?php endif; ?>
    </div>
</div>

<meta http-equiv="refresh" content="6">

<script>
    setTimeout(function () {
        window.location.href = 'turnos2.php';
    }, 6000);
</script>

</body>
</html>
