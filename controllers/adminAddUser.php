<?php
 
include "../models/usuarios.php";

// Verificar si se ha recibido una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si se han recibido los datos esperados
    if (isset($_POST["newValues"])) {
        try {
            // Obtener los datos del nuevo usuario desde la solicitud POST
            $datosUsuario = $_POST["newValues"];            

            // Crear una instancia de la clase Conexion
            $conexion = new Conexion("BBDDPROYECTO");

            // Crear una instancia de Usuarios pasando la instancia de Conexion
            $usuarios = new Usuarios(
                null, // Dejar el id_Usuario como NULL o simplemente no pasarlo
                $datosUsuario['fullname'],
                $datosUsuario['email'],
                $datosUsuario['portal'],
                $datosUsuario['piso'],
                $datosUsuario['letra'],
                $datosUsuario['perfil'],
                $conexion
            );
            
            // Llamar al método para añadir un nuevo usuario
            $result = $usuarios->añadirUsuario($datosUsuario);
            
            // Verificar si la adición del usuario fue exitosa
            if ($result) {
                // Preparar la respuesta en formato JSON
                $response = [
                    "success" => true, // Indicar si la operación fue exitosa
                    "message" => "Usuario agregado correctamente." 
                ];

                // Enviar la respuesta en formato JSON
                http_response_code(200); // OK
                header("Content-Type: application/json");
                echo json_encode($response);
            } else {
                // Preparar la respuesta en formato JSON si hubo un error al añadir el usuario
                $response = [
                    "success" => false, // Indicar que hubo un error
                    "message" => "Hubo un error al intentar agregar el usuario."
                ];

                // Enviar la respuesta en formato JSON
                http_response_code(500); // Internal Server Error
                header("Content-Type: application/json");
                echo json_encode($response);
            }
        } catch (mysqli_sql_exception $e) {
            // Manejar la excepción de usuario duplicado
            $response = [
                "success" => false,
                "message" => "Ya existe un usuario con la misma dirección."
            ];

            // Enviar la respuesta en formato JSON
            http_response_code(400); // Bad Request
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

