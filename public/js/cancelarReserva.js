// Esperar a que el documento esté completamente cargado
document.addEventListener("DOMContentLoaded", function() {
    // Capturar el clic en el botón Cancelar Reserva
    document.querySelectorAll('.cancel-button').forEach(function(button) {
        button.addEventListener('click', function() {
            // Obtener el ID de la reserva desde el atributo data-reserva-id
            var idReserva = button.getAttribute('data-reserva-id');

            
            // Enviar una solicitud AJAX para cancelar la reserva
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../controllers/cancelarReserva.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Manejar la respuesta del servidor
                    var response = JSON.parse(xhr.responseText);
                    // Imprimir la respuesta del servidor en la consola
                    console.log("Respuesta del servidor:", response);
                    if (response.success) {
                        // Después de que se haya cancelado la reserva exitosamente
                        $('#modalCancelarReserva').modal('show');
                        button.style.display = 'none'; // Ocultar el botón cancelar                               
                       
                    } else {
                        alert(response.error);
                    }
                } else {
                    alert('Error en la solicitud AJAX: ' + xhr.statusText);
                }
            };
            xhr.onerror = function() {
                alert('Error en la solicitud AJAX');
            };
            xhr.send('id_reserva=' + encodeURIComponent(idReserva));
        });
        // Agregar un controlador de eventos para el botón "Aceptar" dentro de la modal
        $('#modalCancelarReserva').on('hidden.bs.modal', function (e) {
            // Recargar la página después de cerrar la modal
            location.reload();
});

    });
});
