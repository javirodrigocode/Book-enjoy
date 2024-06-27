// Función para eliminar el usuario
function eliminarUsuario() {
    // Mostrar ventana modal de confirmación
    $('#modalConfirmacionEliminar').modal('show');

    // Cuando se haga clic en el botón de confirmar eliminar usuario
    $('#confirmarEliminar').on('click', function() {
        // Ocultar la ventana modal de confirmación
        $('#modalConfirmacionEliminar').modal('hide');


        // Obtener el ID del usuario de la cookie
        var userId = obtenerIdUsuarioDeCookie();

        // Enviar la solicitud AJAX para eliminar el usuario
        $.ajax({
            url: '../controllers/eliminarUsuario.php',
            type: 'POST',
            data: { idUsuario: userId },
            dataType: 'json',
            success: function(response) {
                // Si el usuario fue eliminado correctamente, mostrar ventana modal de usuario eliminado
                if (response.success) {
                    $('#modalConfirmacionEliminar').modal('show');
                } else {
                    alert('Hubo un error al intentar eliminar el usuario.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
            }
        });
    });

    // Cuando se haga clic en el botón de cancelar eliminar usuario
    $('#cancelarEliminar').on('click', function() {
        // Ocultar la ventana modal de confirmación
        $('#modalConfirmacionEliminar').modal('hide');
    });

    // Cuando se haga clic en el botón de confirmar redirección
    $('#usuarioEliminadoModal').on('click', function() {
        // Redirigir a la página de inicio
        window.location.href = '../views/index.php';
    });
}


