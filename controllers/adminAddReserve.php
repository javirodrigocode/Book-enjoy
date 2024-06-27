<?php

include "../models/reservas.php";

// Verificar si se ha recibido una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se han recibido los datos esperados
    if (isset($_POST["newValues"])) {
        // Obtener los datos de la nueva reserva desde la solicitud POST
        $datosReserva = $_POST["newValues"];   

        // Crear una instancia de la clase Conexion
        $conexion = new Conexion("BBDDPROYECTO");

        // Crear una instancia de Reservas pasando la instancia de Conexion
        $reservas = new Reservas(
            null, // Dejar el id_Reserva como NULL o simplemente no pasarlo            
            $datosReserva['fechaReserva'],
            $datosReserva['idUsuario'],
            $datosReserva['idInstalacion'],
            $datosReserva['idTurno'],            
            null,            
            $conexion
        );

        try {
            // Llamar al método para crear una nueva reserva
            $result = $reservas->crearReserva($datosReserva['idUsuario'], $datosReserva['idInstalacion'], $datosReserva['idTurno'], $datosReserva['fechaReserva']);
            
            // Verifica el resultado antes de devolver la respuesta
            if (isset($result['success']) && $result['success']) {
                // Preparar la respuesta en formato JSON
                $response = [
                    "success" => true, // Indicar si la operación fue exitosa
                    "message" => "Reserva realizada correctamente." 
                ];
            } else {
                // Preparar la respuesta en formato JSON
                $response = [
                    "success" => false, // Indicar que hubo un error
                    "message" => $result['message']
                ];
            }

            // Enviar la respuesta en formato JSON
            http_response_code(200); // OK
            header("Content-Type: application/json");
            echo json_encode($response);
        } catch (Exception $e) {
            // Preparar la respuesta en formato JSON si hubo un error al añadir el usuario
            $response = [
                "success" => false, // Indicar que hubo un error
                "message" => $e->getMessage()
            ];

            // Enviar la respuesta en formato JSON
            http_response_code(500); // Internal Server Error
            header("Content-Type: application/json");
            echo json_encode($response);

            // Agrega un registro de error para el seguimiento
            error_log("Error al crear reserva: " . $e->getMessage());
        }
    } else {
        // Si los datos esperados no se recibieron, enviar una respuesta de error
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Datos incompletos"]);
    }
} else {
    // Si la solicitud no es POST, enviar una respuesta de error
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Método no permitido"]);
}

?>
