var fechaReserva;
var selectedInstalacion;

document.addEventListener('DOMContentLoaded', function() {    
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        // Configuración del calendario
        initialView: 'dayGridMonth', // Vista inicial del calendario
        locale: 'es', // Idioma español
        firstDay: 1, // Lunes
        selectable: true, // Habilitar la selección de días
        select: function(info) {
            fechaReserva = info.startStr; // Obtener la fecha seleccionada en formato YYYY-MM-DD             
            console.log('Fecha seleccionada:', fechaReserva);
            // Obtener el valor seleccionado del select de instalaciones
            selectedInstalacion = document.getElementById('instalacion');
            if (selectedInstalacion) {
                selectedInstalacion = selectedInstalacion.value;
                console.log('Instalación seleccionada:', selectedInstalacion);
            } else {
                console.error('No se pudo obtener la instalación seleccionada.');
                return; // Salir de la función si no se puede obtener la instalación seleccionada
            }
            // Obtener los turnos disponibles para la fecha seleccionada
            obtenerTurnosDisponibles(fechaReserva, selectedInstalacion);
            resaltarFechaSeleccionada(); // Resaltar la fecha seleccionada en el calendario
        }        
    });
    
    calendar.render(); // Renderizar el calendario

    // Función para resaltar la fecha seleccionada en el calendario
    function resaltarFechaSeleccionada() {
        // Remover la clase de selección de todas las fechas
        var todasLasFechas = document.querySelectorAll('.fc-day');
        todasLasFechas.forEach(function(fecha) {
            fecha.classList.remove('fecha-seleccionada');
        });

        // Agregar la clase de selección a la fecha seleccionada
        var fechaSeleccionadaElemento = document.querySelector('.fc-day[data-date="' + fechaReserva + '"]');
        if (fechaSeleccionadaElemento) {
            fechaSeleccionadaElemento.classList.add('fecha-seleccionada');
        }
    }
});


    








        
        
        
        







  


