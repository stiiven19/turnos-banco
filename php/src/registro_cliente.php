<?php
session_start();
include 'config.php';

function verificarCliente($conn, $identificacion) {
    $sql_verificar_cliente = "SELECT * FROM Cliente WHERE identificacion='$identificacion'";
    $result_verificar_cliente = $conn->query($sql_verificar_cliente);
    
    if ($result_verificar_cliente->num_rows > 0) {
        return $result_verificar_cliente->fetch_assoc();
    } else {
        return null;
    }
}

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

    if (!ctype_digit($identificacion)) {
        $_SESSION['mensaje'] = "La identificación debe contener solo números.";
        header("Location: registro_cliente.php");
        exit;
    }

    $sql_verificar_turno = "SELECT * FROM Turno WHERE cliente_identificacion='$identificacion' AND estado != 'finalizado'";
    $result_verificar_turno = $conn->query($sql_verificar_turno);

    if ($result_verificar_turno->num_rows > 0) {
        $_SESSION['mensaje'] = "El cliente ya tiene un turno asignado.";
    } else {
        $sql_verificar_cliente = "SELECT * FROM Cliente WHERE identificacion='$identificacion'";
        $result_verificar_cliente = $conn->query($sql_verificar_cliente);

        if ($result_verificar_cliente->num_rows > 0) {
            $row = $result_verificar_cliente->fetch_assoc();
            $cliente_nombre = $row['nombre'];

            $turno = generarTurno($conn, $zona);
            $cliente_id = $row['identificacion'];
            $sql_turno = "INSERT INTO Turno (numero, cliente_identificacion, zona, estado) VALUES ('$turno', '$cliente_id', '$zona', 'en_espera')";
            if ($conn->query($sql_turno) === TRUE) {
                $_SESSION['mensaje'] = "Cliente $cliente_nombre encontrado. Se le asignó el turno $turno.";
            } else {
                $_SESSION['mensaje'] = "Error al asignar el turno: " . $conn->error;
            }
        } else {
            $sql_cliente = "INSERT INTO Cliente (nombre, identificacion) VALUES ('$nombre', '$identificacion')";
            if ($conn->query($sql_cliente) === TRUE) {
                $turno = generarTurno($conn, $zona);
                $sql_turno = "INSERT INTO Turno (numero, cliente_identificacion, zona, estado) VALUES ('$turno', '$identificacion', '$zona', 'en_espera')";
                if ($conn->query($sql_turno) === TRUE) {
                    $_SESSION['mensaje'] = "Cliente $nombre registrado. Se le asignó el turno $turno.";
                } else {
                    $_SESSION['mensaje'] = "Error al asignar el turno: " . $conn->error;
                }
            } else {
                $_SESSION['mensaje'] = "Error al registrar el cliente: " . $conn->error;
            }
        }
    }
    header("Location: registro_cliente.php");
    exit;
}

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
    <script>
        function mostrarAlerta(mensaje) {
            alert(mensaje);
        }

        document.addEventListener("DOMContentLoaded", function() {
            var mensaje = "<?php echo $mensaje; ?>";
            if (mensaje) {
                mostrarAlerta(mensaje);
            }
        });
    </script>
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
        .boton-registrar {
            background-color: #FD0604;
            color: #ffffff;
            font-size: 18px;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .boton-registrar:hover {
            background-color: #cc0403;
        }
        .pautas {
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .pautas ul {
            margin-left: 20px;
            list-style-type: disc;
        }
        .pautas li {
            margin-bottom: 6px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

    <header class="titulo">
        <img src="logo.png" alt="Logo" />
        <h1>Creando un Turno</h1>
    </header>

    <div class="container mx-auto p-6 bg-gray-200 rounded-lg">
        <form method="post" action="registro_cliente.php" class="space-y-4">
            <label for="nombre" class="block">
                Nombre completo:
                <input type="text" id="nombre" name="nombre" required class="block w-full px-4 py-2 border rounded-md">
            </label>
            <label for="identificacion" class="block">
                Identificación (solo números):
                <input type="text" id="identificacion" name="identificacion" pattern="[0-9]*" required class="block w-full px-4 py-2 border rounded-md">
            </label>
            <label for="zona" class="block">
                Zona a la que se dirige del banco:
                <select name="zona" id="zona" required class="block w-full px-4 py-2 border rounded-md">
                    <option value="A">Trámites Generales (A)</option>
                    <option value="B">Solicitar Documentos (B)</option>
                    <option value="C">Transacciones en Caja (C)</option>
                    <option value="D">Asesorías (D)</option>
                </select>
            </label>
            <input type="submit" value="Registrar" class="boton-registrar w-full">
        </form>
        
        <div class="pautas">
            <p>Está registrando sus datos para recibir un turno:</p>
            <ul>
                <li>Digite su nombre completo.</li>
                <li>Digite su número de identificación (solo números).</li>
                <li>Seleccione la zona a la que se dirige del banco.</li>
            </ul>
        </div>
        
        <a href="index.php" class="block text-center mt-4">Volver al inicio</a>
    </div>

</body>
</html>
