document.addEventListener('DOMContentLoaded', function() {
    // Agregar un controlador de eventos al botón "Reservar"
    document.getElementById('botonReservar').addEventListener('click', function() {
        // Obtener el ID del turno seleccionado
        var selectedTurno = document.querySelector('#listaTurnos li.selected');
        if (selectedTurno){
            var id_turno = selectedTurno.getAttribute('data-idTurno');
        } else {
            console.error('No se ha seleccionado ningún turno');
        }
        var fechaReserva = window.fechaReserva; // Acceder a la variable global fechaReserva desde el objeto window
        var selectedInstalacion = document.getElementById('instalacion').value;
        // Obtener el ID del usuario de la cookie
        var idUsuario = obtenerIdUsuarioDeCookie();       

        // Enviar la solicitud AJAX para crear la reserva
        $.ajax({
            url: '../controllers/crearReservas.php',
            type: 'POST',
            data: { 
                id_turno: id_turno,
                fechaReserva: fechaReserva,
                selectedInstalacion: selectedInstalacion,
                id_usuario: idUsuario            
            },
            dataType: 'json',
            success: function(response) {
                // Verificar si la reserva se guardó correctamente
                if (response.success) {
                    // Mostrar la ventana modal de reserva creada
                    $('#modalReserva').modal('show');
                } else {
                    // Mostrar un mensaje de error si hubo algún problema al guardar la reserva
                    alert('Hubo un error al realizar la reserva. Por favor, inténtalo de nuevo.');
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud
                console.error('Error en la solicitud AJAX:', error);
                alert('Hubo un error en la solicitud AJAX. Por favor, inténtalo de nuevo.');
            }
        });
    });

    // Agregar un controlador de eventos al botón "Cancelar" para cerrar el listado de turnos y redirigir
    document.getElementById('botonCancelar').addEventListener('click', function() {
        // Ocultar el listado de turnos
        document.getElementById('listaTurnos').style.display = 'none';
        // Redirigir a la página principal de usuarios
        window.location.href = '../views/mainPageUsers.php';
    });

    // Agregar el código para resaltar el turno seleccionado y desactivar los demás
    var turnos = document.querySelectorAll('#listaTurnos li');
    turnos.forEach(function(turno) {
        turno.addEventListener('click', function(event) {
            event.stopPropagation();
            // Eliminar la clase "selected" de todos los turnos
            turnos.forEach(function(otroTurno) {
                otroTurno.classList.remove('selected');
            });
            // Agregar la clase "selected" al turno clicado
            turno.classList.add('selected');
        });

        // Agregar un controlador de eventos para el evento mouseover a cada turno
        turno.addEventListener('mouseover', function() {
            // Agregar la clase de hover seleccionado al turno si no está seleccionado
            if (!turno.classList.contains('selected')) {
                turno.classList.add('hover-selected');
            }
        });

        // Agregar un controlador de eventos para el evento mouseout a cada turno
        turno.addEventListener('mouseout', function() {
            // Eliminar la clase de hover seleccionado al mover el puntero fuera del turno
            turno.classList.remove('hover-selected');
        });
    });

    // Agregar un controlador de eventos al botón "Aceptar" de la ventana modal de reserva creada
    $('#modalReserva [data-dismiss="modal"]').on('click', function() {
        // Cerrar la ventana modal
        $('#modalReserva').modal('hide');
        // Redirigir a la página principal de usuarios
        window.location.href = '../views/mainPageUsers.php';
    });
});
