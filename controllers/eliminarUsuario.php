<?php
// Verificar si se recibió el ID del usuario a eliminar
if(isset($_POST['idUsuario'])) {
    // Obtener el ID del usuario recibido
    $userId = $_POST['idUsuario'];

    include "../models/Conexion.php";

    $baseDatos = "BBDDPROYECTO";
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion($baseDatos);
    // Obtener la conexión
    $db = $conexion->obtenerConexion();

    // Consulta SQL para eliminar el usuario por su ID
    $query = "DELETE FROM usuarios WHERE id_usuario = ?";

    // Preparar la consulta
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $userId); // 'i' indica que el parámetro es un entero
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si la consulta se ejecutó correctamente, enviar una respuesta de éxito
        echo json_encode(array('success' => true));
    } else {
        // Si hubo un error al ejecutar la consulta, enviar una respuesta de error
        echo json_encode(array('success' => false));
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->cerrarConexion();
} else {
    // Si no se recibió el ID del usuario a eliminar, enviar una respuesta de error
    echo json_encode(array('success' => false, 'error' => 'No se recibió el ID del usuario'));
}
?>
