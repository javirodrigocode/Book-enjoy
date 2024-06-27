<?php
require_once "../models/Turnos.php";
include "../models/conexion.php";

// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Obtener el ID de la instalación enviado desde la solicitud AJAX
$idInstalacion = $_POST['instalacionId'];

// Crear una instancia de la clase turnos
$turnos = new Turnos(null, null, null, null, $conexion);

// Obtener los turnos asociados a la instalación
$listaTurnos = $turnos->obtenerTurnoPorInstalacion($idInstalacion);

// Convertir la lista de turnos a formato JSON y devolverla
echo json_encode($listaTurnos);
?>
