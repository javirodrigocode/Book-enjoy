<?php
require_once "../models/Usuarios.php";

// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Obtener la lista de usuarios usando la conexión
$usuarios = new Usuarios(null, null, null, null, null, null, null, $conexion);
$tablaUsuarios = $usuarios->obtenerUsuarios($conexion);

// Crear una nueva lista solo con los IDs y nombres completos de los usuarios
$usuariosSimplificados = array();
foreach ($tablaUsuarios as $usuario) {
    $usuarioSimplificado = array(
        'id' => $usuario->getId_Usuario(),
        'fullname' => $usuario->getFullname()
    );
    $usuariosSimplificados[] = $usuarioSimplificado;
}

// Devolver la lista de usuarios simplificada como JSON
echo json_encode($usuariosSimplificados);
?>
