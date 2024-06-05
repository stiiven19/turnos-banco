<?php
include 'config.php';

$sql = "SELECT Asesor.nombre AS asesor_nombre, Turno.numero 
        FROM Turno 
        LEFT JOIN Asesor ON Turno.asesor_id = Asesor.id
        WHERE Turno.asesor_id IS NOT NULL";
$result = $conn->query($sql);

$turnos_espera_sql = "SELECT numero FROM Turno WHERE asesor_id IS NULL";
$turnos_espera_result = $conn->query($turnos_espera_sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Turnos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        table {
            border-spacing: 8px;
        }
        th, td {
            padding: 10px;
            border: 3px solid #e2e8f0;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">

    <header class="bg-blue-900 text-white p-6">
        <h1 class="text-4xl font-bold text-center">Lista de Turnos</h1>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <?php if ($result->num_rows > 0): ?>
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Turnos Asignados</h2>
                <table class="w-full">
                    <thead class="bg-blue-900 text-white">
                        <tr>
                            <th>Asesor</th>
                            <th>Turno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="py-2 px-4 text-center"><?php echo $row["asesor_nombre"]; ?></td>
                                <td><?php echo $row["numero"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-lg text-center">No hay turnos asignados a asesores.</p>
        <?php endif; ?>

        <?php if ($turnos_espera_result->num_rows > 0): ?>
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-2">Turnos en Espera</h2>
                <table class="w-full">
                    <thead class="bg-blue-900 text-white">
                        <tr>
                            <th>Turno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $turnos_espera_result->fetch_assoc()): ?>
                            <tr>
                                <td class="py-2 px-4 text-center"><?php echo $row["numero"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-lg text-center">No hay turnos en espera.</p>
        <?php endif; ?>

        <a href="index.php" class="block text-center mt-8 underline">Volver al inicio</a>
    </div>

</body>
</html>

