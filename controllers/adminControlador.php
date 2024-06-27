<?php
include_once "../models/usuarios.php";

// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Crear una instancia de la clase Usuarios con la conexión a la base de datos
$usuarios = new Usuarios(null, null, null, null, null, null, null, $conexion);

// Verificar si se han recibido los datos del usuario a modificar
if (
    isset($_POST['userId']) && 
    isset($_POST['newValues']) && 
    !empty($_POST['userId']) && 
    !empty($_POST['newValues'])
) {
    // Obtener los datos del usuario a modificar desde el formulario
$idUsuario = $_POST['userId'];
$newValues = $_POST['newValues'];

// Verificar que se hayan recibido los datos necesarios dentro de newValues
if (
    isset($newValues['nombre']) && 
    isset($newValues['portal']) && 
    isset($newValues['piso']) && 
    isset($newValues['letra']) && 
    isset($newValues['email']) && 
    isset($newValues['perfil']) &&
    !empty($newValues['nombre']) && 
    !empty($newValues['portal']) && 
    !empty($newValues['piso']) && 
    !empty($newValues['letra']) && 
    !empty($newValues['email']) &&
    !empty($newValues['perfil'])
) {
    // Obtener los nuevos valores del array newValues
    $newFullnames = is_array($newValues['nombre']) ? $newValues['nombre'] : array($newValues['nombre']);
    $newEmails = is_array($newValues['email']) ? $newValues['email'] : array($newValues['email']);
    $newPortales = is_array($newValues['portal']) ? $newValues['portal'] : array($newValues['portal']);
    $newPisos = is_array($newValues['piso']) ? $newValues['piso'] : array($newValues['piso']);
    $newLetras = is_array($newValues['letra']) ? $newValues['letra'] : array($newValues['letra']);
    $newPerfiles = is_array($newValues['perfil']) ? $newValues['perfil'] : array($newValues['perfil']);

    // Llamar al método modificarUsuarios para realizar la modificación
    $resultado = $usuarios->modificarUsuarios($idUsuario, $newFullnames, $newPortales, $newPisos, $newLetras, $newEmails, $newPerfiles);

    // Verificar si la modificación fue exitosa
    if ($resultado) {
        // La modificación fue exitosa, devolver una respuesta de éxito
        echo json_encode(array("success" => true, "message" => "Usuario modificado correctamente"));
    } else {
        // La modificación falló, devolver una respuesta de error
        echo json_encode(array("success" => false, "message" => "Error al modificar el usuario"));
    }
} else {
    // No se han recibido todos los datos necesarios dentro de newValues para modificar el usuario
    echo json_encode(array("success" => false, "message" => "Faltan datos para modificar el usuario"));
}
}

?>

