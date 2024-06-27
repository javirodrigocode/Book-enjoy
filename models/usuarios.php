<?php
include_once "conexion.php";
class Usuarios {
    private $usuario = [];
    private $id_Usuario;
    private $fullname;
    private $email;
    private $portal;
    private $piso;
    private $letra;
    private $perfil;    
    private $conexion; 

    // Constructor
    public function __construct($id_Usuario, $fullname, $email, $portal, $piso, $letra, $perfil, Conexion $conexion) {
        $this->id_Usuario = $id_Usuario;
        $this->fullname = $fullname;       
        $this->email = $email;
        $this->portal = $portal;
        $this->piso = $piso;
        $this->letra = $letra;
        $this->perfil = $perfil;  
        $this->conexion = $conexion;      
    }

    // Getters
    public function getId_Usuario() {
        return $this->id_Usuario;
    }

    public function getFullname() {
        return $this->fullname;
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function getPortal() {
        return $this->portal;
    }

    public function getPiso() {
        return $this->piso;
    }

    public function getLetra() {
        return $this->letra;
    }

    public function getPerfil() {
        return $this->perfil;
    }

    // Setters
    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPortal($portal) {
        $this->portal = $portal;
    }

    public function setPiso($piso) {
        $this->piso = $piso;
    }
    public function setLetra($letra) {
        $this->letra = $letra;
    }


    public function setPerfil($perfil) {
        $this->perfil = $perfil;
    }


    // Método para obtener todos los usuarios paginados
    public function obtenerUsuarios($conexion) {
        // Obtener la conexión mysqli
        $mysqli = $conexion->obtenerConexion();        
            
        // Prepara la consulta SQL para seleccionar usuarios con paginación
        $consulta = "SELECT * FROM usuarios";
    
        // Ejecuta la consulta
        $resultado = mysqli_query($mysqli, $consulta);
    
        // Verifica si hay resultados
        if ($resultado) {
            // Inicializa un array para almacenar los usuarios
            $tablaUsuarios = [];
    
            // Recorre los resultados y crea objetos de usuario para cada fila
            while ($fila = mysqli_fetch_assoc($resultado)) {
                // Crea un nuevo objeto Usuario y añádelo al array
                $usuario = new Usuarios(
                    $fila['id_usuario'],
                    $fila['fullname'],
                    $fila['email'],
                    $fila['portal'],
                    $fila['piso'],
                    $fila['letra'],
                    $fila['perfil'], 
                    $conexion               
                );
                $tablaUsuarios[] = $usuario;
            }
    
            // Retorna la lista de usuarios
            return $tablaUsuarios;
        } else {
            // Si no hay resultados, devuelve un array vacío o maneja el error de otra manera según tus necesidades
            return [];
        }
    }
    

    
    // Método para añadir un nuevo usuario a la base de datos// Método para añadir un nuevo usuario a la base de datos
public function añadirUsuario($datosUsuario) {
    $fullname = $datosUsuario['fullname'];
    $portal = $datosUsuario['portal'];
    $piso = $datosUsuario['piso'];
    $letra = $datosUsuario['letra'];
    $email = $datosUsuario['email'];
    $perfil = $datosUsuario['perfil'];

    // Consulta SQL para verificar si ya existe un usuario con la misma combinación de portal, piso y letra
    $sql_verificar = "SELECT COUNT(*) AS total FROM usuarios WHERE portal = ? AND piso = ? AND letra = ?";
    
    // Obtener la instancia de conexión mysqli
    $conexionMysqli = $this->conexion->obtenerConexion();
    
    // Preparar la consulta para verificar
    $statement_verificar = $conexionMysqli->prepare($sql_verificar);
    $statement_verificar->bind_param('sss', $portal, $piso, $letra);
    
    // Ejecutar la consulta de verificación
    $statement_verificar->execute();
    
    // Obtener el resultado de la consulta de verificación
    $resultado_verificar = $statement_verificar->get_result();
    $fila = $resultado_verificar->fetch_assoc();
    
    // Verificar si ya existe un usuario con la misma combinación de portal, piso y letra
    if ($fila['total'] > 0) {
        // Si ya existe un usuario con la misma combinación de portal, piso y letra, devolver false
        return [
            'success' => false,
            'message' => "Ya existe un usuario con la misma dirección."
        ];
    }
    
    // Consulta SQL parametrizada para insertar un nuevo usuario
    $sql_insertar = "INSERT INTO usuarios (fullname, portal, piso, letra, email, perfil)
                     VALUES (?, ?, ?, ?, ?, ?)";
    
    // Preparar la consulta para insertar
    $statement_insertar = $conexionMysqli->prepare($sql_insertar);
    $statement_insertar->bind_param('ssssss', $fullname, $portal, $piso, $letra, $email, $perfil);
    
    // Ejecutar la consulta para insertar
    $resultado_insertar = $statement_insertar->execute();
    
    // Verificar si la consulta de inserción se ejecutó con éxito
    if ($resultado_insertar) {
        return [
            'success' => true,
            'message' => "Usuario agregado correctamente."
        ];
    } else {
        // En caso de error, manejar el error y devolver false
        error_log("Error al agregar usuario: " . $statement_insertar->error);
        return [
            'success' => false,
            'message' => "Hubo un error al intentar agregar el usuario."
        ];
    }
}

    


public function modificarUsuarios($idUsuarios, $newFullnames, $newPortales, $newPisos, $newLetras, $newEmails, $newPerfiles) {
    // Verificar si $idUsuarios es un array o un solo valor
    if (!is_array($idUsuarios)) {
        // Si es un solo valor, conviértelo en un array de un solo elemento
        $idUsuarios = array($idUsuarios);
    }

    $baseDatos = "BBDDPROYECTO";
    // Crear una instancia de la clase Conexion
    $conexion = new Conexion($baseDatos);
    // Obtener la conexión
    $db = $conexion->obtenerConexion();

    // Verificar si la conexión se estableció correctamente
    if ($db) {
        try {
            // Preparar la consulta SQL para modificar los datos del usuario
            $query = "UPDATE usuarios SET fullname = ?, portal = ?, piso = ?, letra = ?, email = ?, perfil = ? WHERE id_usuario = ?";
            $statement = $db->prepare($query);

            // Iterar sobre los usuarios a modificar
            foreach ($idUsuarios as $index => $idUsuario) {
                // Validar los datos
                if (!preg_match('/^[a-zA-Z\s]+$/', $newFullnames[$index])) {
                    return ["success" => false, "message" => "Fullname no válido"];
                }
                if (!preg_match('/^\d+$/', $newPortales[$index])) {
                    return ["success" => false, "message" => "Portal no válido"];
                }
                if (!preg_match('/^[a-zA-Z0-9]+$/', $newPisos[$index])) {
                    return ["success" => false, "message" => "Piso no válido"];
                }
                if (!preg_match('/^[a-zA-Z]+$/', $newLetras[$index])) {
                    return ["success" => false, "message" => "Letra no válida"];
                }
                if (!filter_var($newEmails[$index], FILTER_VALIDATE_EMAIL)) {
                    return ["success" => false, "message" => "Email no válido"];
                }
                if (!preg_match('/^[a-zA-Z]+$/', $newPerfiles[$index])) {
                    return ["success" => false, "message" => "Perfil no válido"];
                }

                // Vincular los parámetros
                $statement->bind_param('ssssssi', $newFullnames[$index], $newPortales[$index], $newPisos[$index], $newLetras[$index], $newEmails[$index], $newPerfiles[$index], $idUsuario);

                // Ejecutar la consulta
                $resultado = $statement->execute();

                // Verificar si alguna consulta falló
                if (!$resultado) {
                    return ["success" => false, "message" => "Error al modificar el usuario con ID $idUsuario"];
                }
            }

            return ["success" => true, "message" => "Usuarios modificados correctamente"];
        } catch (mysqli_sql_exception $e) {
            // Manejar cualquier error de base de datos
            error_log("Error al modificar usuarios: " . $e->getMessage());
            return ["success" => false, "message" => "Error en la base de datos"];
        }
    } else {
        // No se pudo establecer la conexión a la base de datos
        return ["success" => false, "message" => "Error de conexión a la base de datos"];
    }
}

    

// Método para eliminar usuarios
public function eliminarUsuarios($idsUsuarios) {
    // Verificar si se recibieron IDs de usuarios válidos
    if (!is_array($idsUsuarios) || empty($idsUsuarios)) {
        return false;
    }

    // Convertir los IDs de usuarios a una lista separada por comas
    $idUsuarioStr = implode(',', array_map('intval', $idsUsuarios));

    // Consulta SQL para eliminar los usuarios con los IDs proporcionados
    $sql = "DELETE FROM usuarios WHERE id_usuario IN ($idUsuarioStr)";

    // Obtener la instancia de conexión mysqli
    $conexionMysqli = $this->conexion->obtenerConexion();
    
    // Ejecutar la consulta SQL
    $resultado = $conexionMysqli->query($sql);

    // Verificar si la consulta se ejecutó con éxito
    if ($resultado) {
        return true; // La operación fue exitosa
    } else {
        return false; // Hubo un error
    }
}
}


    

   
