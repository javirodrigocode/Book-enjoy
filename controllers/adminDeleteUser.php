<?php
// Incluir la clase Usuarios
include "../models/Usuarios.php";

// Verificar si se recibieron los usuarios seleccionados
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["usuarios"]) && is_array($_POST["usuarios"])) {
    // Obtener los datos desde la solicitud post
    $usuarioEliminar = $_POST["usuarios"];
    
    // Inicializar el indicador de éxito
    $allSuccess = true;
    $message = "";

    // Recorrer los usuarios seleccionados
    foreach ($usuarioEliminar as $idUsuario) {
        // Crear una instancia de la clase Conexion
        $conexion = new Conexion("BBDDPROYECTO");

        // Crear una instancia de Usuarios pasando la instancia de Conexion
        $usuarios = new Usuarios(null, null, null, null, null, null, null, $conexion);

        // Llamar al método para eliminar los usuarios seleccionados
        $resultado = $usuarios->eliminarUsuarios($usuarioEliminar);

        // Verificar si la eliminación del usuario fue exitosa
        if (!$resultado) {
            $allSuccess = false;
            $message .= "Error al eliminar el usuario con ID $usuarioEliminar. ";
        }
    }

    // Preparar la respuesta en formato JSON
    if ($allSuccess) {
        $response = [
            "success" => true,
            "message" => "Usuarios eliminados correctamente."
        ];
        http_response_code(200); // OK
    } else {
        $response = [
            "success" => false,
            "message" => "$message"
        ];
        http_response_code(500); // Internal Server Error
    }
} else {
    // Si los datos esperados no se recibieron, enviar una respuesta de error
    http_response_code(400); // Bad Request
    $response = ["error" => "Datos incompletos"];
}

// Enviar la respuesta en formato JSON
header("Content-Type: application/json");
echo json_encode($response);
?>
