<?php
include "../models/consultas.php";

// Compruebo si se han enviado los datos cogiendo los datos introducidos
if (isset($_POST["Enviar"])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];                
    $tipoUsuario = comprobarPerfil($fullname, $email);
     
    if ($tipoUsuario === 'Administrador') {
        $idUsuario = obtenerIdUsuario($fullname, $email); // Obtengo ID de usuario con la funcion
        setcookie("id_usuario", $idUsuario, time() + (86400 * 30), "/"); // Creo una cookie
        header("Location: mainPageAdmin.php");
        exit();
    } elseif ($tipoUsuario === 'Usuario Registrado') { // Configuro la cookie con el ID de usuario
        $idUsuario = obtenerIdUsuario($fullname, $email); /// Obtengo ID de usuario con la funcion
        setcookie("id_usuario", $idUsuario, time() + (86400 * 30), "/"); // Cookie válida por 30 días               
        header("Location: mainPageUsers.php");
        exit();
        
    } else {
        // Mostrar mensaje solo si el formulario ha sido enviado
        $mensaje = "El usuario no está registrado en el sistema.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../public/css/styles.css">
</head>
<body class="custom-background">
    <!-- Encabezado con logo y título -->
    <header class="container-fluid bg-header">
        <div class="row">
            <div class="col-6 col-md-3">
                <!-- logo -->
                <img src="../public/Images/logo.png" class="img-fluid mx-auto d-block" alt="Logo">
            </div>
            <div class="col-6 col-md-9 align-self-center">
                <!-- Título -->
                <h1 class="font-weight-bold text-center">VIP Arcangel Gabriel</h1>
            </div>
        </div>
    </header>

    <!-- Cuerpo con formulario -->
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form id="loginForm" action="index.php" method="POST" class="mb-3">
                    <h2 class="mb-3">Iniciar Sesión</h2>
                    <?php if (isset($mensaje)) { echo "<p>$mensaje</p>"; } ?>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fullname" name="fullname" autocomplete="name" required>
                        <label for="fullname">Nombre y Apellidos:</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" autocomplete="email" required>
                        <label for="email">Correo Electrónico:</label>
                    </div>
                    
                    <input type="submit" class="btn btn-primary" name="Enviar" value="Iniciar Sesión">
                </form>
            </div>
            <div class="col-md-6 texto-contenedor">
                <h1 class="texto">Accede, reserva y disfruta de tu instalación comunitaria</h1>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap y validación del formulario -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../public/js/script.js"></script>
</body>
</html>

