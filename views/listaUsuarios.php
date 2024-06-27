<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../public/css/stylesAdminSection.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>       
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>   
    <title>Listado de Usuarios</title>
</head>
<body>

<?php
require_once "../models/Usuarios.php";
// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Obtener la lista de usuarios usando la conexión
$usuarios = new Usuarios(null, null, null, null, null, null, null, $conexion );
$tablaUsuarios = $usuarios->obtenerUsuarios($conexion); 
?>          

<!-- Generar la tabla de usuarios -->
<div class="container-fluid d-flex justify-content-center">
<table  id="tablaUsuarios" border="1">
    <thead >
        <tr>
            <th>Seleccionar</th> <!-- Columna para casilla de verificacion-->
            <th>ID</th>
            <th>Nombre</th>
            <th>Portal</th>
            <th>Piso</th>
            <th>Letra</th>
            <th>Email</th>
            <th>Perfil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tablaUsuarios as $usuario): ?>
            <tr data-userId="<?= $usuario->getId_Usuario() ?>">
                <td><input type="checkbox" class="seleccionar-usuario" value="<?= $usuario->getId_Usuario() ?>"></td> <!-- Casilla de verificación -->
                <td><?= $usuario->getId_Usuario() ?></td>
                <td class="editable" data-columna="nombre"><?= $usuario->getFullname() ?></td>
                <td class="editable" data-columna="portal"><?= $usuario->getPortal() ?></td>
                <td class="editable" data-columna="piso"><?= $usuario->getPiso() ?></td>
                <td class="editable" data-columna="letra"><?= $usuario->getLetra() ?></td>
                <td class="editable" data-columna="email"><?= $usuario->getEmail() ?></td>
                <td class="editable" data-columna="perfil"><?= $usuario->getPerfil() ?></td>            
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8">
                <div class="container mt-3">
                    <button class="btn btn-success" id="btn-add">Añadir</button>
                    <button class="btn btn-primary" id="btn-modificar">Modificar</button>
                    <button class="btn btn-danger" id="btn-eliminar">Eliminar</button>
                    <button class="btn btn-primary guardarButton" style="display: none;">Guardar</button>
                    <button class="btn btn-secondary cancelarButton" style="display: none;">Cancelar</button>
                    <div class="pagination paginacion"></div>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
</div>

<!-- Modal para modificar -->
<div id="modalModify" class="modal" style="display: none;">      
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Estás seguro de querer guardar los cambios?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btnGuardar">Confirmar cambios</button>
                <button class="btn btn-secondary" id="btnCancelModify" data-dismiss="modal">Cancelar</button>    
            </div>
        </div>
    </div>
</div>

<div id="modalEliminar" class="modal" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Estás seguro de querer eliminar los usuarios seleccionados?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="btnConfirmarEliminar">Confirmar</button>
                <button class="btn btn-secondary" id="btnCancelarEliminar" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de éxito -->
<div id="modalExito" class="modal" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>El usuario se ha añadido con éxito</p>    
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de alerta personalizado -->
<div id="modalAlert" class="modal" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advertencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalAlertMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    

<script src="../public/js/adminUsers.js"></script>
<script src="../public/js/accionesAdminUsuarios.js"></script>
<script src="../public/js/script.js"></script> <!-- Asegúrate de cargar script.js después de la definición de la tabla -->
<script>
$(document).ready(function() {
    // Datos para paginar
    var datosUsuarios = <?php echo json_encode($tablaUsuarios); ?>;
    var elementosPorPagina = 10;
    var tablaUsuarios = $('#tablaUsuarios');

    // Llamar a la función de paginación para la tabla de usuarios
    manejarPaginacion(tablaUsuarios, elementosPorPagina, datosUsuarios);
});
</script>

</body>
</html>
