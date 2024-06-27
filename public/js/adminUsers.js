$(document).ready(function() {   

    // Función para agregar una fila vacía al hacer clic en el botón "Añadir"
    $('#btn-add').click(function() {               
        var newRow = '<tr class="nueva-fila">' +
                        '<td><input type="checkbox" class="seleccionar-usuario"></td>' +
                        '<td></td>' +
                        '<td contenteditable="true"></td>' +
                        '<td contenteditable="true"></td>' +
                        '<td contenteditable="true"></td>' +
                        '<td contenteditable="true"></td>' +
                        '<td contenteditable="true"></td>' +
                        '<td contenteditable="true"></td>' +
                        '<td>' +
                            '<button class="btn btn-success btn-confirmar mr-2">Confirmar</button>' +
                            '<button class="btn btn-danger btn-cancelar">Cancelar</button>' +
                        '</td>' +
                     '</tr>';
        $('table').append(newRow);
        $('#btn-modificar').hide();
        $('#btn-eliminar').hide();
    });

    // Función para mostrar mensajes de error en el modal de alerta
    function mostrarError(mensaje) {
        $('#modalAlertMessage').text(mensaje);
        $('#modalAlert').modal('show');
    }

    // Función de validación para los campos
    function validarCampos(newValues) {
        const nombreRegex = /^[A-Za-z\s]+$/;
        const portalRegex = /^[0-9]+$/;
        const pisoRegex = /^[A-Za-z0-9]+$/;
        const letraRegex = /^[A-Za-z]$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const perfilRegex = /^(usuario|administrador)$/;

        // Verificar si todos los campos están rellenos
        for (let key in newValues) {
            if (newValues[key] === "") {
                mostrarError('Todos los campos deben estar rellenos.');
                return false;
            }
        }
        if (!nombreRegex.test(newValues.fullname)) {
            mostrarError('El nombre solo puede contener letras y espacios.');
            return false;
        }
        if (!portalRegex.test(newValues.portal)) {
            mostrarError('El portal solo puede contener números.');
            return false;
        }
        if (!pisoRegex.test(newValues.piso)) {
            mostrarError('El piso solo puede contener letras o números.');
            return false;
        }
        if (!letraRegex.test(newValues.letra)) {
            mostrarError('La letra solo puede contener una letra.');
            return false;
        }
        if (!emailRegex.test(newValues.email)) {
            mostrarError('Por favor ingrese un email válido.');
            return false;
        }
        if (!perfilRegex.test(newValues.perfil)) {
            mostrarError('El perfil solo puede ser "usuario" o "administrador".');
            return false;
        }
        return true;
    }

    // Función para manejar la confirmación de la adición del nuevo usuario
    $(document).on('click', '.btn-confirmar', function() {
        var newRow = $(this).closest('.nueva-fila');
        var newValues = {
            fullname: newRow.find('td:nth-child(3)').text().trim(),
            portal: newRow.find('td:nth-child(4)').text().trim(),
            piso: newRow.find('td:nth-child(5)').text().trim(),
            letra: newRow.find('td:nth-child(6)').text().trim(),
            email: newRow.find('td:nth-child(7)').text().trim(),
            perfil: newRow.find('td:nth-child(8)').text().trim()
        };

        // Validar los campos antes de enviar
        if (!validarCampos(newValues)) {
            return; // Salir de la función si la validación falla
        }

        console.log("Nuevos valores:", newValues);

        // Enviar la solicitud AJAX para agregar el nuevo usuario
        $.ajax({
            url: '../controllers/adminAddUser.php',
            type: 'POST',
            data: { 
                newValues: newValues
            },
            dataType: 'json',
            success: function(response) {
                console.log("Respuesta del servidor:", response); // Depuración adicional
                if (response.success) {
                    // Mostrar mensaje de éxito en el modal de alerta
                    $('#modalAlertMessage').text(response.message); // Usar el mensaje del servidor
                    $('#modalAlert').modal('show');
                    // Recargar la lista de usuarios después de cerrar el modal
                    $('#modalAlert').on('hidden.bs.modal', function() {
                        cargarUsuarios('#listaUsuarios');
                    });
                } else {
                    $('#modalAlertMessage').text(response.message); // Usar el mensaje del servidor
                    $('#modalAlert').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
                console.error('Respuesta del servidor:', xhr.responseText); // Mostrar la respuesta completa
                $('#modalAlertMessage').text('Hubo un error al intentar añadir el usuario.');
                $('#modalAlert').modal('show');
            }            
        });
        // Eliminar la fila agregada
        newRow.remove();
    });

    // Función para manejar la cancelación de la adición del nuevo usuario
    $(document).on('click', '.btn-cancelar', function() {
        $(this).closest('.nueva-fila').remove();
        // Recargar la lista de usuarios para revertir los cambios
        cargarUsuarios('#listaUsuarios');
    });
});

$(document).ready(function() {
    var userId; // Variable para almacenar el ID del usuario seleccionado

    // Evento al hacer clic en una fila de la tabla
    $('tr').click(function() {
        // Remover la clase de selección de todas las filas
        $('tr').removeClass('seleccionado');
        // Agregar la clase de selección a la fila clicada
        $(this).addClass('seleccionado');
    });

    // Evento al hacer clic en el botón "Modificar"
$('#btn-modificar').click(function() {
    // Verificar si se ha seleccionado al menos un usuario
    var usuarioSeleccionado = $('tr.seleccionado');
    if (!usuarioSeleccionado.length) {
        // Mostrar el modal de alerta personalizado
        $('#modalAlertMessage').text('Debes seleccionar al menos un usuario para modificar.');
        $('#modalAlert').modal('show');
        return;
    }

    // Obtener el ID del usuario seleccionado
    userId = usuarioSeleccionado.data('userid');

    // Habilitar la edición de todas las celdas editables
    usuarioSeleccionado.find('.editable').attr('contenteditable', 'true');
    
    // Ocultar los botones de modificar y eliminar
    $('#btn-modificar, #btn-add, #btn-eliminar').hide();
    $('.guardarButton').show();        
});


    // Función para mostrar mensajes de error en el modal de alerta
    function mostrarError(mensaje) {
        $('#modalAlertMessage').text(mensaje);
        $('#modalAlert').modal('show');
    }

    // Función de validación para los campos
    function validarCampos(newValues) {
        const nombreRegex = /^[A-Za-zÀ-ÿ\s]+$/; // Acepta letras y espacios, incluyendo caracteres acentuados
        const portalRegex = /^[0-9]+$/;
        const pisoRegex = /^[A-Za-z0-9]+$/;
        const letraRegex = /^[A-Za-z]$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const perfilRegex = /^(usuario|administrador)$/;

        // Verificar si todos los campos están rellenos
        for (let key in newValues) {
            if (newValues[key] === "") {
                mostrarError('Todos los campos deben estar rellenos.');
                return false;
            }
        }

        if (!nombreRegex.test(newValues.nombre)) {
            mostrarError('El nombre solo puede contener letras y espacios.');
            return false;
        }
        if (!portalRegex.test(newValues.portal)) {
            mostrarError('El portal solo puede contener números.');
            return false;
        }
        if (!pisoRegex.test(newValues.piso)) {
            mostrarError('El piso solo puede contener letras o números.');
            return false;
        }
        if (!letraRegex.test(newValues.letra)) {
            mostrarError('La letra solo puede contener una letra.');
            return false;
        }
        if (!emailRegex.test(newValues.email)) {
            mostrarError('Por favor ingrese un email válido.');
            return false;
        }
        if (!perfilRegex.test(newValues.perfil)) {
            mostrarError('El perfil solo puede ser "usuario" o "administrador".');
            return false;
        }
        return true;
    }

    // Evento al hacer clic en el botón "Guardar"
    $('.guardarButton').click(function() {
        // Obtener los nuevos valores de todas las celdas editables
        var newValues = {};
        $('tr.seleccionado').find('.editable').each(function() {
            newValues[$(this).data('columna')] = $(this).text().trim();
        });

        // Validar los campos antes de enviar
        if (!validarCampos(newValues)) {
            return; // Salir de la función si la validación falla
        }

        // Enviar los nuevos valores al servidor para modificar el usuario
        $.ajax({
            url: '../controllers/adminControlador.php',
            type: 'POST',
            data: { 
                userId: userId,
                newValues: newValues
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Mostrar mensaje de éxito en el modal de alerta
                    $('#modalAlertMessage').text('Cambios guardados correctamente.');
                    $('#modalAlert').modal('show');
                    // Recargar la lista de usuarios después de cerrar el modal
                    $('#modalAlert').on('hidden.bs.modal', function() {
                        cargarUsuarios('#listaUsuarios');
                    });
                } else {
                    // Mostrar mensaje de error en el modal de alerta
                    $('#modalAlertMessage').text('Hubo un error al intentar modificar el usuario.');
                    $('#modalAlert').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
                // Mostrar mensaje de error en el modal de alerta
                $('#modalAlertMessage').text('Hubo un error al intentar modificar el usuario.');
                $('#modalAlert').modal('show');
            }
        });
    });

    // Manejar el cierre del modal de alerta
    $('#modalAlert').on('hidden.bs.modal', function() {
        // Enfocar la tabla de usuarios para facilitar la selección de usuarios
        $('#tablaUsuarios').focus();
    });
});

$(document).ready(function() {
    // Manejar el clic en el botón "Eliminar"
    $('#btn-eliminar').click(function() {       
        // Verificar si al menos un usuario está seleccionado
        if ($('.seleccionar-usuario:checked').length > 0) {
            // Mostrar la ventana modal de confirmación
            $('#modalEliminar').modal('show');
        } else {
            // Mostrar el modal de alerta personalizado
            $('#modalAlertMessage').text('Debes seleccionar al menos un usuario para eliminar.');
            $('#modalAlert').modal('show');
        }
    });

    // Manejar el clic en el botón "Confirmar" de la ventana modal
    $('#btnConfirmarEliminar').click(function() {
        // Recopilar los usuarios seleccionados
        var usuariosSeleccionados = [];
        $('.seleccionar-usuario:checked').each(function() {
            usuariosSeleccionados.push($(this).val());
        });

        // Enviar los usuarios seleccionados al servidor para su eliminación mediante AJAX
        $.ajax({
            url: '../controllers/adminDeleteUser.php',
            type: 'POST',
            data: {
                usuarios: usuariosSeleccionados
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Ocultar el modal de eliminación
                    $('#modalEliminar').modal('hide');
                    // Recargar la lista de usuarios
                    cargarUsuarios('#listaUsuarios');
                } else {
                    // Mostrar mensaje de error en el modal de alerta
                    $('#modalAlertMessage').text('Hubo un error al intentar modificar el usuario.');
                    $('#modalAlert').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud:', error);
                // Mostrar mensaje de error en el modal de alerta
                $('#modalAlertMessage').text('Hubo un error al intentar eliminar el usuario.');
                $('#modalAlert').modal('show');
            }
        });


        // Ocultar la ventana modal después de confirmar
        $('#modalEliminar').css('display', 'none');
    });

    // Manejar el clic en el botón "Cancelar" de la ventana modal
    $('#btnCancelarEliminar').click(function() {
        // Ocultar la ventana modal
        $('#modalEliminar').css('display', 'none');
    });
});