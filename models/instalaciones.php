<?php
include_once "conexion.php";
class Instalaciones {
    private $instalacion = [];
    private $id_instalacion;
    private $nombre;
    private $horarios;
    private $conexion;
    
    // Constructor
    public function __construct($id_instalacion, $nombre, Conexion $conexion) {
        $this->id_instalacion = $id_instalacion;
        $this->nombre = $nombre;
        $this->conexion = $conexion;
        $this->horarios = $this->obtenerHorarios(); // Llamamos al método obtenerHorarios para obtener los horarios;
    }

    // Getters
    public function getId_Instalacion() {
        return $this->id_instalacion;
    }

    public function getNombre() {
        return $this->nombre;
    }
    
    public function getHorarios() {
        return $this->horarios;
    }
    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Método para establecer los horarios de la instalación
    public function setHorarios($horarios) {
        $this->horarios = $horarios;
    }
    
// Método para obtener las instalaciones existentes
public function obtenerInstalaciones($conexion) {
    // Obtener la conexión mysqli
    $mysqli = $conexion->obtenerConexion();
    // Prepara la consulta SQL para seleccionar las instalaciones y sus horarios
    $consulta = "SELECT i.id_instalacion, i.nombre 
                 FROM instalaciones i";
    // Ejecuta la consulta
    $resultado = mysqli_query($mysqli, $consulta);

    // Verifica si hay resultados
    if ($resultado) {
        // Inicializa un array para almacenar las instalaciones
        $listaInstalaciones = [];
        // Recorre los resultados y crea objetos Instalaciones para cada fila
        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Crea una nueva instancia de Instalaciones
            $instalacion = new Instalaciones(
                $fila['id_instalacion'],
                $fila['nombre'],
                $conexion
            );
            // Obtén y agrega los horarios a la instalación actual
            $horarios = $instalacion->obtenerHorarios();
            // Agrega los horarios al objeto Instalaciones
            $instalacion->setHorarios($horarios);
            // Agrega la instalación a la lista
            $listaInstalaciones[] = $instalacion;
        }
        // Retorna la lista de instalaciones
        return $listaInstalaciones;
    } else {
        // Si no hay resultados, devuelve un array vacío o maneja el error de otra manera según tus necesidades
        return [];
    }
}



// Método para obtener los horarios de la instalación
public function obtenerHorarios() {
    // Inicializar un array para almacenar los horarios
    $horarios = [];

    // Obtener la conexión mysqli
    $mysqli = $this->conexion->obtenerConexion();

    // Consulta SQL para obtener los horarios de la instalación actual
    $consulta = "SELECT hora_inicio, hora_fin 
                 FROM turnos 
                 WHERE id_instalacion = ?";

    // Preparar la consulta
    $stmt = $mysqli->prepare($consulta);
    $stmt->bind_param("i", $this->id_instalacion); // 'i' indica que el parámetro es un entero

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado de la consulta
    $resultado = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($resultado) {
        // Recorrer los resultados y almacenar los horarios en el array
        while ($fila = $resultado->fetch_assoc()) {
            $horarios[] = array(
                'hora_inicio' => $fila['hora_inicio'],
                'hora_fin' => $fila['hora_fin']
            );
        }
    }

    // Cerrar la consulta
    $stmt->close();

    // Retornar los horarios obtenidos
    return $horarios;
}

public function obtenerInstalacionPorId($id_instalacion) {
    $mysqli = $this->conexion->obtenerConexion();
    $consulta = "SELECT id_instalacion, nombre FROM instalaciones WHERE id_instalacion = ?";
    $stmt = $mysqli->prepare($consulta);
    $stmt->bind_param("i", $id_instalacion);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $instalacion = $resultado->fetch_assoc();

    if ($instalacion) {
        // Crear una nueva instancia de Instalaciones con el id_instalacion
        $this->id_instalacion = $id_instalacion;

        // Obtener y agregar los horarios
        $instalacion['horarios'] = $this->obtenerHorarios();
    }

    return $instalacion;
}


