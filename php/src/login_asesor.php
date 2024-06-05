<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificacion = $_POST['identificacion'];
    $password = $_POST['password'];
    $zona = $_POST['zona'];

    $sql = "SELECT * FROM Asesor WHERE identificacion='$identificacion' AND zona='$zona'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['asesor_id'] = $row['id'];
            $_SESSION['asesor_nombre'] = $row['nombre'];
            $_SESSION['zona'] = $zona;
            header("Location: asesor_turnos.php");
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Asesor no encontrado o zona incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Asesor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">

    <header class="bg-blue-900 text-white p-6">
        <h1 class="text-4xl font-bold text-center">Login Asesor</h1>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-gray-200 shadow-lg rounded-lg max-w-md">
        <form method="post" action="login_asesor.php" class="space-y-4 text-center">
            <label for="identificacion" class="block">
                Identificación:
                <input type="text" id="identificacion" name="identificacion" class="block mx-auto w-full px-4 py-2 border rounded-md" required>
            </label>
            <label for="password" class="block">
                Contraseña:
                <input type="password" id="password" name="password" class="block mx-auto w-full px-4 py-2 border rounded-md" required>
            </label>
            <label for="zona" class="block">
                Zona:
                <select name="zona" id="zona" class="block mx-auto w-full px-4 py-2 border rounded-md" required>
                    <option value="A">Trámites Generales</option>
                    <option value="B">Solicitar Documentos</option>
                    <option value="C">Transacciones en Caja</option>
                    <option value="D">Asesorías</option>
                </select>
            </label>
            <input type="submit" value="Login" class="bg-blue-900 text-white font-medium py-2 px-6 rounded-lg hover:bg-blue-800 transition duration-200 w-full">
        </form>
        <a href="index.php" class="block text-center mt-4">Volver al inicio</a>
    </div>

</body>
</html>
