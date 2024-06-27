// Variable global para almacenar la sección actual
var seccionActual = "";

// Funcionamiento del boton atras
$(document).ready(function() {
    $('#btn-atras').click(function() {
        $('#btn-atras').hide();
        // Realizar la acción correspondiente según la sección actual
        switch (seccionActual) {
            case 'usuarios':
                // Ocultar la lista de usuarios
                $('#listaUsuarios').hide();
                // Mostrar el contenido de administrador nuevamente
                $('#adminSection').show();
                $('#btnUsuarios').show();
                $('#btnInstalaciones').show();
                $('#btnReservas').show();
                $('#tituloBienvenida').show();
                break;
            case 'instalaciones':
                $('#tablaInstalaciones').hide();
                $('#btnUsuarios').show();
                $('#adminSection').show();
                $('#btnInstalaciones').show();
                $('#btnReservas').show();
                $('#tituloBienvenida').show();
                break;
            case 'reservas':
                $('#tablaReservas').hide();
                $('#adminSection').show();
                $('#btnReservas').show();
                $('#btnUsuarios').show();
                $('#btnInstalaciones').show();
                $('#tituloBienvenida').show();
                break;
            default:
                break;
        }        
    });
});

$(document).ready(function() {
    // Manejador de eventos para el clic del botón #btnUsuarios
    $('#btnUsuarios').click(function() {
        cargarUsuarios('#listaUsuarios');
        $('#btn-atras').show();
        $('#btnReservas').hide()
        $('#btnInstalaciones').hide();
        $('#adminSection').hide();
        // Actualizar la variable de la sección actual
        seccionActual = "usuarios";
    });

    // Delegación de eventos para ordenar la tabla
    $('#listaUsuarios').on('click', 'th', function() {
        var indiceColumna = $(this).index();
        ordenarTabla(indiceColumna, 'listaUsuarios');
    });    
});

function cargarUsuarios(selector) {
    // Realizar una solicitud AJAX al servidor para obtener los usuarios
    $.ajax({
        url: '../views/listausuarios.php', // Archivo PHP que maneja la solicitud
        type: 'GET',
        async: true,
        success: function(response) {
            // Ocultar la sección de administración
            $('#adminSection').hide();
            // Mostrar la lista de usuarios
            $(selector).html(response); // Mostrar la lista de usuarios en el selector especificado
            // Ocultar el botón "Usuarios"
            $('#btn-atras').show();
            $('#btnUsuarios').hide();
            $('#btnReservas').hide()
            $('#btnInstalaciones').hide();
            $('#tituloBienvenida').hide();
            // Actualizar la variable de la sección actual
            seccionActual = "usuarios";
            
        },
        error: function(xhr, status, error) {
            // Manejar errores si es necesario
            console.error(error);
        }
    });
}


// Función para cargar la lista de instalaciones
$('#btnInstalaciones').click(function() {
    cargarInstalaciones('#tablaInstalaciones');
    $('#btn-atras').show();
    $('#btnUsuarios').hide();
    $('#btnReservas').hide()
    $('#btnInstalaciones').hide();
    // Actualizar la variable de la sección actual
    seccionActual = "instalaciones";
});

// Función para cargar la lista de instalaciones dinámicamente
function cargarInstalaciones(selector) {
    // Realizar una solicitud AJAX al servidor para obtener las instalaciones
    $.ajax({
        url: '../views/listaInstalaciones.php',
        type: 'GET',
        async: true,
        success: function(response) {
            // Ocultar la sección de administración
            $('#adminSection').hide();
            // Mostrar la lista de usuarios
            $(selector).html(response).show();
            // Ocultar el botón "Usuarios"
            $('#btnInstalaciones').hide();
            $('#tituloBienvenida').hide();
            // Actualizar la variable de la sección actual
            seccionActual = "instalaciones";
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Función para cargar la lista de reservas
$('#btnReservas').click(function() {
    cargarReservas('#tablaReservas');
    $('#btn-atras').show();
    $('#btnUsuarios').hide();
    $('#btnReservas').hide()
    $('#btnInstalaciones').hide();
    // Actualizar la variable de la sección actual
    seccionActual = "reservas";

    // Delegación de eventos para ordenar la tabla
    $('#tablaReservas').on('click', 'th', function() {
        var indiceColumna = $(this).index();
        ordenarTabla(indiceColumna, 'tablaReservas');
    });    
});

// Función para cargar la lista de reservas dinámicamente
function cargarReservas(selector) {
    // Realizar una solicitud AJAX al servidor para obtener las reservas
    $.ajax({
        url: '../views/listaReservas.php',
        type: 'GET',
        async: true,
        success: function(response) {
            // Ocultar la sección de administración
            $('#adminSection').hide();
            $('h2').hide();
            // Mostrar la tabla de reservas
            $(selector).html(response);
            // Ocultar el boton reservas
            $('#tituloBienvenida').hide();
            $('#btnReservas').hide();
            seccionActual = "reservas";
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}


