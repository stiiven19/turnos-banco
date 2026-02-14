<?php
// Configuración de la conexión a la base de datos
include 'config.php';
// Crear la conexión

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener la fecha de atención
$sql = "SELECT fecha_atencion FROM Asesoria WHERE duracion IS NULL LIMIT 1";
$result = $conn->query($sql);

$fecha_atencion_js = '';

if ($result->num_rows > 0) {
    // Obtener la primera fila del resultado
    $row = $result->fetch_assoc();
    $fecha_atencion_js = date('Y-m-d\TH:i:s', strtotime($row['fecha_atencion']));
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contador de Tiempo</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        #contador {
            font-size: 48px;
        }
    </style>
</head>
<body>
    <h1>Contador de Tiempo desde la Fecha de Atención</h1>
    <div id="contador">
        <?php
        if ($fecha_atencion_js) {
            echo '<script>let fechaAtencion = "' . $fecha_atencion_js . '";</script>';
        } else {
            echo 'No hay fecha de atención definida.';
        }
        ?>
    </div>

    <script>
        if (typeof fechaAtencion !== 'undefined') {
            // Convertir la fecha de atención a un objeto de fecha JavaScript en la zona horaria local
            let fechaAtencionDate = new Date(fechaAtencion + 'Z'); // Añadir 'Z' para asegurarse de que está en UTC

            // Función para actualizar el contador
            function actualizarContador() {
                // Obtener la fecha actual en la zona horaria local
                let ahora = new Date();

                // Calcular la diferencia en milisegundos
                let diferencia = ahora.getTime() - fechaAtencionDate.getTime();

                // Convertir la diferencia de milisegundos a horas, minutos y segundos
                let segundos = Math.floor((diferencia / 1000) % 60);
                let minutos = Math.floor((diferencia / (1000 * 60)) % 60);
                let horas = Math.floor((diferencia / (1000 * 60 * 60)) % 24);

                // Formatear los valores para que siempre tengan dos dígitos (por ejemplo, 01 en lugar de 1)
                segundos = segundos < 10 ? '0' + segundos : segundos;
                minutos = minutos < 10 ? '0' + minutos : minutos;
                horas = horas < 10 ? '0' + horas : horas;

                // Actualizar el elemento en el DOM con el contador
                document.getElementById('contador').textContent = horas + ':' + minutos + ':' + segundos;
            }

            // Llamar a la función inicialmente para iniciar el contador
            actualizarContador();

            // Actualizar el contador cada segundo (1000 milisegundos)
            setInterval(actualizarContador, 1000);
        }
    </script>
</body>
</html>