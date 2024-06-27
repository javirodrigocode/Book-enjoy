<?php
include_once "usuarios.php";
include_once "instalaciones.php";
include_once "turnos.php";
class Reservas {
    private $id_reserva;
    private $estado;    
    private $id_usuario;
    private $id_instalacion;
    private $id_turno;
    private $fechaReserva;
    private $conexion;

    // Constructor
    public function __construct($idUsuario, $idReserva, $estado, $id_instalacion, $id_turno, $fechaReserva, Conexion $conexion) {                
        $this->id_reserva = $idReserva; // El valor de id_Reserva se asignará automáticamente al insertar en la base de datos
        $this->estado = $estado; 
        $this->id_usuario = $idUsuario;
        $this->id_instalacion = $id_instalacion;
        $this->id_turno = $id_turno;
        $this->fechaReserva = $fechaReserva;         
        $this->conexion = $conexion;
    }

// Getters
public function getIdReserva() {
    return $this->id_reserva;
}

public function getIdUsuario() {
    return $this->id_usuario;
}

public function getIdInstalacion() {
    return $this->id_instalacion;
}

public function getIdTurno() {
    return $this->id_turno;
}

public function getFechaReserva() {
    return $this->fechaReserva;
}

public function getEstado() {
    return $this->estado;
}

// Setters
    public function setIdReserva($id_reserva) {
        $this->id_reserva = $id_reserva;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function setIdInstalacion($id_instalacion) {
        $this->id_instalacion = $id_instalacion;
    }

    public function setIdTurno($id_turno) {
        $this->id_turno = $id_turno;
    }

    public function setFechaReserva($fechaReserva) {
        $this->fechaReserva = $fechaReserva;
    }

    
    public function getFullnameUsuario() {
        // Aquí asumimos que $this->id_usuario es el ID del usuario asociado a la reserva
        $usuario = $this->obtenerUsuario(); 
        if ($usuario) {
            return $usuario->getFullname();
        } else {
            return null;
        }
    }

    public function getNombreInstalacion() {
        // Aquí asumimos que $this->id_instalacion es el ID de la instalación asociada a la reserva
        $instalacion = $this->obtenerInstalacion(); 
        if ($instalacion) {
            return $instalacion->getNombre();
        } else {
            return null;
        }
    }

    public function getHoraInicio() {
        // Aquí asumimos que $this->id_turno es el ID del turno asociado a la reserva
        $turno = $this->obtenerTurno(); 
        if ($turno) {
            return $turno->getHoraInicio();
        } else {
            return null;
        }
    }

    public function getHoraFin() {
        // Aquí asumimos que $this->id_turno es el ID del turno asociado a la reserva
        $turno = $this->obtenerTurno(); 
        if ($turno) {
            return $turno->getHoraFin();
        } else {
            return null;
        }
    }

    public function obtenerUsuario() {
        // Crear una instancia de la clase Conexion
        $conexion = new Conexion("BBDDPROYECTO");
        // Obtener la conexión
        $mysqli = $conexion->obtenerConexion();
        
        // Consulta SQL para obtener los datos del usuario asociado a la reserva
        $consulta = "SELECT * FROM usuarios WHERE id_usuario = ?";
        // Preparar la consulta
        $stmt = $mysqli->prepare($consulta);
        // Vincular parámetros
        $stmt->bind_param("i", $this->id_usuario);
        // Ejecutar la consulta
        $stmt->execute();
        // Obtener el resultado
        $resultado = $stmt->get_result();
        // Obtener los datos del usuario como un array asociativo
        $datosUsuario = $resultado->fetch_assoc();
        
        // Crear una instancia de Usuarios con los datos obtenidos
        $usuario = new Usuarios(
            $datosUsuario['id_usuario'],
            $datosUsuario['fullname'],
            $datosUsuario['email'],
            $datosUsuario['portal'],
            $datosUsuario['piso'],
            $datosUsuario['letra'],
            $datosUsuario['perfil'],
            $conexion
        );
    
        return $usuario;
    }
    
    
    public function obtenerInstalacion() {
        // Crear una instancia de la clase Conexion
        $conexion = new Conexion("BBDDPROYECTO");
        // Obtener la conexión
        $mysqli = $conexion->obtenerConexion();
        
        // Consulta SQL para obtener los datos de la instalación asociada a la reserva
        $consulta = "SELECT * FROM instalaciones WHERE id_instalacion = ?";
        // Preparar la consulta
        $stmt = $mysqli->prepare($consulta);
        // Vincular parámetros
        $stmt->bind_param("i", $this->id_instalacion);
        // Ejecutar la consulta
        $stmt->execute();
        // Obtener el resultado
        $resultado = $stmt->get_result();
        // Obtener la instalación como objeto
        $instalacion = $resultado->fetch_object();
    
        // Crear una instancia de Instalaciones con los datos obtenidos
        $instanciaInstalacion = new Instalaciones(
            $instalacion->id_instalacion,
            $instalacion->nombre,
            $conexion
        );
    
        return $instanciaInstalacion;
    }
    
    
    public function obtenerTurno() {
        // Crear una instancia de la clase Conexion
        $conexion = new Conexion("BBDDPROYECTO");
        // Obtener la conexión
        $mysqli = $conexion->obtenerConexion();
        
        // Consulta SQL para obtener los datos del turno asociado a la reserva
        $consulta = "SELECT id_turno, id_instalacion, hora_inicio, hora_fin FROM turnos WHERE id_turno = ?";

        // Preparar la consulta
        $stmt = $mysqli->prepare($consulta);
        // Vincular parámetros
        $stmt->bind_param("i", $this->id_turno);
        // Ejecutar la consulta
        $stmt->execute();
        // Obtener el resultado
        $resultado = $stmt->get_result();
        // Obtener el turno
        $turno = $resultado->fetch_object();
        // Crear una instancia de Usuarios con los datos obtenidos
        $turno = new Turnos(
            $turno->id_turno,
            $turno->id_instalacion,
            $turno->hora_inicio,
            $turno->hora_fin,
            $conexion
        );
    
        return $turno;
    }
    

// Método para crear una reserva
public function crearReserva($idUsuario, $id_instalacion, $id_turno, $fechaReserva) {
    try {
        // Verificar si la fecha y hora de la reserva son posteriores a la fecha y hora actuales
        $fechaHoraActual = new DateTime();
        $fechaHoraReserva = new DateTime($fechaReserva);
        if ($fechaHoraReserva < $fechaHoraActual) {
            throw new Exception('La fecha y hora de la reserva no pueden ser anteriores a la fecha y hora actuales.');
        }
        
        // Verificar si ya existe una reserva para la misma fecha, instalación y turno
        if ($this->comprobarReserva($id_instalacion, $id_turno, $fechaReserva)) {
            return array('success' => false, 'message' => 'Ya existe una reserva para esta fecha, instalación y turno.');
        }
        
        $conexion = $this->conexion->obtenerConexion();
        
        // Verificar si la conexión fue exitosa
        if ($conexion->connect_error) {
            throw new Exception('Error al conectar con la base de datos');
        }
        
        // Consulta SQL para insertar la reserva con fecha actual y estado "reservada"
        $query = "INSERT INTO reservas (id_usuario, id_instalacion, id_turno, estado, fechaReserva) 
                  VALUES (?, ?, ?, 'Reservado', ?)"; 
        
        // Preparar la consulta
        $stmt = $conexion->prepare($query);
        
        // Vincular parámetros
        $stmt->bind_param("iiis", $idUsuario, $id_instalacion, $id_turno, $fechaReserva);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Devolver una respuesta exitosa
            return array('success' => true, 'message' => 'Reserva creada exitosamente.');
        } else {
            // Lanzar una excepción en caso de error
            throw new Exception('Error al crear la reserva: ' . $stmt->error);
        }
    } catch (Exception $e) {
        // Capturar y manejar cualquier excepción que ocurra durante el proceso
        return array('success' => false, 'message' => $e->getMessage());
    }
}

    
// Método para verificar si ya existe una reserva para la misma fecha, instalación y turno
private function comprobarReserva($id_instalacion, $id_turno, $fechaReserva) {
    // Obtener la conexión a la base de datos
    $conexion = $this->conexion->obtenerConexion();
    
    // Consulta SQL para verificar la existencia de una reserva
    $query = "SELECT COUNT(*) AS total_reservas FROM reservas 
              WHERE id_instalacion = ? AND id_turno = ? AND fechaReserva = ? AND estado = 'Reservado'";
    
    // Preparar la consulta
    $stmt = $conexion->prepare($query);
    
    // Vincular parámetros
    $stmt->bind_param("iis", $id_instalacion, $id_turno, $fechaReserva);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado de la consulta
    $result = $stmt->get_result();
    
    // Obtener el número total de reservas encontradas
    $row = $result->fetch_assoc();
    $totalReservas = $row['total_reservas'];
    
// Agregar mensaje de depuración
if ($totalReservas > 0) {
    error_log("Ya existe una reserva para esta fecha, instalación y turno.");
} else {
    error_log("No existe reserva para esta fecha, instalación y turno.");
}

    // Devolver verdadero si ya existe al menos una reserva, falso si no existe ninguna
    return $totalReservas > 0;
}


