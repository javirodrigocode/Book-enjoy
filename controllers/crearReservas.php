<?php
include '../models/reservas.php'; // Asegúrate de incluir la clase Reservas

$idUsuario = $_COOKIE['id_usuario'];
// Verifica que se recibieron todos los datos necesarios
if (isset ($_POST['id_usuario'], $_POST['id_turno'], $_POST['fechaReserva'], $_POST['selectedInstalacion'])) {
    // Obtiene los datos de la solicitud POST    
    $id_turno = $_POST['id_turno'];
    $fechaReserva = $_POST['fechaReserva'];
    $selectedInstalacion = $_POST['selectedInstalacion'];
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion("BBDDPROYECTO");
    
    try {
        // Crear una instancia de la clase Reservas
        $reservas = new Reservas($idUsuario, $selectedInstalacion, null, null, $id_turno, $fechaReserva, $conexion);
        
        // Llamar al método crearReserva con los datos recibidos
        $resultado = $reservas->crearReserva($idUsuario, $selectedInstalacion, $id_turno, $fechaReserva, );
        
        // Devolver el resultado de la operación
        echo json_encode($resultado);
    } catch (Exception $e) {
        // Manejar cualquier excepción lanzada
        echo json_encode(array('error' => $e->getMessage()));
    }
} else {
    echo json_encode(array('error' => 'Faltan datos en la solicitud'));
}
?>


