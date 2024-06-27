$(document).ready(function() {
    // Manejador de eventos para el clic en el botón "Crear Reserva"
    $('#btnCrearReserva').click(function() {
        // Mostrar la fila con el select de horarios
        $('.horarioSelectRow').show();
        // Llamar a la función para agregar una nueva fila de reserva
        agregarNuevaReservaFila();
    });

    $(document).on('change', '.selector-instalaciones', function() {
        var instalacionId = $(this).val();
        var $selectorTurnos = $(this).closest('tr').find('.selector-turnos');

        // Realizar una solicitud AJAX para obtener los turnos asociados a la instalación seleccionada
        $.ajax({
            url: '../controllers/turnosReservas.php',
            type: 'POST',
            data: { instalacionId: instalacionId },
            success: function(response) {
                var listaTurnos = JSON.parse(response);
                var options = '<option value=""></option>'; 
                listaTurnos.forEach(function(turno) {
                    options += '<option value="' + turno.id_turno + '">' + turno.hora_inicio + ' - ' + turno.hora_fin + '</option>';
                });
                $selectorTurnos.html(options);
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los turnos:', error);
            }
        });
    });

    // Manejador de eventos para la confirmación de la adición de una nueva reserva
    $(document).on('click', '.btnConfirmar', function() {
        var newRow = $(this).closest('.nueva-reserva');
        var fechaReserva = newRow.find('.fecha-reserva').val();
        var idUsuario = newRow.find('.selector-usuarios').val();
        var fullname = newRow.find('.selector-usuarios option:selected').text();
        var idInstalacion = newRow.find('.selector-instalaciones').val();
        var nombreInstalacion = newRow.find('.selector-instalaciones option:selected').text();
        var turnoSeleccionado = newRow.find('.selector-turnos option:selected').text();
        var hora_inicio, hora_fin, idTurno;

        // Validar campos requeridos
        if (!fechaReserva || !idUsuario || !idInstalacion || !turnoSeleccionado) {
            $('#modalAlertMessage').text('Por favor, complete todos los campos antes de confirmar.');
            $('#modalAlerta').modal('show');
            return;
        }

        // Separar horas de inicio y fin solo si el turno está seleccionado
        if (turnoSeleccionado) {
            var horas = turnoSeleccionado.split(' - ');
            hora_inicio = horas[0] ? horas[0].trim() : '';
            hora_fin = horas[1] ? horas[1].trim() : '';
            idTurno = newRow.find('.selector-turnos').val();
        }

        var newValues = {
            fechaReserva: fechaReserva,
            idUsuario: idUsuario,
            fullname: fullname,
            idInstalacion: idInstalacion,
            nombreInstalacion: nombreInstalacion,
            hora_inicio: hora_inicio,
            hora_fin: hora_fin,
            idTurno: idTurno,
            estado: 'reservado'
        };

        console.log("Nuevos valores:", newValues);

        // Enviar la solicitud AJAX para agregar la nueva reserva
        $.ajax({
            url: '../controllers/adminAddReserve.php',
            type: 'POST',
            data: { 
                newValues: newValues
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log(response);
                    $('#modalAlertMessage').text('Reserva realizada correctamente.');
                } else {
                    $('#modalAlertMessage').text('Error al realizar la reserva.');
                }
                $('#modalAlerta').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#modalAlertMessage').text('Hubo un error en la solicitud.');
                $('#modalAlerta').modal('show');
            }            
        });

        // Eliminar la fila agregada
        newRow.remove();
    });

    // Manejador de eventos para el clic en el botón "Aceptar" del modal de alerta
    $('#btnConfirm').on('click', function() {
        $('#modalAlerta').modal('hide');
        // Recargar la tabla de reservas
        cargarReservas('#tablaReservas');
    });

    // Manejador de eventos para el clic en el botón "Cancelar Reserva"
    $(document).on('click', '.btnCancelar', function() {
        var reservaParaCancelar = $(this).data('id_reserva');
        $('#confirmarModal').modal('show');

        $('#btnConfirmarSi').one('click', function() {
            $('#confirmarModal').modal('hide');

            $.ajax({
                url: '../controllers/cancelarReserva.php',
                type: 'POST',
                data: {
                    id_reserva: reservaParaCancelar
                },
                success: function(response) {
                    if (response.success) {
                        cargarReservas('#tablaReservas');
                    } else {
                        console.error('Error al cancelar la reserva:', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cancelar la reserva:', error);
                    console.error('Detalles del error:', xhr.responseText);
                }
            });
        });
    });

    $('#btnConfirmarNo').on('click', function() {
        $('#confirmarModal').modal('hide');
        cargarReservas('#tablaReservas');
    });

    function cargarReservas(tablaId) {
        $.ajax({
            url: '../views/listaReservas.php',
            type: 'GET',
            success: function(response) {
                $(tablaId).html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar las reservas:', error);
            }
        });
    }
});

function agregarNuevaReservaFila() {
    var newRow = '<tr class="nueva-reserva">' +
                    '<td></td>' +
                    '<td><input type="text" class="fecha-reserva" readonly></td>' +
                    '<td>' +
                        '<select class="selector-usuarios">' +
                            '<option value="">Seleccione un usuario</option>';

    $.ajax({
        url: '../controllers/usuariosReservas.php',
        type: 'POST',
        success: function(response) {
            var listaUsuarios = JSON.parse(response);
            listaUsuarios.forEach(function(usuario) {
                newRow += '<option value="' + usuario.id + '">' + usuario.fullname + '</option>';
            });
            newRow += '</select>' +
                    '</td>' +
                    '<td>' +
                        '<select class="selector-instalaciones">' +
                            '<option value="">Seleccione una instalación</option>';

            $.ajax({
                url: '../controllers/instalacionesReservas.php',
                type: 'POST',
                success: function(response) {
                    var listaInstalaciones = JSON.parse(response);
                    listaInstalaciones.forEach(function(instalacion) {
                        newRow += '<option value="' + instalacion.id_instalacion + '" data-id-instalacion="' + instalacion.id_instalacion + '">' + instalacion.nombre + '</option>';
                    });
                    newRow += '</select>' +
                            '</td>' +
                            '<td>' +
                                '<select class="selector-turnos"></select>' + 
                                '<option value="">Seleccione un turno</option>'+
                            '</td>' +
                            '<td></td>' +
                            '<td>' +
                            '<button class="btn btn-success btnConfirmar mr-2">Confirmar</button>' +
                            '<button class="btn btn-secondary btnCancelacion">Cancelar</button>' +
                            '</td>' +
                        '</tr>';

                    $('#tablaReservas tbody').prepend(newRow);

                    $('.fecha-reserva').datepicker({
                        dateFormat: 'yy-mm-dd'
                    });

                    $(document).on('click', '.btnCancelacion', function () {
                        $(this).closest('tr').remove();
                        cargarReservas('#tablaReservas');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener la lista de instalaciones:', error);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener la lista de usuarios:', error);
        }
    });
}


