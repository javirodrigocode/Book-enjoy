<?php
include "../models/usuarios.php";

// Verificar si la cookie de userId existe y si el usuario tiene permisos de administrador
if (isset($_COOKIE['userId'])) {
    // Obtener el ID de usuario de la cookie
    $userId = $_COOKIE['userId'];

    // Creo una instancia de la clase Conexion
    $conexion = new Conexion("BBDDPROYECTO");
    // Establecemos la conexión con la base de datos
    $db = $conexion->obtenerConexion();

    // Consulta SQL para verificar si el usuario es administrador
    $query = "SELECT perfil FROM usuarios WHERE id_usuario = ? AND perfil = 'administrador'";

    // Preparar la consulta
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $userId); // 'i' indica que el parámetro es un entero    

    // Ejecutar la consulta
    $stmt->execute();    

    // Obtener el resultado de la consulta
    $result = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($result && $result->num_rows > 0) {
        // El usuario es un administrador, mostrar el contenido de adminsection.php
        ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="../public/css/stylesAdminSection.css">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">      
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>       
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <style>
        body {
            background-image: url('../public/Images/minimalista.png');
            background-size: cover; 
            background-position: center; 
        }        
    </style>
        </head>
        <body>
    <!-- Encabezado -->
    <div class="container text-center mt-5">
        <h1 id="tituloBienvenida" class="display-3">Bienvenido a tu Área de Administrador</h1>
    </div>

    <!-- Sección de Administrador -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="text-center mb-4">
                    <h2>Área Administrativa</h2>
                </div>
                <div class="text-center">
                    <button id="btnUsuarios" class="btn btn-primary btn-lg btn-block mb-3">Usuarios</button>
                    <button id="btnInstalaciones" class="btn btn-primary btn-lg btn-block mb-3">Ver Instalaciones</button>
                    <button id="btnReservas" class="btn btn-primary btn-lg btn-block mb-3">Reservas</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Div donde se cargará la lista de usuarios -->
    <div id="listaUsuarios"></div>
    <!-- Contenedor donde se cargará la tabla de instalaciones -->
    <div id="tablaInstalaciones"></div>
    <!-- Contenedor donde se cargará la tabla de reservas -->
    <div id="tablaReservas"></div>

    <!-- Botones de navegación -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <button id="btn-atras" class="btn btn-secondary btn-lg btn-block" style="display: none;">Atrás</button>
                <button onclick="goBack()" class="btn btn-secondary btn-lg btn-block">Volver a tu espacio</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../public/js/script.js"></script>
    <script src='../public/js/adminUsers.js'></script>
    <script src="../public/js/accionesAdminUsuarios.js"></script>
        
</body>
</html>

        <?php
    } else {
        // El usuario no tiene permisos de administrador, redirigirlo a otra página
        header("Location: index.php");
        exit(); // Salir del script para evitar que se siga ejecutando
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->cerrarConexion();
} else {
    // La cookie de usuario no se encontró, redirigirlo a la página de inicio de sesión
    header("Location: index.php");
    exit(); // Salir del script para evitar que se siga ejecutando
}
?>


