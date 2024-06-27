<?php
 
include "../models/instalaciones.php";

// Verificar si se ha recibido una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se han recibido los datos esperados
    if (isset($_POST["nuevaInstalacion"])) {
        // Obtener los datos de la nueva instalacion desde la solicitud POST
        $nuevaInstalacion = $_POST["nuevaInstalacion"];        
        $nombre = $nuevaInstalacion['nombre'];
        $horarios = $nuevaInstalacion['turnos'];
        
        // Crear una instancia de la clase Conexion
        $conexion = new Conexion("BBDDPROYECTO");

        // Crear una instancia de Instalaciones pasando la instancia de Conexion
        $instalaciones = new Instalaciones(null, null, $conexion);          
            
        
        // Llamar al método para añadir una nueva instalación
        $result = $instalaciones->añadirInstalacion($nombre, $horarios);
        
        // Verificar si la adición de la instalación fue exitosa
        if ($result) {
            // Preparar la respuesta en formato JSON
            $response = [
                "success" => true, // Indicar si la operación fue exitosa
                "message" => "Instalación agregada correctamente." 
            ];

            // Enviar la respuesta en formato JSON
            http_response_code(200); // OK
            header("Content-Type: application/json");
            echo json_encode($response);
        } else {
            // Preparar la respuesta en formato JSON si hubo un error al añadir la instalacion
            $response = [
                "success" => false, // Indicar que hubo un error
                "message" => "Hubo un error al intentar agregar la instalación."
            ];

            // Enviar la respuesta en formato JSON
            http_response_code(500); // Internal Server Error
            header("Content-Type: application/json");
            echo json_encode($response);
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
