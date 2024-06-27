<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents("php://input");
    $datosModificacion = json_decode($input, true);

    // Depuración: Verificar los datos recibidos
    file_put_contents('php://stderr', print_r($datosModificacion, TRUE));

    if (isset($datosModificacion["id_instalacion"]) && isset($datosModificacion["horarios"])) {
        $id_instalacion = $datosModificacion["id_instalacion"];
        $horarios = $datosModificacion["horarios"];

        require_once "../models/Instalaciones.php";
        $conexion = new Conexion("BBDDPROYECTO");
        $instalaciones = new Instalaciones(null, null, $conexion);

        // Depuración: Verificar los valores de $id_instalacion y $horarios
        file_put_contents('php://stderr', "ID de la instalación: " . $id_instalacion . PHP_EOL);
        file_put_contents('php://stderr', "Horarios: " . print_r($horarios, TRUE) . PHP_EOL);

        $result = $instalaciones->modificarInstalacion($id_instalacion, $horarios);

        if ($result) {
            $response = [
                "success" => true,
                "message" => "Instalación modificada correctamente."
            ];
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($response);
        } else {
            $response = [
                "success" => false,
                "message" => "Hubo un error al intentar modificar la instalación."
            ];
            http_response_code(500);
            header("Content-Type: application/json");
            echo json_encode($response);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos: faltan parámetros"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
