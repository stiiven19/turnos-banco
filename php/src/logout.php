<?php
session_start();

// Guardar información necesaria en variables de sesión antes de destruir la sesión
if (isset($_SESSION['turno_en_atencion'])) {
    $_SESSION['saved_turno_en_atencion'] = $_SESSION['turno_en_atencion'];
    $_SESSION['saved_inicio_atencion'] = $_SESSION['inicio_atencion'];
}

// Destruir la sesión
// Restaurar la información necesaria en la nueva sesión
session_start();
if (isset($_SESSION['saved_turno_en_atencion'])) {
    $_SESSION['turno_en_atencion'] = $_SESSION['saved_turno_en_atencion'];
    $_SESSION['inicio_atencion'] = $_SESSION['saved_inicio_atencion'];
    unset($_SESSION['saved_turno_en_atencion']);
    unset($_SESSION['saved_inicio_atencion']);
}

// Redirigir al inicio de sesión
header('Location: login_asesor.php');
exit();
?>
