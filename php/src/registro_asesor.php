<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $identificacion = $_POST['identificacion'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $zona = $_POST['zona'];
    
    $sql = "INSERT INTO Asesor (nombre, identificacion, password, zona) VALUES ('$nombre', '$identificacion', '$password', '$zona')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo asesor registrado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Asesor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">

    <header class="bg-blue-900 text-white p-6">
        <h1 class="text-4xl font-bold text-center">Registro de Asesor</h1>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-gray-200 font-serif shadow-lg rounded-lg">
        <form method="post" action="registro_asesor.php" class="space-y-4 text-center">
            <label for="nombre" class="block">
                Nombre:
                <input type="text" id="nombre" name="nombre" class="block mx-auto w-64 px-4 py-2 border rounded-md" required>
            </label>
            <label for="identificacion" class="block">
                Identificación:
                <input type="text" id="identificacion" name="identificacion" class="block mx-auto w-64 px-4 py-2 border rounded-md" required>
            </label>
            <label for="password" class="block">
                Contraseña:
                <input type="password" id="password" name="password" class="block mx-auto w-64 px-4 py-2 border rounded-md" required>
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