public function añadirInstalacion($nombre, $horarios) {
    // Obtener la instancia de conexión mysqli
    $conexionMysqli = $this->conexion->obtenerConexion();

    // Iniciar una transacción
    $conexionMysqli->begin_transaction();

    try {
        // Consulta SQL para insertar una nueva instalación
        $sql_instalacion = "INSERT INTO instalaciones (nombre) VALUES (?)";

        // Preparar la consulta para insertar la instalación
        $statement_instalacion = $conexionMysqli->prepare($sql_instalacion);
        $statement_instalacion->bind_param('s', $nombre);

        // Ejecutar la consulta para insertar la instalación
        $resultado_instalacion = $statement_instalacion->execute();

        // Verificar si la inserción de la instalación fue exitosa
        if (!$resultado_instalacion || $statement_instalacion->affected_rows <= 0) {
            throw new Exception("Error al insertar la instalación");
        }

        // Obtener el ID de la instalación recién insertada
        $id_instalacion = $statement_instalacion->insert_id;

        // Cerrar la consulta de inserción de la instalación
        $statement_instalacion->close();

        // Consulta SQL para insertar los horarios en la tabla de turnos
        $sql_turnos = "INSERT INTO turnos (id_instalacion, hora_inicio, hora_fin) VALUES (?, ?, ?)";

        // Preparar la consulta para insertar los horarios
        $statement_turnos = $conexionMysqli->prepare($sql_turnos);

        // Iterar sobre los horarios y ejecutar la inserción en la tabla de turnos
        foreach ($horarios as $horario) {
            $hora_inicio = $horario['horaInicio'];
            $hora_fin = $horario['horaFin'];

            // Bind parameters
            $statement_turnos->bind_param("iss", $id_instalacion, $hora_inicio, $hora_fin);

            // Ejecutar la consulta para insertar el horario
            $resultado_turnos = $statement_turnos->execute();

            // Verificar si la inserción del horario fue exitosa
            if (!$resultado_turnos || $statement_turnos->affected_rows <= 0) {
                throw new Exception("Error al insertar los horarios");
            }
        }

        // Cerrar la consulta de inserción de los horarios
        $statement_turnos->close();

        // Confirmar la transacción
        $conexionMysqli->commit();

        // Si todo fue exitoso, regresar true
        return true;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexionMysqli->rollback();

        // Devolver false y manejar el error en la capa superior
        return false;
    }
}


   // Método para eliminar una instalación
public function eliminarInstalacion($id_instalacion) {
    // Verificar si se recibió un ID de instalación válido
    if (!is_numeric($id_instalacion) || $id_instalacion <= 0) {
        return false; // ID inválido
    }

    // Consulta SQL para eliminar la instalación con un statement preparado
    $sql = "DELETE FROM instalaciones WHERE id_instalacion = ?";

    // Obtener la instancia de conexión mysqli
    $conexionMysqli = $this->conexion->obtenerConexion();

    // Preparar la consulta con un statement preparado
    $stmt = $conexionMysqli->prepare($sql);

    // Verificar si la preparación de la consulta fue exitosa
    if (!$stmt) {
        return false; // Error al preparar la consulta
    }

    // Vincular el ID de instalación al statement preparado
    $stmt->bind_param("i", $id_instalacion);

    // Ejecutar la consulta
    $resultado = $stmt->execute();
    
    // Verificar si la consulta se ejecutó con éxito
    if ($resultado) {
        // Verificar si se eliminó alguna fila
        if ($stmt->affected_rows > 0) {
            // Cerrar el statement preparado
            $stmt->close();
            // La instalación se eliminó con éxito
            return true;
        } else {
            // Cerrar el statement preparado
            $stmt->close();
            // No se encontró ninguna instalación con el ID proporcionado
            return false;
        }
    } else {
        // Cerrar el statement preparado
        $stmt->close();
        // Error al ejecutar la consulta
        return false;
    }  
}

public function modificarInstalacion($id_instalacion, $horarios) {
    // Obtener la instancia de conexión mysqli
    $conexionMysqli = $this->conexion->obtenerConexion();
    // Iniciar una transacción
    $conexionMysqli->begin_transaction();

    try {
        
        // Consulta SQL para actualizar los horarios existentes
        $sql_actualizar_horarios = "UPDATE turnos SET hora_inicio = ?, hora_fin = ? WHERE id_instalacion = ?";

        // Preparar la consulta para actualizar los horarios
        $statement_actualizar_horarios = $conexionMysqli->prepare($sql_actualizar_horarios);

        // Iterar sobre los horarios y ejecutar la actualización en la tabla de turnos
        foreach ($horarios as $horario) {
            $hora_inicio = $horario['horaInicio'];
            $hora_fin = $horario['horaFin'];

            // Bind parameters
            $statement_actualizar_horarios->bind_param("ssi", $hora_inicio, $hora_fin, $id_instalacion);

            // Ejecutar la consulta para actualizar el horario
            $resultado_actualizar_horarios = $statement_actualizar_horarios->execute();

            // Verificar si la actualización del horario fue exitosa
            if (!$resultado_actualizar_horarios || $statement_actualizar_horarios->affected_rows <= 0) {
                throw new Exception("Error al actualizar los horarios");
            }
        }

        // Cerrar la consulta de actualización de horarios
        $statement_actualizar_horarios->close();

        // Confirmar la transacción
        $conexionMysqli->commit();

        // Si todo fue exitoso, regresar true
        return true;
    } catch (Exception $e) {
            
        // Revertir la transacción en caso de error
        $conexionMysqli->rollback();

        // Devolver false y manejar el error en la capa superior
        return false;
    }
}
}