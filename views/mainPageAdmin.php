<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="../public/css/stylesMainAdmin.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>       
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</head>
<body class="custom-background">
    <h1 class="text-center mt-5">Bienvenido a tu espacio de Administrador</h1>
    
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6 ml-md-auto">
                <div id="adminMessage" class="seccion">
                    <img src="../public/Images/Administrador.png" alt="Administrador">
                    <div class="botones">
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-primary btn-lg btn-block mb-3" onclick="goToAdminSection()">Area Administrativa</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mr-md-auto">
                <div id="userMessage" class="seccion">
                    <img src="../public/Images/Usuarios.png" alt="Usuario">
                    <div class="botones">
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-primary btn-lg btn-block mb-3" onclick="goToUserSection()">Area de Usuario</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="../controllers/logout.php" method="POST" class="logout-form cerrar-sesion">
        <input type="submit" name="logout" value="Cerrar Sesión" class="btn btn-danger btn-lg mb-3">
    </form>

    <!-- Scripts -->
    <script>
        function goToAdminSection() {
            window.location.href = "adminSection.php"; // Redirigir a la sección de administrador
        }

        function goToUserSection() {
            window.location.href = "mainPageUsers.php"; // Redirigir a la sección de usuario
        }
    </script>
</body>
</html>
