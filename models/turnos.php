<?php

class Turnos {
    private $id_turno;
    private $id_instalacion;
    private $hora_inicio;
    private $hora_fin;
    private $conexion;
    
    public function __construct($id_turno, $id_instalacion, $hora_inicio, $hora_fin, $conexion) {
        $this->id_turno = $id_turno;
        $this->id_instalacion = $id_instalacion;
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $hora_fin;
        $this->conexion = $conexion;
    }
    
    // Getters
    public function getIdTurno() {
        return $this->id_turno;
    }
    
    public function getIdInstalacion() {
        return $this->id_instalacion;
    }    
  
    public function getHoraInicio() {
        return $this->hora_inicio;
    }
    
    public function getHoraFin() {
        return $this->hora_fin;
    }
    
    // Setters
    public function setIdTurno($id_turno) {
        $this->id_turno = $id_turno;
    }
    
    public function setIdInstalacion($id_instalacion) {
        $this->id_instalacion = $id_instalacion;
    }
        
    public function setHoraInicio($hora_inicio) {
        $this->hora_inicio = $hora_inicio;
    }
    
    public function setHoraFin($hora_fin) {
        $this->hora_fin = $hora_fin;
    }

// Método para obtener los turnos asociados a una instalación específica
// Nuevo método para obtener los turnos asociados a una instalación específica
public function obtenerTurnoPorInstalacion($idInstalacion) {
    // Consulta SQL para obtener los datos del turno asociado a la reserva
    $consulta = "SELECT id_turno, id_instalacion, hora_inicio, hora_fin FROM turnos WHERE id_instalacion = ?";

    // Preparar la consulta
    $stmt = $this->conexion->obtenerConexion()->prepare($consulta);
    // Vincular parámetros
    $stmt->bind_param("i", $idInstalacion);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener el resultado
    $resultado = $stmt->get_result();

    // Crear un array para almacenar los turnos
    $listaTurnos = array();

    // Iterar sobre los resultados y crear objetos Turnos
    while ($turno = $resultado->fetch_object()) {
        // Crear objetos con las propiedades adecuadas
        $turnoObjeto = new stdClass();
        $turnoObjeto->id_turno = $turno->id_turno;
        $turnoObjeto->hora_inicio = $turno->hora_inicio;
        $turnoObjeto->hora_fin = $turno->hora_fin;

        // Agregar el objeto Turnos al array
        $listaTurnos[] = $turnoObjeto;
    }

    return $listaTurnos;
}


}

?>

