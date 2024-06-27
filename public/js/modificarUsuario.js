// Cuando el documento esté listo
$(document).ready(function() {
    // Ocultar inicialmente los botones de guardar y cancelar
    $('#guardarButton').hide();
    $('#cancelarButton').hide();

    // Manejo de eventos de clic para el botón modificar
    $('#modificarButton').on('click', function() {
        modificarUsuario();
    });

    // Manejo de eventos de clic para el botón guardar
    $('#guardarButton').on('click', function() {
        guardarCambios();
    });

    // Manejo de eventos de clic para el botón cancelar
    $('#cancelarButton').on('click', function() {
        // Redirigir a la página de inicio
        window.location.href = '../views/mainpageusers.php';
    });

    // Manejar el clic en el botón "Aceptar" dentro de la ventana modal de cambios guardados
    $('#cambiosGuardadosModal .btn-primary').on('click', function() {
        // Ocultar la ventana modal de cambios guardados
        $('#cambiosGuardadosModal').modal('hide');
        // Recargar la página para mostrar los cambios actualizados en la tabla
        location.reload();
    });
});

// Función para modificar el usuario
function modificarUsuario() {
    // Habilitar la edición de las celdas
    $('.editable').attr('contenteditable', 'true').addClass('active');

    // Ocultar el botón de modificar original
    $('#modificarButton').hide();

    // Mostrar los botones de guardar y cancelar    
    $('#guardarButton').show();
    $('#cancelarButton').show();

    // Ocultar el botón de eliminar usuario
    $('#eliminarButton').hide();
}

// Función para mostrar mensajes en el modal
function showMessage(message) {
    // Actualizar el contenido del modal con el mensaje proporcionado
    $('#messageText').text(message);
    // Mostrar el modal
    $('#modalMessage').modal('show');
}

function guardarCambios() {
    // Obtener el ID del usuario de la cookie
    var userId = obtenerIdUsuarioDeCookie();
    
    // Obtener los datos modificados del usuario
    var newFullname = $('#nombreInput').text();
    var newEmail = $('#emailInput').text();
    var newPortal = $('#portalInput').text();
    var newPiso = $('#pisoInput').text();
    var newLetra = $('#letraInput').text();

    // Validar los datos
    if (!/^[a-zA-Z\s]+$/.test(newFullname)) {
        showMessage('Datos inválidos en Nombre.');
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(newEmail)) {
        showMessage('El formato del correo electrónico no es válido.');
        return;
    }
    if (!/^\d+$/.test(newPortal)) {
        showMessage('El portal solo puede contener números.');
        return;
    }
    if (!/^[a-zA-Z0-9]+$/.test(newPiso)) {
        showMessage('El piso solo puede contener letras y números.');
        return;
    }
    if (!/^[a-zA-Z]+$/.test(newLetra)) {
        showMessage('Datos inválidos en Letra.');
        return;
    }

    // Enviar la solicitud AJAX para modificar el usuario
    $.ajax({
        url: '../controllers/modificarUsuario.php',
        type: 'POST',
        data: { 
            idUsuario: userId,
            newFullname: newFullname,
            newEmail: newEmail,
            newPortal: newPortal,
            newPiso: newPiso,
            newLetra: newLetra
        },
        dataType: 'json',
        success: function(response) {
            // Si el usuario ha sido modificado correctamente, mostrar ventana modal de usuario modificado
            if (response.success) {                
                // Actualizar los datos mostrados en la tabla
                $('#nombreInput').text(newFullname);
                $('#emailInput').text(newEmail);
                $('#portalInput').text(newPortal);
                $('#pisoInput').text(newPiso);
                $('#letraInput').text(newLetra);
                $('#cambiosGuardadosModal').modal('show');
            } else {
                // Mostrar mensaje de error
                showMessage(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
            showMessage('Error en la solicitud: ' + error);
        }
    });
}




