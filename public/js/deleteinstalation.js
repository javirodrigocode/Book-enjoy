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
            url: '../controllers/deleteInstalation.php',
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




    
