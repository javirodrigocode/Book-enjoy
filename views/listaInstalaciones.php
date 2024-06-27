<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/stylesAdminSection.css">
    <title>Instalaciones existentes</title>
</head>
<body>

<?php
require_once "../models/Instalaciones.php";
// Crear una instancia de la clase Conexion
$conexion = new Conexion("BBDDPROYECTO");

// Obtener la lista de instalaciones usando la conexión
$instalacion = new Instalaciones(null, null, $conexion );
$listaInstalaciones = $instalacion->obtenerInstalaciones($conexion);            
?>          
   
<!-- Generar la tabla instalaciones -->
<table id="tabla-instalaciones" border="1">
    <tr>
        <th>ID</th>
        <th>Nombre de la instalación</th>
        <th>Horarios</th>  
        <th>Acciones</th>     
    </tr>
    <?php foreach ($listaInstalaciones as $instalacion): ?>
        <tr data-instalacionid="<?= $instalacion->getId_Instalacion() ?>">            
            <td><?= $instalacion->getId_Instalacion() ?></td>
            <td class="editable"><?= $instalacion->getNombre() ?></td>
            <td>
            <?php foreach ($instalacion->getHorarios() as $indice => $horario): ?>
            <div class="horario">
                Turno <?= $indice + 1 ?>: 
                <span class="hora_inicio"><?= date('H:i', strtotime($horario['hora_inicio'])) ?></span> - 
                <span class="hora_fin"><?= date('H:i', strtotime($horario['hora_fin'])) ?></span>
            </div>    
            <?php endforeach; ?>      
            </td>
            <td>
                <!-- Botón Modificar -->
                <button class="btn btn-primary btn-sm mr-2 btn-modify" 
                data-instalacionid="<?= $instalacion->getId_Instalacion() ?>"
                data-nombreinstalacion="<?= $instalacion->getNombre() ?>">Modificar</button>
                <!-- Botón Eliminar -->
                <button class="btn btn-danger btn-sm btn-delete" data-instalacionid="<?= $instalacion->getId_Instalacion() ?>">Eliminar</button>
            </td>                                  
        </tr>
    <?php endforeach; ?> 
    <tfoot>
        <tr>
            <td colspan="8">
                <div class="container mt-3" style="background-color: #caf0f8; padding: 10px;">                  
                  <button class="btn btn-primary btnAgregar" id="btn-add">Añadir Nueva Instalación</button>                  
                    <div class="pagination paginacion"></div>
                </div>
            </td>
        </tr>
    </tfoot>       
</table>

<!-- Modal para modificar instalaciones --> 
<div id="modalModificarInstalacion" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Modificar Instalación</h2>
        <form id="formModificarInstalacion">
            <label for="nombre-instalacion">Nombre de la Instalación:</label>
            <input type="text" id="nombre-instalacion-modificar" class="form-control mb-3" readonly>

            
            <label for="hora-inicio">Hora de Inicio:</label>
            <input type="time" id="hora-inicio" class="form-control mb-3">
            
            <label for="hora-fin">Hora de Fin:</label>
            <input type="time" id="hora-fin" class="form-control mb-3">
            
            <input type="hidden" id="id_instalacion_modificar">
                       
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <button type="button" id="cancelarModificarInstalacion" class="btn btn-secondary">Cancelar</button>
        </form>
    </div>
</div>


<!-- Contenedor para añadir una instalacion -->
<div id="contenedor-campos" style="display: none;" class="container my-5 p-3 rounded">
    <label for="nombre-instalacion">Nombre de la Instalación:</label>
    <input type="text" id="nombre-instalacion" class="form-control mb-3">
    <label for="num-turnos">Número de Turnos:</label>
    <select id="num-turnos" class="form-control mb-3">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>  
    </select>
    <button id="btn-siguiente" class="btn btn-primary">Siguiente</button>
    <button id="cancelarButton" class="btn btn-secondary">Cancelar</button>
</div>

<!-- Tabla para añadir los turnos a la nueva instalacion -->
<div id="tabla-nueva-instalacion" style="display: none;" class="container my-5 p-3 rounded bg-white">
    <table id="tabla-turnos" class="table">
        <thead>
            <tr>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
            </tr>
        </thead>
        <tbody id="filas-turnos"></tbody>    
    </table>
    <button id="btn-agregar-instalacion" class="btn btn-success">Aceptar</button>
    <button id="buttonCancelar" class="btn btn-secondary">Cancelar</button>
</div>


<!-- Ventana modal para eliminar una instalacion -->
<div id="modalDelete" class="modal modal-delete" style="display: none;" class="container my-5 p-3 rounded bg-white">
    <div class="modal-content-delete">
        <p>¿Estás seguro de querer eliminar la instalación <span id="nombre-instalacion-confirmacion"></span>?</p>    
        <button id="btnConfirmEliminar" class="btn btn-danger">Confirmar</button>
        <button id="btnCancelEliminar" class="btn btn-secondary">Cancelar</button> 
    </div>   
</div>

<!-- Modal para mensajes -->
<div id="modalMessage" class="modal modal-message" style="display: none";>
    <div class="modal-content-message">
        <p id="messageText"></p>
        <button id="btnCloseMessage" class="btn btn-secondary">Cerrar</button>
    </div>
</div>

<button class="saveButton btn btn-primary" style="display: none;">Guardar nueva instalación</button>
<button class="cancelButton btn btn-secondary" style="display: none;">Cancelar</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>       
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../public/js/modifyInstalation.js"></script>
<script src="../public/js/script.js"></script>
<script src="../public/js/deleteinstalation.js"></script>
<script src="../public/js/addInstalation.js"></script>
<script src="../public/js/accionesAdminUsuarios.js"></script>

</body>
</html>