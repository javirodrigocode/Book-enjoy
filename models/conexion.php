<?php

class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $contrasena = ""; // No recomendado para producción
    private $baseDatos = "BBDDPROYECTO";
    private $conexion;

    // Constructor de la clase
    public function __construct($baseDatos) {
        $this->baseDatos = $baseDatos;
        $this->conectar();
    }

    // Método privado para conectar a la base de datos
    private function conectar() {
        $this->conexion = mysqli_connect($this->host, $this->usuario, $this->contrasena, $this->baseDatos);

        if (!$this->conexion) {
            throw new Exception("Error al conectar con la base de datos: " . mysqli_connect_error());
        }
    }

    // Método para obtener la conexión
    public function obtenerConexion(): mysqli {
        return $this->conexion;
    }

    // Método para cerrar la conexión
    public function cerrarConexion() {
        mysqli_close($this->conexion);
    }
}

