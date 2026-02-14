<?php
include 'config.php';

$zona_nombres = [
    'A' => 'Trámites Generales',
    'B' => 'Solicitar Documentos',
    'C' => 'Transacciones en Caja',
    'D' => 'Asesorías'
];

function buscarAsesor($conn, $identificacion) {
    $sql = "SELECT * FROM Asesor WHERE identificacion = '$identificacion'";
    return $conn->query($sql)->fetch_assoc();
}

function buscarCliente($conn, $identificacion) {
    $sql = "SELECT * FROM Cliente WHERE identificacion = '$identificacion'";
    return $conn->query($sql)->fetch_assoc();
}

function obtenerAsesoriasCompletadasPorAsesor($conn, $asesor_id) {
    $sql = "SELECT A.fecha_atencion, A.duracion, C.identificacion AS cliente_identificacion, C.nombre AS cliente_nombre, zona 
            FROM Asesoria A JOIN Cliente C ON A.cliente_identificacion = C.identificacion JOIN Turno B ON B.cliente_identificacion = A.cliente_identificacion 
            WHERE A.asesor_identificacion = '$asesor_id' AND A.duracion IS NOT NULL;";
    return $conn->query($sql);
}

function obtenerAsesoriasEnCursoPorAsesor($conn, $asesor_id) {
    $sql = "SELECT A.fecha_atencion, A.duracion, C.identificacion AS cliente_identificacion, C.nombre AS cliente_nombre, zona
            FROM Asesoria A JOIN Cliente C ON A.cliente_identificacion = C.identificacion JOIN Turno B ON B.cliente_identificacion = A.cliente_identificacion 
            WHERE A.asesor_identificacion = '$asesor_id' AND A.duracion IS NULL;";
    return $conn->query($sql);
}

function obtenerAsesoriasCompletadasPorCliente($conn, $cliente_id) {
    $sql = "SELECT A.fecha_atencion, A.duracion, C.identificacion AS cliente_identificacion, C.nombre AS cliente_nombre, zona
            FROM Asesoria A JOIN Cliente C ON A.cliente_identificacion = C.identificacion JOIN Turno B ON B.cliente_identificacion = A.cliente_identificacion 
            WHERE A.cliente_identificacion = '$cliente_id' AND A.duracion IS NOT NULL;";
    return $conn->query($sql);
}

function obtenerAsesoriasEnCursoPorCliente($conn, $cliente_id) {
    $sql = "SELECT A.fecha_atencion, A.duracion, C.identificacion AS cliente_identificacion, C.nombre AS cliente_nombre, zona
            FROM Asesoria A JOIN Cliente C ON A.cliente_identificacion = C.identificacion JOIN Turno B ON B.cliente_identificacion = A.cliente_identificacion 
            WHERE A.cliente_identificacion = '$cliente_id' AND A.duracion IS NULL;";
    return $conn->query($sql);
}

$busqueda = null;
$resultados_completados = null;
$resultados_en_curso = null;
$tipo_busqueda = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificacion = $_POST['identificacion'];
    $tipo = $_POST['tipo'];
    $tipo_busqueda = $tipo;

    if ($tipo === 'asesor') {
        $busqueda = buscarAsesor($conn, $identificacion);
        if ($busqueda) {
            $resultados_completados = obtenerAsesoriasCompletadasPorAsesor($conn, $busqueda['identificacion']);
            $resultados_en_curso = obtenerAsesoriasEnCursoPorAsesor($conn, $busqueda['identificacion']);
        }
    } elseif ($tipo === 'cliente') {
        $busqueda = buscarCliente($conn, $identificacion);
        if ($busqueda) {
            $resultados_completados = obtenerAsesoriasCompletadasPorCliente($conn, $busqueda['identificacion']);
            $resultados_en_curso = obtenerAsesoriasEnCursoPorCliente($conn, $busqueda['identificacion']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Buscar Registros</h1>
    <form method="post" action="registros.php">
        <div>
            <h2>Buscar por Cliente</h2>
            <label for="identificacion_cliente">Identificación:</label>
            <input type="text" id="identificacion_cliente" name="identificacion">
            <input type="hidden" name="tipo" value="cliente">
            <button type="submit">Buscar</button>
        </div>
        <div>
            <h2>Buscar por Asesor</h2>
            <label for="identificacion_asesor">Identificación:</label>
            <input type="text" id="identificacion_asesor" name="identificacion">
            <input type="hidden" name="tipo" value="asesor">
            <button type="submit">Buscar</button>
        </div>
    </form>

    <p><a href="index.php">Volver al inicio</a></p>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if ($busqueda): ?>
            <h2><?php echo ucfirst($tipo_busqueda); ?> Encontrado</h2>
            <p>Nombre: <?php echo $busqueda['nombre']; ?></p>
            <p>Identificación: <?php echo $busqueda['identificacion']; ?></p>
            <?php if ($tipo_busqueda === 'asesor'): ?>
                <p>Zona: <?php  echo $zona_nombres[$busqueda['zona']]; ?> (<?php echo $busqueda['zona']; ?>)</p>
            <?php endif; ?>

            <?php if ($resultados_completados && $resultados_completados->num_rows > 0): ?>
                <p>Zona: <?php  echo ucfirst('si entro a la busqueda');?></p>
                <h3>Historial de Asesorías Completadas</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Identificación Cliente</th>
                            <th>Nombre Cliente</th>
                            <th>Zona</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultados_completados->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['fecha_inicio']; ?></td>
                                <td><?php echo $row['fecha_fin']; ?></td>
                                <td><?php echo $row['cliente_identificacion']; ?></td>
                                <td><?php echo $row['cliente_nombre']; ?></td>
                                <td><?php echo $zona_nombres[$row['zona']]; ?> (<?php echo $row['zona']; ?>)</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron asesorías completadas para este <?php echo $tipo_busqueda; ?>.</p>
            <?php endif; ?>

            <?php if ($resultados_en_curso && $resultados_en_curso->num_rows > 0): ?>
                <h3>Asesorías En Curso</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha Inicio</th>
                            <th>Estado</th>
                            <th>Identificación Cliente</th>
                            <th>Nombre Cliente</th>
                            <th>Zona</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultados_en_curso->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['fecha_inicio']; ?></td>
                                <td>En Curso</td>
                                <td><?php echo $row['cliente_identificacion']; ?></td>
                                <td><?php echo $row['cliente_nombre']; ?></td>

                                <td><?php echo $zona_nombres[$row['zona']]; ?> (<?php echo $row['zona']; ?>)</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if ((!$resultados_completados || $resultados_completados->num_rows === 0) && (!$resultados_en_curso || $resultados_en_curso->num_rows === 0)): ?>
                <p>No se encontraron asesorías completadas o en curso para este <?php echo $tipo_busqueda; ?>.</p>
            <?php endif; ?>

        <?php else: ?>
            <h2><?php echo ucfirst($tipo_busqueda); ?> No Encontrado</h2>
            <p>No se encontró ningún <?php echo $tipo_busqueda; ?> con la identificación proporcionada.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
