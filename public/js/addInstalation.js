$(document).ready(function() {
    // Al hacer clic en el botón "Añadir Nueva Instalación"
    $('#btn-add').click(function() {
        // Ocultar los botones "Modificar" y "Eliminar" de la tabla de instalaciones
        $('.btn-modify').hide();
        $('.btn-delete').hide();        
        // Mostrar los campos para ingresar el nombre de la instalación y seleccionar el número de turnos
        $('#contenedor-campos').show();
        // Ocultar el botón "Añadir Nueva Instalación"
        $(this).hide();    
    });

    $('#cancelarButton').click(function() {             
        cargarInstalaciones('#tablaInstalaciones');   
    });

    // Manejar el evento clic del botón "Siguiente"
    $('#btn-siguiente').click(function() {
        // Obtener el número de turnos seleccionado por el usuario
        var numTurnos = parseInt($('#num-turnos').val());

        // Limpiar las filas de la tabla de turnos
        $('#filas-turnos').empty();

        // Generar dinámicamente las filas de la tabla de turnos según el número seleccionado
        for (var i = 0; i < numTurnos; i++) {
            var newRow = '<tr>' +
                            '<td><input type="time" class="hora_inicio"></td>' +
                            '<td><input type="time" class="hora_fin"></td>' +
                        '</tr>';
            $('#filas-turnos').append(newRow);
        }

        // Ocultar los campos de ingreso y el botón "Siguiente"
        $('#contenedor-campos').hide();

        // Mostrar la tabla para ingresar los horarios de los turnos
        $('#tabla-nueva-instalacion').show(); 
    });
    
    // Manejar el evento clic del botón "Aceptar"
$('#btn-agregar-instalacion').click(function() {
    // Obtener el nombre de la instalación
    var nombreInstalacion = $('#nombre-instalacion').val();

    // Crear un array para almacenar los datos de los turnos
    var turnos = [];

    // Iterar sobre cada fila de la tabla de turnos
    $('#tabla-nueva-instalacion tbody tr').each(function() {
        // Obtener los valores de hora inicio y hora fin de cada fila
        var horaInicio = $(this).find('.hora_inicio').val();
        var horaFin = $(this).find('.hora_fin').val();

        // Crear un objeto con los datos del turno y agregarlo al array
        turnos.push({
            horaInicio: horaInicio,
            horaFin: horaFin
        });
    });

    // Crear un objeto con los datos de la nueva instalación y sus turnos
    var nuevaInstalacion = {
        nombre: nombreInstalacion,
        turnos: turnos
    };
    console.log(nuevaInstalacion);
        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            url: '../controllers/adminAddInstalation.php',
            type: 'POST',
            data: { nuevaInstalacion: nuevaInstalacion },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Mostrar el mensaje de éxito
                    showMessage('Instalación agregada correctamente.');
                    // Recargar la tabla de instalaciones después de cerrar el mensaje
                    $('#tabla-nueva-instalacion').hide();
                    $('#btnCloseMessage').click(function() {             
                        cargarInstalaciones('#tablaInstalaciones');
                    });                  
                } else {
                    // Mostrar el mensaje de error
                    showMessage('Error al agregar la instalación: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
            }
        });
    });

// Función para mostrar mensajes en el modal
function showMessage(message) {
    // Actualizar el contenido del modal con el mensaje proporcionado
    $('#messageText').text(message);
    // Mostrar el modal
    $('#modalMessage').show();
}

// Clic boton cancelar de la modal
$('#buttonCancelar').click(function() {             
    cargarInstalaciones('#tablaInstalaciones');    
});
});

        

    