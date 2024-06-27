<?php
include '../models/conexion.php';
include '../models/turnos.php';

// Verificar si se ha enviado la fecha y la instalación seleccionadas
if (!isset($_POST['fecha']) || !isset($_POST['instalacion'])) {
    echo json_encode(array('error' => 'No se han proporcionado la fecha o la instalación'));
    exit(); // Salir del script si no se han proporcionado la fecha o la instalación
}

$fecha = $_POST['fecha'];
$instalacion = $_POST['instalacion'];

$baseDatos = "BBDDPROYECTO";
// Crear una instancia de la clase Conexion
$conexion = new Conexion($baseDatos);
// Obtener la conexión
$db = $conexion->obtenerConexion();

// Verificar si se obtuvo la conexión correctamente
if (!$db) {
    echo json_encode(array('error' => 'Error al conectar con la base de datos'));
    exit(); // Salir del script si hay un error de conexión
}

// Consulta SQL para obtener todos los turnos disponibles o reservados (no cancelados) para una fecha y una instalación específica
$query = "SELECT t.id_turno, t.id_instalacion, t.hora_inicio, t.hora_fin, IF(r.estado IS NOT NULL, r.estado, 'disponible') AS estado
          FROM turnos t
          LEFT JOIN reservas r ON t.id_turno = r.id_turno AND DATE(r.fechaReserva) = ? AND r.id_instalacion = ?
          WHERE t.id_instalacion = ? AND (r.estado IS NULL OR r.estado IN ('reservado', 'cancelado', 'disponible'))";


// Preparar la consulta
$stmt = $db->prepare($query);
$stmt->bind_param("sss", $fecha, $instalacion, $instalacion); // Aquí deberías usar $instalacion dos veces, una vez para el JOIN y otra para el WHERE
// Ejecutar la consulta
$stmt->execute();    
// Obtener el resultado de la consulta
$result = $stmt->get_result();

$turnos = array();     

// Recorrer los resultados de la consulta
while ($row = $result->fetch_assoc()) {
    // Verificar si el estado es nulo y establecerlo como "disponible" por defecto
    if ($row['estado'] === null) {
        $row['estado'] = 'disponible';
    }
    // Agregar el turno al array de turnos
    $turno = array(
        'id_turno' => $row['id_turno'],
        'id_instalacion' => $row['id_instalacion'],
        'estado' => $row['estado'],
        'hora_inicio' => $row['hora_inicio'],
        'hora_fin' => $row['hora_fin']
    );
    $turnos[] = $turno;
}

// Devolver los turnos disponibles como respuesta en formato JSON
echo json_encode($turnos);
?>


