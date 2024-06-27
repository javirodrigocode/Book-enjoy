<?php
include "../models/reservas.php"; 

// Configurar el encabezado de la respuesta como JSON
header('Content-Type: application/json');

// Verificar si se ha enviado un formulario para cancelar una reserva
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_reserva'])) {
    $idReserva = $_POST['id_reserva'];
    // Obtener el ID del usuario de la cookie
    $idUsuario = $_COOKIE['id_usuario'];
    
    $baseDatos = "BBDDPROYECTO";
    // Instancia de la clase Conexion llamada $conexion
    $conexion = new Conexion($baseDatos);
    $db = $conexion->obtenerConexion();

    // Realizar una consulta SQL para obtener los datos necesarios de la base de datos
    $query = "SELECT id_instalacion, id_turno, fechaReserva FROM reservas WHERE id_reserva = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $idReserva);
    $stmt->execute();
    $stmt->bind_result($id_instalacion, $id_turno, $fechaReserva);
    $stmt->fetch();
    $stmt->close();

    try {
        // Crear una instancia de la clase Reservas
        $reservas = new Reservas($idReserva, $idUsuario, null, null, $id_turno, $fechaReserva, $conexion); // Crear una instancia de la clase Reservas
        
        // Llamar al método cancelarReserva solo con el ID de la reserva y del usuario
        $reservas->cancelarReserva($idReserva); // Llamar al método cancelarReserva

        // Enviar una respuesta de éxito
        echo json_encode(array('success' => true));

    } catch (Exception $e) {
        // Manejar cualquier excepción lanzada y enviar una respuesta de error
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }
} else {
    // Enviar una respuesta de error si faltan datos en la solicitud
    echo json_encode(array('success' => false, 'error' => 'Faltan datos en la solicitud'));
}
?>


