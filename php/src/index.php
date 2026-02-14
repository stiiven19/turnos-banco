<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Turnos</title>
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
        .menu-item {
            margin-bottom: 10px;
        }
        .menu-link {
            display: block;
            background-color: #FD0604; 
            color: #ffffff;
            font-size: 18px;
            padding: 12px 24px;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .menu-link:hover {
            background-color: #cc0403; 
        }
    </style>
</head>
<body class="bg-gray-200 font-serif leading-normal tracking-normal">

    <header class="titulo">
        <img src="logo.png" alt="Logo" />
        <h1>Gestión de Turnos</h1>
    </header>

    <div class="container mx-auto mt-10 p-6 bg-gray-200 font-serif shadow-lg rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="menu-item">
                <a href="registro_asesor.php" class="menu-link">
                    Registrar Asesor
                </a>
            </div>
            <div class="menu-item">
                <a href="registro_cliente.php" class="menu-link">
                    Registrar Cliente
                </a>
            </div>
            <div class="menu-item">
                <a href="login_asesor.php" class="menu-link">
                    Ingreso Asesor
                </a>
            </div>
            <div class="menu-item">
                <a href="turnos.php" class="menu-link">
                    Ver Turnos
                </a>
            </div>
            <div class="menu-item">
                <a href="registros.php" class="menu-link">
                    Registros
                </a>
            </div>
        </div>
    </div>

</body>
</html>
