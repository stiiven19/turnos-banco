<?php
session_start();
include 'config.php';

if (!isset($_SESSION['asesor_id'])) {
    header('Location: login_asesor.php');
    exit();
}

$asesor_id = $_SESSION['asesor_id'];
$zona = $_SESSION['zona'];

$sql_asesor = "SELECT nombre FROM Asesor WHERE identificacion = '$asesor_id'";
$result_asesor = $conn->query($sql_asesor);
$asesor_nombre = $result_asesor->fetch_assoc()['nombre'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['atender'])) {
        $turno_id = $_POST['turno_id'];
        $fecha_inicio = date('Y-m-d H:i:s');

        // Verificar si el asesor ya está atendiendo a otro cliente
        $sql_check_current = "SELECT COUNT(*) as active_turns FROM Turno WHERE asesor_identificacion = '$asesor_id' AND estado = 'atendiendo'";
        $result_check_current = $conn->query($sql_check_current);
        $active_turns = $result_check_current->fetch_assoc()['active_turns'];

        if ($active_turns > 0) {
            $message = "Ya estás atendiendo a un cliente. Debes finalizar el turno actual antes de tomar otro.";
        } else {
            $sql_check = "SELECT estado FROM Turno WHERE id = '$turno_id' AND estado = 'en_espera' AND asesor_identificacion IS NULL";
            $result_check = $conn->query($sql_check);
            if ($result_check->num_rows > 0) {
                $conn->begin_transaction();
                try {
                    $sql_update = "UPDATE Turno SET estado = 'atendiendo', asesor_identificacion = '$asesor_id' WHERE id = '$turno_id'";
                    $conn->query($sql_update);

                    $sql_insert = "INSERT INTO Asesoria (asesor_identificacion, cliente_identificacion, turno_id, fecha_atencion) 
                                   SELECT '$asesor_id', cliente_identificacion, '$turno_id', '$fecha_inicio' 
                                   FROM Turno 
                                   WHERE id = '$turno_id'";
                    $conn->query($sql_insert);

                    $conn->commit();
                    $_SESSION['turno_en_atencion'] = $turno_id;
                } catch (Exception $e) {
                    $conn->rollback();
                    $message = "Error al comenzar la atención del turno: " . $e->getMessage();
                }
            } else {
                $message = "El turno ya está siendo atendido por otro asesor o ya fue tomado.";
            }
        }
    } elseif (isset($_POST['finalizar'])) {
        $turno_id = $_POST['turno_id'];
        $fecha_fin = date('Y-m-d H:i:s');

        $sql_update = "UPDATE Turno SET estado = 'finalizado' WHERE id = '$turno_id'";
        if ($conn->query($sql_update) === TRUE) {
            $sql_update_asesoria = "UPDATE Asesoria SET duracion = TIMEDIFF('$fecha_fin', fecha_atencion) WHERE turno_id = '$turno_id'";
            if ($conn->query($sql_update_asesoria) === TRUE) {
                unset($_SESSION['turno_en_atencion']);
            } else {
                $message = "Error al registrar la duración de la asesoría: " . $conn->error;
            }
        } else {
            $message = "Error al finalizar el turno: " . $conn->error;
        }
    }
}

$sql_turno_atencion = "SELECT Turno.id, Turno.numero, Cliente.nombre, Cliente.identificacion, Asesoria.fecha_atencion 
                       FROM Turno 
                       INNER JOIN Cliente ON Turno.cliente_identificacion = Cliente.identificacion
                       INNER JOIN Asesoria ON Turno.id = Asesoria.turno_id
                       WHERE Turno.asesor_identificacion = '$asesor_id' AND Turno.estado = 'atendiendo'";
$result_turno_atencion = $conn->query($sql_turno_atencion);
$turno_en_atencion = $result_turno_atencion->fetch_assoc();

$zona_nombres = [
    'A' => 'Trámites Generales (A)',
    'B' => 'Solicitar Documentos (B)',
    'C' => 'Transacciones en Caja (C)',
    'D' => 'Asesorías (D)'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido Asesor <?php echo htmlspecialchars($asesor_nombre); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">
    <header class="bg-blue-900 text-white p-6 flex justify-between items-center">
        <h1 class="text-4xl font-bold">Bienvenido Asesor <?php echo htmlspecialchars($asesor_nombre); ?></h1>
        <div>
            <a href="index.php" class="block text-center text-blue-500 hover:underline mr-4">Volver al inicio</a>
            <a href="logout.php" class="block text-center text-red-500 hover:underline">Cerrar Sesión</a>
        </div>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-gray-200 shadow-lg rounded-lg max-w-md">
        <?php if ($message): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <p class="text-lg">Zona: <?php echo $zona_nombres[$zona]; ?></p>

        <?php if ($turno_en_atencion): ?>
            <h2 class="text-2xl font-bold mt-4">Turno en Atención</h2>
            <p class="text-lg">Cliente: <?php echo htmlspecialchars($turno_en_atencion['nombre']); ?></p>
            <p class="text-lg">Identificación: <?php echo htmlspecialchars($turno_en_atencion['identificacion']); ?></p>
            <p class="text-lg">Turno: <?php echo htmlspecialchars($turno_en_atencion['numero']); ?></p>
            <p class="text-lg">Duración: <span id="contador">0:00:00</span></p>
            <form method="post">
                <input type="hidden" name="turno_id" value="<?php echo $turno_en_atencion['id']; ?>">
                <button type="submit" name="finalizar" class="bg-red-500 text-white font-medium py-2 px-6 rounded-lg hover:bg-red-600 transition duration-200 w-full mt-2">Finalizar Turno</button>
            </form>
        <?php endif; ?>

        <h2 class="text-2xl font-bold mt-4">Turnos en Espera</h2>
        <ul class="space-y-2" id="turnos-en-espera">
        </ul>
    </div>

    <?php if ($turno_en_atencion): ?>
        <script>
            let fechaAtencion = "<?php echo $turno_en_atencion['fecha_atencion']; ?>";

            if (fechaAtencion) {
                let fechaAtencionDate = new Date(fechaAtencion + 'Z');

                function actualizarContador() {
                    let ahora = new Date();

                    let diferencia = ahora.getTime() - fechaAtencionDate.getTime();

                    let segundos = Math.floor((diferencia / 1000) % 60);
                    let minutos = Math.floor((diferencia / (1000 * 60)) % 60);
                    let horas = Math.floor((diferencia / (1000 * 60 * 60)) % 24);

                    segundos = segundos < 10 ? '0' + segundos : segundos;
                    minutos = minutos < 10 ? '0' + minutos : minutos;
                    horas = horas < 10 ? '0' + horas : horas;

                    document.getElementById('contador').textContent = horas + ':' + minutos + ':' + segundos;
                }

                actualizarContador();

                setInterval(actualizarContador, 1000);
            }
        </script>
    <?php endif; ?>

    <script>
        function fetchTurnosEnEspera() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_turnos_en_espera.php?zona=<?php echo $zona; ?>', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('turnos-en-espera').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        setInterval(fetchTurnosEnEspera, 5000);
        fetchTurnosEnEspera();
    </script>
</body>
</html>
