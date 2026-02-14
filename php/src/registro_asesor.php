<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $identificacion = $_POST['identificacion'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $zona = $_POST['zona'];

    $sql = "INSERT INTO Asesor (nombre, identificacion, password, zona) VALUES ('$nombre', '$identificacion', '$password', '$zona')";
    if ($conn->query($sql) === TRUE) {
        echo "Asesor registrado exitosamente.";
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
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .titulo {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            background-color: #FD0604; 
            color: #ffffff;
            font-size: 24px;
            padding: 16px;
            margin-bottom: 20px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .titulo img {
            max-width: 120px; 
            margin-right: 10px;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .form-submit {
            background-color: #FD0604;
            color: #ffffff;
            font-size: 18px;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-submit:hover {
            background-color: #cc0403; 
        }
    </style>
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">

    <header class="titulo">
        <img src="logo.png" alt="Logo" />
        <h1>Registro de Asesor</h1>
    </header>

    <div class="container mx-auto p-6 bg-gray-200 rounded-lg">
        <form method="post" action="registro_asesor.php" class="space-y-4">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-input" required><br>
            <label for="identificacion" class="form-label">Identificación:</label>
            <input type="text" id="identificacion" name="identificacion" class="form-input" required><br>
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" id="password" name="password" class="form-input" required><br>
            <label for="zona" class="form-label">Zona:</label>
            <select name="zona" id="zona" class="form-input" required>
                <option value="A">Trámites Generales (A)</option>
                <option value="B">Solicitar Documentos (B)</option>
                <option value="C">Transacciones en Caja (C)</option>
                <option value="D">Asesorías (D)</option>
            </select><br>
            <input type="submit" value="Registrar" class="form-submit">
        </form>
        <a href="index.php">Volver al inicio</a>
    </div>

</body>
</html>
