<?php
include 'config.php';

$sql_turnos_en_espera = "
    SELECT 
        CASE 
            WHEN SUBSTRING(numero, 1, 1) = 'A' THEN 'A'
            WHEN SUBSTRING(numero, 1, 1) = 'B' THEN 'B'
            WHEN SUBSTRING(numero, 1, 1) = 'C' THEN 'C'
            WHEN SUBSTRING(numero, 1, 1) = 'D' THEN 'D'
            ELSE 'Otros'
        END AS zona,
        numero
    FROM Turno 
    WHERE estado = 'en_espera'
    ORDER BY zona, numero
";

$result_turnos_en_espera = $conn->query($sql_turnos_en_espera);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Turnos en Espera</title>
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
    .turnos {
        width: 100%;
        background-color: #ffffff;
        display: flex;
        flex-direction: column; 
        justify-content: center;
        align-items: center;
        padding: 20px; 
        border-radius: 12px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); 
        font-size: 35px; 
    }
    .tabla-turnos {
        width: 100%; 
        max-height: 80%; 
        overflow-y: auto; 
        border-collapse: separate; 
        border-spacing: 3px; 
        border-radius: 12px; 
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); 
        font-size: 40px; 
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
    .arrow-down {
        position: relative;
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 10px solid #25A7E1; 
        margin: 0 auto; 
        margin-top: 5px; 
    }
</style>
</head>
<body>

<div class="container">
    <div class="turnos">
        <table class="tabla-turnos">
            <thead>
                <tr>
                    <th colspan="4">Turnos en Espera</th>
                </tr>
                <tr>
                    <th>Zona A</th>
                    <th>Zona B</th>
                    <th>Zona C</th>
                    <th>Zona D</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $turnos_zona = [
                    'A' => [],
                    'B' => [],
                    'C' => [],
                    'D' => []
                ];

                while ($row = $result_turnos_en_espera->fetch_assoc()) {
                    $zona = substr($row['numero'], 0, 1);
                    if (array_key_exists($zona, $turnos_zona)) {
                        $turnos_zona[$zona][] = $row['numero'];
                    }
                }

                $max_turnos_por_zona = 5; 

                for ($i = 0; $i < $max_turnos_por_zona; $i++) {
                    echo '<tr>';
                    foreach ($turnos_zona as $zona => $turnos) {
                        if (isset($turnos[$i])) {
                            echo '<td>' . $turnos[$i] . '</td>';
                        } else {
                            echo '<td></td>'; 
                        }
                    }
                    echo '</tr>';
                }

                $mostrar_flecha = false;
                foreach ($turnos_zona as $zona => $turnos) {
                    if (count($turnos) > $max_turnos_por_zona) {
                        $mostrar_flecha = true;
                        break;
                    }
                }

                if ($mostrar_flecha) {
                    echo '<tr>';
                    echo '<td colspan="4" class="arrow-down"></td>'; 
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<meta http-equiv="refresh" content="3">

<script>
    setTimeout(function () {
        window.location.href = 'turnos.php'; 
    }, 3000);
</script>

</body>
</html>
