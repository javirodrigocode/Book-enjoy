<?php
require_once "../models/Reservas.php";
require_once "../models/Usuarios.php";
require_once "../models/Instalaciones.php";
require_once "../models/Turnos.php";

// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Obtener la lista de reservas usando la conexión
$reserva = new Reservas(null, null, null, null, null, null, $conexion);
$tablaReservas = $reserva->obtenerReservas($conexion);

// Obtener la lista de usuarios
$usuarios = new Usuarios(null, null, null, null, null, null, null, $conexion);
$listaUsuarios = $usuarios->obtenerUsuarios($conexion);
// Convertir la lista de usuarios a JSON
$listaUsuariosJSON = json_encode($listaUsuarios); 

//Obtener lista de instalaciones
$instalaciones = new Instalaciones(null, null, $conexion);
$listaInstalaciones = $instalaciones->obtenerInstalaciones($conexion);
$listaInstalacionesJSON = json_encode($listaInstalaciones);

// Definir $idInstalacion si está presente en la solicitud POST
$idInstalacion = isset($_POST['instalacionId']) ? $_POST['instalacionId'] : null;

// Obtener la lista de turnos solo si $idInstalacion está definido
if ($idInstalacion !== null) {
    $turnos = new Turnos(null, null, null, null, $conexion);
    $listaTurnos = $turnos->obtenerTurnoPorInstalacion($idInstalacion);
    $listaTurnosJSON = json_encode($listaTurnos);
} else {
    $listaTurnosJSON = json_encode([]); // Si $idInstalacion no está definido, enviar un array vacío
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">       
    <link rel="stylesheet" href="../public/css/stylesAdminSection.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  

    <title>Reservas existentes</title>    
</head>
<body>

<!-- Generar la tabla reservas -->
<table class="table custom-table mt-4" id="tablaReservas" border="1">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Fecha reserva</th>
      <th>Usuario</th>
      <th>Instalación</th>  
      <th>Turno</th>
      <th>Estado de la reserva</th> 
      <th>Acciones</th>              
    </tr>
  </thead>
  <tbody class="tbody-reservas">
    <?php 
    if (!empty($tablaReservas)) {
        foreach ($tablaReservas as $reserva) {
            // Obtener los detalles asociados a la reserva
            $usuario = $reserva->obtenerUsuario();
            $instalacion = $reserva->obtenerInstalacion();
            $turno = $reserva->obtenerTurno();

            echo "<tr>";
            echo "<td>{$reserva->getIdReserva()}</td>";
            echo "<td>{$reserva->getFechaReserva()}</td>";
            echo "<td>{$usuario->getFullname()}</td>";
            echo "<td>{$instalacion->getNombre()}</td>";
            echo "<td>{$turno->getHoraInicio()} - {$turno->getHoraFin()}</td>";      
            echo "<td>{$reserva->getEstado()}</td>"; 
            echo "<td>";
            // Agregar una clase adicional si la reserva es posterior a la fecha y hora actual y su estado es 'reservado'
            $fechaReserva = new DateTime($reserva->getFechaReserva());
            $fechaActual = new DateTime();
            if ($reserva->getEstado() === 'Reservado' || $reserva->getEstado() === 'reservado'&& $fechaReserva > $fechaActual) {
              echo "<button class='btn btn-danger btn-sm btnCancelar' data-id_reserva='{$reserva->getIdReserva()}'>Cancelar Reserva</button>";
            } else {
                echo "&nbsp;"; // Si no cumple las condiciones, mostrar un espacio en blanco
            }
            echo "</td>";
            echo "</tr>";
        }        
    } else {
        // No hay reservas
        echo "<tr><td colspan='7'>No hay reservas.</td></tr>";
    }
    ?>                
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <div class="row justify-content-center mb-4" style="background-color: #caf0f8; padding: 10px;">
                    <button class="btn btn-primary btnCrear" id="btnCrearReserva">Crear Reserva</button>
                    <div class="pagination paginacion"></div>
                </div>
            </td>
        </tr>
    </tfoot>
</table>

<!-- Modal de alerta -->
<div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog" aria-labelledby="modalAlertLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAlertLabel">Alerta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalAlertMessage">
        <!-- Contenido del mensaje de alerta -->
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btnConfirm">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog" aria-labelledby="confirmarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmarModalLabel">Confirmación de cancelación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas cancelar esta reserva?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnConfirmarNo" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="btnConfirmarSi">Sí</button>
      </div>
    </div>
  </div>
</div>



<script>
    var listaUsuarios = <?php echo $listaUsuariosJSON; ?>;
    var listaInstalaciones = <?php echo $listaInstalacionesJSON; ?>;
    var listaTurnos = <?php echo $listaTurnosJSON; ?>;
</script>
<script src="../public/js/manejoReservas.js"></script>
<script src="../public/js/script.js"></script>
<script>
$(document).ready(function() {
    // Datos para paginar
    var datosReservas = <?php echo json_encode($tablaReservas); ?>;
    var elementosPorPagina = 10;
    var tablaReservas = $('#tablaReservas');

    // Llamar a la función de paginación para la tabla de usuarios
    manejarPaginacion(tablaReservas, elementosPorPagina, datosReservas);
});
</script>
</body>
</html>

