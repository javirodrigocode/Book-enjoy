<?php
// Verificar si se recibieron los datos necesarios
if (isset($_POST['idUsuario']) && isset($_POST['newFullname']) && isset($_POST['newEmail']) && 
    isset($_POST['newPortal']) && isset($_POST['newPiso']) && isset($_POST['newLetra'])) {

    // Obtener los datos del formulario
    $userId = $_POST['idUsuario'];
    $newFullname = $_POST['newFullname'];
    $newEmail = $_POST['newEmail'];
    $newPortal = $_POST['newPortal'];
    $newPiso = $_POST['newPiso'];
    $newLetra = $_POST['newLetra'];

    // Validar los datos
    if (!preg_match('/^[a-zA-Z\s]+$/', $newFullname)) {
        echo json_encode(array('success' => false, 'message' => 'El nombre completo solo puede contener letras y espacios.'));
        exit();
    }
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('success' => false, 'message' => 'El formato del correo electrónico no es válido.'));
        exit();
    }
    if (!preg_match('/^\d+$/', $newPortal)) {
        echo json_encode(array('success' => false, 'message' => 'El portal solo puede contener números.'));
        exit();
    }
    if (!preg_match('/^[a-zA-Z0-9]+$/', $newPiso)) {
        echo json_encode(array('success' => false, 'message' => 'El piso solo puede contener letras y números.'));
        exit();
    }
    if (!preg_match('/^[a-zA-Z]+$/', $newLetra)) {
        echo json_encode(array('success' => false, 'message' => 'La letra solo puede contener letras.'));
        exit();
    }

    include "../models/Conexion.php";

    $baseDatos = "BBDDPROYECTO";
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion($baseDatos);
    // Obtener la conexión
    $db = $conexion->obtenerConexion();

    // Consulta SQL para actualizar los datos del usuario en la base de datos
    $query = "UPDATE usuarios SET fullname=?, email=?, portal=?, piso=?, letra=? WHERE id_usuario=?";

    // Preparar la consulta
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssssi", $newFullname, $newEmail, $newPortal, $newPiso, $newLetra, $userId);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si la consulta se ejecuta correctamente, devolver una respuesta exitosa
        echo json_encode(array('success' => true, 'message' => 'Usuario modificado correctamente.'));
    } else {
        // Si hay un error al ejecutar la consulta, devolver una respuesta de error
        echo json_encode(array('success' => false, 'message' => 'Error al modificar el usuario.'));
    }

    // Cerrar la conexión
    $stmt->close();
    $conexion->cerrarConexion();
} else {
    // Si no se recibieron los datos del formulario, devolver una respuesta de error
    echo json_encode(array('success' => false, 'message' => 'No se recibieron los datos del formulario.'));
}
?>
