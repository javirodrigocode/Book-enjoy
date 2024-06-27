<?php
// Inicializar la sesión si aún no está iniciada
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Borrar la cookie de sesión
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Borrar la cookie "tipo_usuario"
setcookie("tipo_usuario", "", time() - 3600, "/");

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio de sesión
header("Location: ../views/index.php");
exit();

