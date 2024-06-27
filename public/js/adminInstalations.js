// Funcionalidad para añadir instalaciones
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

        
// Funcionalidad para eliminar instalaciones
$(document).ready(function() {
    var idInstalacion; // Variable para almacenar el ID de la instalación a eliminar

    // Función para mostrar el modal
    function showModal(modalId) {
        $(modalId).css('display', 'block');
    }

    // Función para ocultar el modal
    function hideModal(modalId) {
        $(modalId).css('display', 'none');
    }

    // Manejar el clic en el botón "Eliminar"
    $('.btn-delete').click(function() {
        console.log('Botón de eliminar clicado');
        // Obtener el ID de la instalación desde el botón "Eliminar" correspondiente
        idInstalacion = $(this).data('instalacionid');
        console.log('ID de instalación a eliminar:', idInstalacion);
        // Obtener el nombre de la instalación desde la fila correspondiente
        var nombreInstalacion = $(this).closest('tr').find('.editable').text();
        // Mostrar el nombre de la instalación en la ventana modal
        $('#nombre-instalacion-confirmacion').text(nombreInstalacion);        
        // Mostrar la ventana modal
        showModal('#modalDelete');
    });

    // Manejar el clic en el botón "Cancelar" de la ventana modal
    $('#btnCancelEliminar').click(function() {
        // Ocultar la ventana modal
        hideModal('#modalDelete');
    });

    // Manejar el clic en el botón "Confirmar" de la ventana modal
    $('#btnConfirmEliminar').click(function() {
        $.ajax({
            url: 'deleteInstalation.php',
            type: 'POST',
            data: { idInstalacion: idInstalacion },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage('Instalación eliminada correctamente.');                    
                } else {
                    showMessage('Error al eliminar la instalación: ' + response.message);
                    console.error('Error al eliminar la instalación:', response.message);
                }
                hideModal('#modalDelete');
            }
        });
    });

    // Evento click para cerrar el modal de mensajes
    $('#btnCloseMessage').click(function() {
        hideModal('#modalMessage');
        cargarInstalaciones('#tablaInstalaciones');
    });

    // Función para mostrar mensajes
    function showMessage(message) {
        $('#messageText').text(message);
        showModal('#modalMessage');
    }
});


//Funcionalidad para modificar instalaciones
$(document).ready(function() {
    var nombreInstalacion; // Variable para almacenar el nombre de la instalación

    // Función para manejar el evento de clic en el botón "Modificar"
    $('.btn-modify').click(function() {
        // Obtener el ID de la instalación a modificar
        var instalacionId = $(this).data('instalacionid');
        console.log("ID de la instalación a modificar:", instalacionId);
        
        // Obtener el nombre de la instalación de los datos del botón
        nombreInstalacion = $(this).data('nombreinstalacion');
        console.log("Nombre de la instalación a modificar:", nombreInstalacion);
        
        // Obtener el horario actual de la instalación
        var horarioInicioActual = $(this).closest('tr').find('.hora_inicio').text();
        var horarioFinActual = $(this).closest('tr').find('.hora_fin').text();
        
        // Actualizar los valores del formulario de modificación
        $('#id_instalacion_modificar').val(instalacionId);
        $('#nombre-instalacion-modificar').val(nombreInstalacion);
        $('#hora-inicio').val(horarioInicioActual);
        $('#hora-fin').val(horarioFinActual);
        
        // Mostrar el modal de modificación
        $('#modalModificarInstalacion').show();
    });

    // Función para manejar el evento de envío del formulario de modificación
    $('#formModificarInstalacion').submit(function(event) {
        // Evitar que se envíe el formulario de manera predeterminada
        event.preventDefault();
        
        // Obtener los datos del formulario de modificación
        var id_instalacion = $('#id_instalacion_modificar').val();
        var hora_inicio = $('#hora-inicio').val();
        var hora_fin = $('#hora-fin').val();
        
        
        var datosModificacion = {
            id_instalacion: id_instalacion,
            horarios: [
                {
                    horaInicio: hora_inicio,
                    horaFin: hora_fin
                }
            ]
        };
        console.log("Datos de modificación:", datosModificacion);

        // Enviar los datos al servidor utilizando AJAX
        $.ajax({
            url: 'adminModifyInstalation.php',
            type: 'POST',
            data: JSON.stringify(datosModificacion),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Si la modificación fue exitosa, cerrar el modal y actualizar la tabla
                    $('#modalModificarInstalacion').hide();
                    cargarInstalaciones('#tablaInstalaciones');
                } else {
                    // Si hubo un error en la modificación, mostrar un mensaje de error
                    alert('Error al modificar la instalación: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Si ocurrió un error en la solicitud AJAX, mostrar un mensaje de error
                console.error('Error en la solicitud:', error);
            }
        });
    });

    // Función para manejar el evento de clic en el botón "Cancelar"
    $('#cancelarModificarInstalacion').click(function() {
        // Ocultar el modal de modificación
        $('#modalModificarInstalacion').hide();
    });
});

