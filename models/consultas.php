<?php
include_once "Conexion.php";

// Función para comprobar el perfil del usuario y establecer la cookie con su ID
function comprobarPerfil($fullname, $email) {
    // Realizo validaciones de datos en el lado del servidor
    if (!preg_match('/^[a-zA-Z ]*$/', $fullname)) {
        return array('error' => 'El nombre y apellidos solo pueden contener letras y espacios');
    }
    // Valido el formato del email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('error' => 'El correo electrónico no tiene un formato válido');
    }

    // Creo una instancia de la clase Conexion
    $conexion = new Conexion("BBDDPROYECTO");
    // Establecemos la conexión con la base de datos
    $db = $conexion->obtenerConexion(); // Utilizamos el método obtenerConexion de la instancia de Conexion
    
    try {
        // Consultamos si el usuario es administrador utilizando sentencias preparadas
        $stmt = $db->prepare("SELECT id_usuario FROM usuarios WHERE fullname = ? AND email = ? AND perfil = 'administrador'");
        $stmt->bind_param("ss", $fullname, $email);
        $stmt->execute();
        $stmt->store_result();
        
        // Verificamos si se encontró el usuario como administrador
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_usuario);
            $stmt->fetch();
            $stmt->close();
            $conexion->cerrarConexion(); // Cerrar la conexión        
            
            // Almacenar el ID del usuario en una cookie
            setcookie('userId', $id_usuario, 0, "/"); // La cookie expirará en 30 días
            
            return 'Administrador';
        }
        
        // Si no es administrador, buscamos si está registrado como usuario normal
        $stmt = $db->prepare("SELECT id_usuario FROM usuarios WHERE fullname = ? AND email = ?");
        $stmt->bind_param("ss", $fullname, $email);
        $stmt->execute();
        $stmt->store_result();
        
        // Verificamos si se encontró el usuario como registrado
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_usuario);
            $stmt->fetch();
            $stmt->close();
            $conexion->cerrarConexion(); // Cerrar la conexión        
            
            // Almacenar el ID del usuario en una cookie
            setcookie('userId', $id_usuario, time() + (86400 * 30), "/"); // La cookie expirará en 30 días
            
            return 'Usuario Registrado';
        }
        
        // Si no se encontró ningún usuario con los datos especificados
        $conexion->cerrarConexion(); // Cerrar la conexión
        return 'No registrado';
    } catch (Exception $e) {
        // Manejar excepciones si ocurre algún error durante la ejecución de la consulta
        return 'Error en la consulta: ' . $e->getMessage();
    }    
}

function obtenerIdUsuario($fullname, $email) {
    $baseDatos = "BBDDPROYECTO";
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion($baseDatos);
    // Obtener la conexión
    $db = $conexion->obtenerConexion();

    // Consulta SQL para obtener el ID de usuario basado en el nombre completo y correo electrónico
    $query = "SELECT id_usuario FROM usuarios WHERE fullname = ? AND email = ?";
    
    // Preparar la consulta
    $stmt = $db->prepare($query);
    if ($stmt === false) {
        // Manejar el error en caso de que la preparación de la consulta falle
        die("Error al preparar la consulta: " . $db->error);
    }

    // Vincular los parámetros y ejecutar la consulta
    $stmt->bind_param("ss", $fullname, $email);
    $stmt->execute();
    
    // Vincular el resultado
    $stmt->bind_result($idUsuario);
    // Obtener el valor del ID de usuario
    $stmt->fetch();
    
    // Cerrar el statement y la conexión
    $stmt->close();
    $db->close();

    return $idUsuario;
}


function obtenerReservasUsuario($idUsuario, $pagina, $num_reservas_por_pagina) {
    $baseDatos = "BBDDPROYECTO";
    $conexion = new Conexion($baseDatos);
    $db = $conexion->obtenerConexion();

    if ($db->connect_error) {          
        die("Error de conexión: " . $db->connect_error);    
    }

    // Calcular el punto de inicio de las reservas para la página actual
    $inicio = ($pagina - 1) * $num_reservas_por_pagina;

    // Consulta SQL para obtener las reservas del usuario con paginación
    $query = "SELECT r.id_reserva, i.nombre AS nombre_instalacion, r.fechaReserva, t.hora_Inicio, t.hora_Fin, r.estado 
              FROM reservas r
              INNER JOIN instalaciones i ON r.id_instalacion = i.id_instalacion
              INNER JOIN turnos t ON r.id_turno = t.id_turno
              WHERE r.id_usuario = ?
              ORDER BY r.fechaReserva DESC
              LIMIT ?, ?"; // Limitar el número de resultados según la página y el número de reservas por página

    // Preparar la consulta
    $stmt = $db->prepare($query);    
    // Vincular parámetros
    $stmt->bind_param("iii", $idUsuario, $inicio, $num_reservas_por_pagina);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener los resultados
    $resultado = $stmt->get_result();
    // Obtener las filas de resultados como un array asociativo
    $reservas = $resultado->fetch_all(MYSQLI_ASSOC);
    // Cerrar la consulta preparada
    $stmt->close();

    // Consulta SQL para obtener el total de reservas del usuario
    $queryTotal = "SELECT COUNT(*) as total_reservas
                   FROM reservas
                   WHERE id_usuario = ?";
    // Preparar la consulta
    $stmtTotal = $db->prepare($queryTotal);    
    // Vincular parámetros
    $stmtTotal->bind_param("i", $idUsuario);
    // Ejecutar la consulta
    $stmtTotal->execute();
    // Obtener el resultado
    $resultadoTotal = $stmtTotal->get_result();
    // Obtener el total de reservas
    $totalReservas = $resultadoTotal->fetch_assoc()['total_reservas'];
    // Cerrar la consulta preparada
    $stmtTotal->close();

    // Cerrar la conexión
    $db->close();

    return array("reservas" => $reservas, "total_reservas" => $totalReservas);
}


?>

