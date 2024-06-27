<?php
require_once "../models/Reservas.php";

// Obtener el número de página enviado por la solicitud AJAX
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
echo "Número de página recibido:", $pagina;

// Definir el número de reservas por página
$numReservasPorPagina = 10;

// Calcular el índice de inicio de las reservas para la página actual
$indiceInicio = ($pagina - 1) * $numReservasPorPagina;

// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Obtener las reservas de la página actual
$tablaReservas = Reservas::obtenerReservasPorPagina($indiceInicio, $numReservasPorPagina, $conexion);

// Generar el HTML de las filas de la tabla de reservas
$htmlTablaReservas = '';
foreach ($tablaReservas as $reserva) {
    // Obtener los detalles asociados a la reserva
    $usuario = $reserva->obtenerUsuario();
    $instalacion = $reserva->obtenerInstalacion();
    $turno = $reserva->obtenerTurno();

    // Generar el HTML para una fila de reserva
    $htmlTablaReservas .= "<tr>";
    $htmlTablaReservas .= "<td>{$reserva->getIdReserva()}</td>";
    $htmlTablaReservas .= "<td>{$reserva->getFechaReserva()}</td>";
    $htmlTablaReservas .= "<td>{$usuario->getFullname()}</td>";
    $htmlTablaReservas .= "<td>{$instalacion->getNombre()}</td>";
    $htmlTablaReservas .= "<td>{$turno->getHoraInicio()} - {$turno->getHoraFin()}</td>";
    $htmlTablaReservas .= "<td>{$reserva->getEstado()}</td>";
    $htmlTablaReservas .= "<td>";
    // Agregar una clase adicional si la reserva es posterior a la fecha y hora actual y su estado es 'reservado'
    $fechaReserva = new DateTime($reserva->getFechaReserva());
    $fechaActual = new DateTime();
    if ($reserva->getEstado() === 'reservado' && $fechaReserva > $fechaActual) {
        $htmlTablaReservas .= "<button class='btn btn-danger btn-sm btn-cancelar' data-id_reserva='{$reserva->getIdReserva()}'>Cancelar Reserva</button>";
    } else {
        $htmlTablaReservas .= "&nbsp;"; // Si no cumple las condiciones, mostrar un espacio en blanco
    }
    $htmlTablaReservas .= "</td>";
    $htmlTablaReservas .= "</tr>";
}

// Devolver el HTML generado como respuesta a la solicitud AJAX
echo $htmlTablaReservas;
?>
