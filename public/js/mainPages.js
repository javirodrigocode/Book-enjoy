function obtenerTurnosDisponibles(fechaSeleccionada, selectedInstalacion) {
    // Hacer la solicitud AJAX a obtenerTurnosDisponibles.php
    fetch('../controllers/obtenerTurnosDisponibles.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'fecha=' + fechaSeleccionada + '&instalacion=' + selectedInstalacion
    })
    .then(response => response.json())
    .then(turnos => {
        // Manejar la respuesta JSON con los turnos disponibles
        console.log('Turnos disponibles:', turnos);
        // Actualizar la interfaz con los turnos disponibles
        mostrarTurnosDisponibles(turnos, selectedInstalacion); // Pasar selectedInstalacion como argumento
    })
    .catch(error => {
        // Manejar cualquier error de la solicitud
        console.error('Error al obtener los turnos:', error);
    });
}

function mostrarTurnosDisponibles(turnos) {
    console.log('Turnos recibidos:', turnos);
    var listaTurnos = document.getElementById('listaTurnos');
    listaTurnos.innerHTML = ''; // Limpiar la lista de turnos

    // Mostrar cada turno en la lista
    turnos.forEach(turno => {
        var li = document.createElement('li');
        li.textContent = `Turno de ${turno.hora_inicio} a ${turno.hora_fin}`;
        li.setAttribute('data-idTurno', turno.id_turno);

        // Modificar la clase según el estado del turno
        if (turno.estado === 'disponible'|| turno.estado === 'cancelado') {
            li.classList.add('disponible');

            // Agregar evento de clic al elemento <li> solo para los turnos disponibles
            li.addEventListener('click', function() {
                console.log('Turno seleccionado:', turno);
                // Quitar la clase 'selected' de todos los elementos
                document.querySelectorAll('#listaTurnos li').forEach(item => {
                    item.classList.remove('selected');
                });

                // Agregar la clase 'selected' al elemento seleccionado
                this.classList.add('selected');
            });
        } else {
            li.classList.add('reservado');
            // Desactivar clics en turnos reservados
            li.addEventListener('click', function(event) {
                event.stopPropagation(); // Evitar que el clic se propague
            });
        }

        // Agregar el turno a la lista
        listaTurnos.appendChild(li);
    });

    // Crear el contenedor para el cuadradito y el texto de disponibilidad
    var contenedorDisponibilidad = document.createElement('div');
    contenedorDisponibilidad.classList.add('contenedor-disponibilidad');

    // Crear el cuadradito de disponibilidad
    var cuadraditoDisponibilidad = document.createElement('div');
    cuadraditoDisponibilidad.classList.add('cuadradito', 'bg-success');

    // Crear el texto de disponibilidad
    var textoDisponibilidad = document.createElement('span');
    textoDisponibilidad.textContent = 'Disponible';
    textoDisponibilidad.classList.add('texto-disponibilidad');

    // Agregar el cuadradito y el texto al contenedor de disponibilidad
    contenedorDisponibilidad.appendChild(cuadraditoDisponibilidad);
    contenedorDisponibilidad.appendChild(textoDisponibilidad);

    // Agregar el contenedor de disponibilidad al final de la lista de turnos
    listaTurnos.appendChild(contenedorDisponibilidad);

    // Crear el contenedor para el cuadradito y el texto de reserva
    var contenedorReserva = document.createElement('div');
    contenedorReserva.classList.add('contenedor-reserva');

    // Crear el cuadradito de reserva
    var cuadraditoReserva = document.createElement('div');
    cuadraditoReserva.classList.add('cuadradito', 'bg-danger');

    // Crear el texto de reserva
    var textoReserva = document.createElement('span');
    textoReserva.textContent = 'Reservado';
    textoReserva.classList.add('texto-reserva');

    // Agregar el cuadradito y el texto al contenedor de reserva
    contenedorReserva.appendChild(cuadraditoReserva);
    contenedorReserva.appendChild(textoReserva);

    // Agregar el contenedor de reserva al final de la lista de turnos
    listaTurnos.appendChild(contenedorReserva);

    // Mostrar la lista de turnos
    listaTurnos.style.display = 'block';

    // Mostrar los botones
    var contenedorBotones = document.getElementById('contenedorBotones');
    contenedorBotones.style.display = 'block';
}


