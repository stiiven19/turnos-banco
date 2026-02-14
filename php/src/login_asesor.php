<?php
include 'config.php';
session_start();

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificacion = $_POST['identificacion'];
    $password = $_POST['password'];
    $zona = $_POST['zona'];

    $sql = "SELECT * FROM Asesor WHERE identificacion='$identificacion' AND zona='$zona'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['asesor_id'] = $row['identificacion'];
            $_SESSION['asesor_nombre'] = $row['nombre'];
            $_SESSION['zona'] = $zona;
            
            header("Location: asesor_turnos.php");
            exit(); 
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Asesor no encontrado o zona incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Asesor</title>
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
            border-top-left-radius: 8px; 
            border-top-right-radius: 8px; 
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
        .error-message {
            color: #e53e3e; 
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

    <header class="titulo">
        <img src="logo.png" alt="Logo">
        <h1>Login Asesor</h1>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-gray-200 shadow-lg rounded-lg max-w-md">
        <form method="post" action="login_asesor.php" class="space-y-4 text-center">
            <label for="identificacion" class="block">
                Identificación:
                <input type="text" id="identificacion" name="identificacion" class="form-input" required>
            </label>
            <label for="password" class="block">
                Contraseña:
                <input type="password" id="password" name="password" class="form-input" required>
            </label>
            <label for="zona" class="block">
                Zona:
                <select name="zona" id="zona" class="form-input" required>
                    <option value="A">Trámites Generales</option>
                    <option value="B">Solicitar Documentos</option>
                    <option value="C">Transacciones en Caja</option>
                    <option value="D">Asesorías</option>
                </select>
            </label>
            <input type="submit" value="Login" class="form-submit">
        </form>
        <?php if ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <a href="index.php" class="block text-center mt-4">Volver al inicio</a>
    </div>

</body>
</html>