    public function cancelarReserva($idReserva) {
        try {
            // Consulta SQL para cancelar la reserva actual y marcar el turno como disponible nuevamente
            $query = "UPDATE reservas
                      SET estado = 'Cancelado'
                      WHERE id_reserva = ? ";
    
            $conexion = $this->conexion->obtenerConexion();
    
            // Preparar la consulta
            $stmt = $conexion->prepare($query);
            // Vincular parámetros
            $stmt->bind_param("i", $idReserva);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // La reserva se canceló correctamente
                $response = array('success' => 'Reserva cancelada correctamente');
                echo json_encode($response);
                exit(); // Agrega exit() para detener la ejecución del script PHP
            } else {
                // Error al cancelar la reserva
                $response = array('error' => 'Error al cancelar la reserva: ' . $stmt->error);
                echo json_encode($response);
                exit(); // Agrega exit() para detener la ejecución del script PHP
            }                  
               
        } catch (Exception $e) {
            // Manejar cualquier excepción lanzada
        $response = array('error' => $e->getMessage());
        echo json_encode($response);
        exit(); // Agrega exit() para detener la ejecución del script PHP
        }
    }

    public function obtenerReservas($conexion){
        // Obtener la conexión mysqli
        $mysqli = $conexion->obtenerConexion();
        $consulta = "SELECT r.id_reserva, r.estado, r.id_usuario, u.fullname AS fullname_usuario,
                     r.id_instalacion, i.nombre AS nombre_instalacion,
                     r.id_turno, t.hora_inicio, t.hora_fin,
                     r.fechaReserva 
                     FROM reservas r
                     INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                     INNER JOIN instalaciones i ON r.id_instalacion = i.id_instalacion
                     INNER JOIN turnos t ON r.id_turno = t.id_turno
                     ORDER BY r.fechaReserva DESC";
        
        // Ejecuta la consulta
        $resultado = mysqli_query($mysqli, $consulta);
    
        // Verifica si hay resultados
        if ($resultado) {
            // Inicializa un array para almacenar las reservas
            $tablaReservas = [];
            // Recorre los resultados y crea objetos reservas para cada fila
            while ($fila = mysqli_fetch_assoc($resultado)) {
                // Crea una nueva instancia de Reservas con los datos obtenidos de la base de datos
                $reserva = new Reservas($fila['id_usuario'], $fila['id_reserva'], $fila['estado'], $fila['id_instalacion'], $fila['id_turno'], $fila['fechaReserva'], $conexion);
                
                // Agrega la reserva al array
                $tablaReservas[] = $reserva;
            }
            return $tablaReservas;
        } else {
            // array vacío si no hay resultados
            return [];
        }
    }  

    public static function obtenerReservasPorPagina($pagina, $numReservasPorPagina, $conexion) {
        // Calcular el índice de inicio de las reservas en esta página
        $inicio = ($pagina - 1) * $numReservasPorPagina;
    
        // Consulta SQL para obtener las reservas de esta página
        $consulta = "SELECT r.id_reserva, r.estado, r.id_usuario, u.fullname AS fullname_usuario,
                     r.id_instalacion, i.nombre AS nombre_instalacion,
                     r.id_turno, t.hora_inicio, t.hora_fin,
                     r.fechaReserva 
                     FROM reservas r
                     INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
                     INNER JOIN instalaciones i ON r.id_instalacion = i.id_instalacion
                     INNER JOIN turnos t ON r.id_turno = t.id_turno
                     ORDER BY r.fechaReserva DESC
                     LIMIT $inicio, $numReservasPorPagina";
        // Ejecutar la consulta
        $resultado = mysqli_query($conexion->obtenerConexion(), $consulta);
    
        // Verificar si hay resultados
        if ($resultado) {
            // Inicializar un array para almacenar las reservas de esta página
            $reservas = [];
    
            // Recorrer los resultados y crear objetos de reserva
            while ($fila = mysqli_fetch_assoc($resultado)) {
                // Crear una nueva instancia de Reserva con los datos obtenidos
                $reserva = new Reservas($fila['id_usuario'], $fila['id_reserva'], $fila['estado'], $fila['id_instalacion'], $fila['id_turno'], $fila['fechaReserva'], $conexion);
                
                // Agregar la reserva al array
                $reservas[] = $reserva;
            }
    
            return $reservas;
        } else {
            // Devolver un array vacío si no hay resultados
            return [];
        }
    }
    
}