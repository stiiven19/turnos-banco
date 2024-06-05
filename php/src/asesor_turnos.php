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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asesor Turnos</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $asesor_nombre; ?></h1>
    <h2>Zona: <?php echo getZonaNombreCompleto($zona); ?></h2>
    <?php if ($turno_actual): ?>
        <h3>Turno Actual</h3>
        <p>Turno: <?php echo $turno_actual['numero']; ?></p>
        <p>Cliente ID: <?php echo $turno_actual['cliente_id']; ?></p>
        <form method="post" action="asesor_turnos.php">
            <input type="hidden" name="turno_id" value="<?php echo $turno_actual['id']; ?>">
            <input type="submit" name="finalizar_turno" value="Finalizar Turno">
        </form>
    <?php else: ?>
        <p>Sin asignar</p>
    <?php endif; ?>

    <h3>En Espera</h3>
    <?php if ($result_espera->num_rows > 0): ?>
        <ul>
            <?php while($row = $result_espera->fetch_assoc()): ?>
                <li>
                    Turno: <?php echo $row['numero']; ?>
                    <form method="post" action="asesor_turnos.php" style="display:inline;">
                        <input type="hidden" name="turno_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="atender_turno" value="Atender Cliente" <?php echo $turno_actual ? 'disabled' : ''; ?>>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No hay turnos en espera</p>
    <?php endif; ?>
    <a href="index.php">Volver al inicio</a>
</body>
</html>