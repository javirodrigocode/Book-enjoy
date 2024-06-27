<?php
require_once "../models/Instalaciones.php";

// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Obtener la lista de instalaciones usando la conexión
$instalaciones = new Instalaciones(null, null, $conexion);
$listaInstalaciones = $instalaciones->obtenerInstalaciones($conexion);

// Crear una nueva lista solo con los IDs y nombres de las instalaciones
$instalacionesSimplificadas = array();
foreach ($listaInstalaciones as $instalacion) {
    $instalacionSimplificada = array(
        'id_instalacion' => $instalacion->getId_Instalacion(),
        'nombre' => $instalacion->getNombre()
    );
    $instalacionesSimplificadas[] = $instalacionSimplificada;
}

// Devolver la lista de instalaciones simplificada como JSON
echo json_encode($instalacionesSimplificadas);
?>
