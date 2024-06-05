<?php
session_start();
include 'config.php';

if (!isset($_SESSION['asesor_id'])) {
    header("Location: login_asesor.php");
    exit;
}

$asesor_id = $_SESSION['asesor_id'];
$zona = $_SESSION['zona'];
$asesor_nombre = $_SESSION['asesor_nombre'];

$sql_actual = "SELECT * FROM Turno WHERE asesor_id='$asesor_id' AND zona='$zona'";
$result_actual = $conn->query($sql_actual);
$turno_actual = $result_actual->fetch_assoc();

$sql_espera = "SELECT * FROM Turno WHERE asesor_id IS NULL AND zona='$zona' ORDER BY numero";
$result_espera = $conn->query($sql_espera);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['finalizar_turno'])) {
        $sql = "DELETE FROM Turno WHERE id=".$_POST['turno_id'];
        if ($conn->query($sql) === TRUE) {
            header("Location: asesor_turnos.php");
            exit;
        }
    } elseif (isset($_POST['atender_turno'])) {
        $sql = "UPDATE Turno SET asesor_id='$asesor_id' WHERE id=".$_POST['turno_id'];
        if ($conn->query($sql) === TRUE) {
            header("Location: asesor_turnos.php");
            exit;
        }
    }
}
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

?>

</html> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asesor Turnos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">

    <header class="bg-blue-900 text-white p-6">
        <h1 class="text-4xl font-bold text-center">Bienvenido, <?php echo $asesor_nombre; ?></h1>
        <h2 class="text-2xl text-center">Zona: <?php echo getZonaNombreCompleto($zona); ?></h2>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <?php if ($turno_actual): ?>
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">Turno Actual</h3>
                <p class="mb-2">Turno: <?php echo $turno_actual['numero']; ?></p>
                <p class="mb-4">Cliente ID: <?php echo $turno_actual['cliente_id']; ?></p>
                <form method="post" action="asesor_turnos.php">
                    <input type="hidden" name="turno_id" value="<?php echo $turno_actual['id']; ?>">
                    <input type="submit" name="finalizar_turno" value="Finalizar Turno" class="bg-red-500 text-white font-medium py-2 px-6 rounded-lg hover:bg-red-600 transition duration-200">
                </form>
            </div>
        <?php else: ?>
            <p class="text-lg text-center mb-6">Sin asignar</p>
        <?php endif; ?>

        <h3 class="text-xl font-semibold mb-2">Turnos en Espera</h3>
        <?php if ($result_espera->num_rows > 0): ?>
            <ul class="space-y-4">
                <?php while($row = $result_espera->fetch_assoc()): ?>
                    <li class="bg-gray-100 p-4 rounded-lg shadow-md">
                        <p class="mb-2">Turno: <?php echo $row['numero']; ?></p>
                        <form method="post" action="asesor_turnos.php" style="display:inline;">
                            <input type="hidden" name="turno_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" name="atender_turno" value="Atender Cliente" class="bg-blue-900 text-white font-medium py-2 px-6 rounded-lg hover:bg-blue-800 transition duration-200" <?php echo $turno_actual ? 'disabled' : ''; ?>>
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-lg text-center">No hay turnos en espera</p>
        <?php endif; ?>

        <a href="index.php" class="block text-center mt-8 underline">Volver al inicio</a>
    </div>

</body>
</html>