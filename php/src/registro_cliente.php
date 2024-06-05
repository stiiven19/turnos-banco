<?php
include 'config.php';

function generarTurno($conn, $zona) {
    $sql = "SELECT numero FROM Turno WHERE zona='$zona' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    $lastTurn = $result->fetch_assoc();
    
    if ($lastTurn) {
        $lastTurn = $lastTurn['numero'];
        $number = intval(substr($lastTurn, 1));
        
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $identificacion = $_POST['identificacion'];
    $zona = $_POST['zona'];
    $turno = generarTurno($conn, $zona);
    
    $sql_cliente = "INSERT INTO Cliente (nombre, identificacion, zona) VALUES ('$nombre', '$identificacion', '$zona')";
    if ($conn->query($sql_cliente) === TRUE) {
        $cliente_id = $conn->insert_id;
        
        $sql_turno = "INSERT INTO Turno (numero, cliente_id, zona) VALUES ('$turno', '$cliente_id', '$zona')";
        if ($conn->query($sql_turno) === TRUE) {
            $mensaje = "Cliente registrado exitosamente con turno $turno";
        } else {
            $mensaje = "Error al registrar el turno: " . $conn->error;
        }
    } else {
        $mensaje = "Error al registrar el cliente: " . $conn->error;
    }
}
?>

<<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">

    <header class="bg-blue-900 text-white p-6">
        <h1 class="text-4xl font-bold text-center">Registro de Cliente</h1>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-gray-200 shadow-lg rounded-lg">
        <?php if (isset($mensaje)): ?>
            <p class="text-center text-green-500 font-medium"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form method="post" action="registro_cliente.php" class="space-y-4 text-center">
            <label for="nombre" class="block">
                Nombre:
                <input type="text" id="nombre" name="nombre" class="block mx-auto w-64 px-4 py-2 border rounded-md" required>
            </label>
            <label for="identificacion" class="block">
                Identificación:
                <input type="text" id="identificacion" name="identificacion" class="block mx-auto w-64 px-4 py-2 border rounded-md" required>
            </label>
            <label for="zona" class="block">
                Zona:
                <select name="zona" id="zona" class="block mx-auto w-64 px-4 py-2 border rounded-md" required>
                    <option value="A">Trámites Generales</option>
                    <option value="B">Solicitar Documentos</option>
                    <option value="C">Transacciones en Caja</option>
                    <option value="D">Asesorías</option>
                </select>
            </label>
            <input type="submit" value="Registrar" class="bg-blue-900 text-white font-medium py-2 px-6 rounded-lg hover:bg-blue-800 transition duration-200">
        </form>
        <a href="index.php" class="block text-center mt-4">Volver al inicio</a>
    </div>

</body>
</html>
