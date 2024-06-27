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
            url: '../controllers/adminModifyInstalation.php',
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

