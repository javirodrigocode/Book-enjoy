<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Usuario</title> 
    
    <link rel="stylesheet" type="text/css" href="../public/css/stylesMainUsers.css"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>       
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        <?php
        include "../models/Conexion.php";

        // Verificar si la cookie de userId existe
        if (isset($_COOKIE['userId'])) {
            // Obtener el ID de usuario de la cookie
            $userId = $_COOKIE['userId'];

            $baseDatos = "BBDDPROYECTO";
            // Crear una instancia de la clase Conexion
            $conexion = new Conexion($baseDatos);
            // Obtener la conexión
            $db = $conexion->obtenerConexion();

            // Consulta SQL para obtener los datos del usuario por su ID
            $query = "SELECT id_Usuario, fullname, email, portal, piso, letra FROM usuarios WHERE id_usuario = ?";

            // Preparar la consulta
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $userId); // 'i' indica que el parámetro es un entero    

            // Ejecutar la consulta
            $stmt->execute();    

            // Obtener el resultado de la consulta
            $result = $stmt->get_result();

            // Verificar si se encontraron resultados
            if ($result && $result->num_rows > 0) {
                // Obtener y mostrar los datos del usuario
                $userData = $result->fetch_assoc();
        ?>
        
</head>
<body class="custom-background">        
    
    <div class="container">
        <h1 class="text-center mt-5">Bienvenido a tu página de usuario</h1>
        <div class="row mt-5">
            <!-- Parte de datos personales -->
            <div class="col-md-6">
                <div class="card section-background">
                    <div class="card-body  datos-personales">
                    <h2 class="card-title">Datos Personales</h2>
                                
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                    <td><?php echo $userData['id_Usuario']; ?></td>
                            </tr>
                            <tr>
                                <th>Nombre y apellidos</th>
                                    <td class="editable" id="nombreInput"><?php echo $userData['fullname']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                    <td class="editable" id="emailInput"><?php echo $userData['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Portal</th>
                                    <td class="editable" id="portalInput"><?php echo $userData['portal']; ?></td>
                            </tr>
                            <tr>
                                <th>Piso</th>
                                    <td class="editable" id="pisoInput"><?php echo $userData['piso']; ?></td>
                            </tr>
                            <tr>
                                <th>Letra</th>
                                    <td class="editable" id="letraInput"><?php echo $userData['letra']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Botones de acción -->
                    <div id="botonesAccion">
                    <!-- Botón para modificar -->
                    <button id="modificarButton" onclick="modificarUsuario()" class="btn btn-primary">Modificar</button>
                    <!-- Botón para guardar -->
                    <button id="guardarButton" class="btn btn-success">Guardar</button>
                    <!-- Botón para cancelar -->
                    <button id="cancelarButton" class="btn btn-secondary">Cancelar</button>
                    <!-- Botón para eliminar -->
                    <button id="eliminarButton" onclick="eliminarUsuario()" class="btn btn-danger">Eliminar usuario</button>
                    </div>
                </div>
            </div>
        </div>                    


                    <!-- Sección de reservas -->
                    <div class="col-md-6">
                        <div class="card section-background">
                            <div class="card-body reservas">
                                <h2 class="card-title">Reservas</h2>
                                <label for="instalacion">Seleccione la instalación:</label>            
                                <select id="instalacion" class="form-control mb-3">
                                    <option value="1">Pista de Padel</option>
                                    <option value="2">Cuarto Comunitario</option>
                                </select> 
                                <div id="calendar"></div>             
                                <!-- Lista de turnos -->
                                <div id="contenedorTurnos">
                                    <ul id="listaTurnos" class="list-group"></ul>
                                    <!-- Botones -->
                                    <div id="contenedorBotones" style="display: none;">
                                        <button id="botonReservar" class="btn btn-primary">Reservar</button>
                                        <button id="botonCancelar" class="btn btn-danger">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                     <!-- Botones de acción -->
                    <div class="col-md-6 mt-0">
                        <div class="row">
                            <div class="col-md-6">
                                <button id="gestionButton" class="btn btn-primary">Gestión de Reservas</button>
                                <script>
                                    // Obtener el botón por su ID
                                    var botonGestion = document.getElementById("gestionButton");

                                    // Agregar un evento de clic al botón
                                    botonGestion.addEventListener("click", function() {
                                        // Redirigir al usuario a gestionReservas.php
                                        window.location.href = "../views/gestionReservas.php";
                                    });
                                </script>
                            </div>
                            <div class="col-md-6 ">
                                <button onclick="goBack()" class="btn btn-secondary">Atrás</button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de logout -->
                    <div class="col-md-12 mt-0 text-right">
                        <form action="../controllers/logout.php" method="POST" class="logout-form">
                            <input type="submit" name="logout" value="Cerrar Sesión" class="btn btn-danger">
                        </form>
                    </div>
                </div>


<!-- Ventana modal de reserva creada -->
<div class="modal fade" id="modalReserva" tabindex="-1" role="dialog" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReservaLabel">Reserva creada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¡Reserva creada correctamente!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensajes -->
<div id="modalMessage" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mensaje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="messageText"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para cambios guardados -->
<div id="cambiosGuardadosModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cambios Guardados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Cambios guardados correctamente.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>

<!-- Ventana modal de confirmación de eliminación -->
<div class="modal fade" id="modalConfirmacionEliminar" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacionEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmacionEliminarLabel">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                        <use xlink:href="#exclamation-triangle-fill"/>
                    </svg>
                    <div>
                         ¿Estás seguro de eliminar el usuario permanentemente?
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminar">Confirmar</button>
            </div>
        </div>
    </div>
</div>



<!-- Ventana modal de usuario eliminado -->
<div class="modal fade" id="usuarioEliminadoModal" tabindex="-1" role="dialog" aria-labelledby="usuarioEliminadoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usuarioEliminadoModalLabel">Usuario eliminado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Usuario eliminado correctamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>


        <?php
            } else {
                echo "No se encontraron datos del usuario.";
            }

            // Cerrar la conexión
            $stmt->close();
            $conexion->cerrarConexion();
        } else {
            echo "Cookie de usuario no encontrada.";
        }
        ?>
    </div>

    <!-- Scripts -->
    <script src="../public/js/modificarUsuario.js"></script>
    <script src="../public/js/eliminarUsuario.js"></script>      
    <script src="../public/js/script.js"></script>
    <script src="../public/js/calendar.js"></script>
    <script src="../public/js/mainPages.js"></script>
    <script src="../public/js/crearReserva.js"></script>
</body>
</html>
