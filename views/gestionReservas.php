<?php
include "../models/consultas.php";
include "../models/reservas.php"; 

// Obtener el ID del usuario de la cookie 
$idUsuario = $_COOKIE['id_usuario'];

// Definir el número de reservas por página
$num_reservas_por_pagina = 10;

// Obtener la página actual
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Obtener las reservas del usuario con paginación y el total de reservas
$reservasData = obtenerReservasUsuario($idUsuario, $pagina, $num_reservas_por_pagina);
$reservas = $reservasData['reservas'];
$total_reservas = $reservasData['total_reservas'];

// Calcular el número total de páginas
$total_paginas = max(1, ceil($total_reservas / $num_reservas_por_pagina));

// Verificar si se ha enviado un formulario para cancelar una reserva
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_reserva'])) {
    $idReserva = $_POST['id_reserva'];
    
    // Buscar la reserva correspondiente en el array $reservas
    foreach ($reservas as $reserva) {
        if ($reserva['id_reserva'] == $idReserva) {
            $idInstalacion = $reserva['id_instalacion'];
            $idTurno = $reserva['id_turno'];
            $fechaReserva = $reserva['fechaReserva'];
            
            // Crear una instancia de la clase Reservas para cancelar la reserva
            $reservasClase = new Reservas($idUsuario, $id_instalacion, null, null, $id_turno,  $fechaReserva, $conexion);
            
            // Llamar al método cancelarReserva con los datos de la reserva
            $reservasClase->cancelarReserva($idReserva);           
            
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/stylesGestion.css">
</head>
<body class="custom-background">
    <div class="container mt-5">
        <h1 class="text-center">Reservas</h1>
        <div class="table-responsive">
            <table id="tablaReservasUsuario"class="table custom-table mt-4">
                <thead class="thead-dark">
                    <tr>
                    <th data-indice="0">ID Reserva</th>
                    <th data-indice="1">Instalación</th>
                    <th data-indice="2">Fecha de Reserva</th>
                    <th data-indice="3">Hora de Inicio</th>
                    <th data-indice="4">Hora de Fin</th>
                    <th data-indice="5">Estado</th>
                    <th data-indice="6">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $reserva): ?>
                        <tr>
                            <td><?php echo $reserva['id_reserva']; ?></td>
                            <td><?php echo $reserva['nombre_instalacion']; ?></td>
                            <td><?php echo $reserva['fechaReserva']; ?></td>
                            <td><?php echo $reserva['hora_Inicio']; ?></td>
                            <td><?php echo $reserva['hora_Fin']; ?></td>
                            <td><?php echo $reserva['estado']; ?></td>
                            <td>
                                <?php if ($reserva['fechaReserva'] > date('Y-m-d') && $reserva['estado'] === 'Reservado'): ?>
                                    <button class="btn btn-danger cancel-button" data-reserva-id="<?php echo $reserva['id_reserva']; ?>">Cancelar Reserva</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>            
                </tbody>
            </table>
        </div>

        <!-- Agrega los enlaces de paginación -->
        <div class="row mt-4 ml-2 mr-2 mb-4">
            <ul class="pagination justify-content-start mb-0">
                <?php if ($pagina > 1): ?>
                    <li class="page-item"><a class="page-link" href="?pagina=<?php echo ($pagina - 1); ?>">Anterior</a></li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php if ($i == $pagina) echo 'active'; ?>"><a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
                <?php if ($pagina < $total_paginas): ?>
                    <li class="page-item"><a class="page-link" href="?pagina=<?php echo ($pagina + 1); ?>">Siguiente</a></li>
                <?php endif; ?>
            </ul>
            <div class="ml-auto mr-2 mb-2">
                <a href="../views/mainpageusers.php" class="btn btn-secondary btn-lg">Atrás</a>
            </div>
        </div>
         
        <div class="modal fade" id="modalCancelarReserva" tabindex="-1" role="dialog" aria-labelledby="modalCancelarLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCancelarLabel">Reserva cancelada</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¡Reserva cancelada correctamente!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>    
        

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../public/js/cancelarReserva.js"></script>
    <script src="../public/js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var encabezados = document.querySelectorAll('#tablaReservasUsuario th');
            encabezados.forEach(function (encabezado, indice) {encabezado.addEventListener('click', function () {
                    ordenarTabla(indice, 'tablaReservasUsuario');
                });
            });
        });
    </script>
</body>
</html>

