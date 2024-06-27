
function validarFormulario() {
    var fullname = document.getElementById('fullname').value.trim();
    var email = document.getElementById('email').value.trim();

    // Validar que los campos no estén vacíos
    if (fullname === '' || email === '') {
        mostrarMensaje('Por favor, complete todos los campos.');
        return false;
    }

    // Validar el formato del correo electrónico
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        mostrarMensaje('Por favor, introduzca una dirección de correo electrónico válida.');
        return false;
    }

    return true;
}

// Función para obtener el ID del usuario de la cookie
function obtenerIdUsuarioDeCookie() {
    var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)userId\s*\=\s*([^;]*).*$)|^.*$/, "$1");
    return cookieValue;
}

// Función para volver a la página anterior en el historial del navegador
function goBack() {
    window.history.back();
}



// Funcion para paginar las tablas
function manejarPaginacion(tabla, elementosPorPagina, datosCompletos) {
    var numElementos = datosCompletos.length;
    var numPaginas = Math.ceil(numElementos / elementosPorPagina);

    // Limpiar la paginación existente
    tabla.parent().find('.paginacion').empty();

    // Generar botones de paginación utilizando Bootstrap
    for (var i = 1; i <= numPaginas; i++) {
        tabla.parent().find('.paginacion').append('<li class="page-item"><button class="btn btn-link pagina">' + i + '</button></li>');
    }

    // Mostrar la primera página al cargar la página
    mostrarPagina(tabla, datosCompletos, 1, elementosPorPagina);

    // Manejar el clic en los botones de paginación
    tabla.parent().find('.paginacion').on('click', '.pagina', function() {
        var pagina = parseInt($(this).text());
        mostrarPagina(tabla, datosCompletos, pagina, elementosPorPagina);
    });
}

function mostrarPagina(tabla, datosCompletos, pagina, elementosPorPagina) {
    var inicio = (pagina - 1) * elementosPorPagina;
    var fin = Math.min(inicio + elementosPorPagina, datosCompletos.length);

    // Ocultar todas las filas de la tabla
    tabla.find('tbody tr').hide();

    // Mostrar las filas correspondientes a la página actual
    for (var i = inicio; i < fin; i++) {
        tabla.find('tbody tr').eq(i).show();
    }
}

// Funcion para ordenar tablas
// Objeto para realizar un seguimiento del estado de orden de cada columna
var estadoOrden = {};

function ordenarTabla(indiceColumna, tablaId) {
    var tabla = document.getElementById(tablaId);
    var filas = tabla.querySelectorAll('tbody tr');
    var filasArray = Array.from(filas);

    // Obtener el estado de orden actual para esta columna
    var estadoActual = estadoOrden[indiceColumna] || 'asc';

    // Ordenar las filas en función del contenido de la columna seleccionada
    filasArray.sort(function(filaA, filaB) {
        var valorA = filaA.querySelectorAll('td')[indiceColumna]?.innerText || '';
        var valorB = filaB.querySelectorAll('td')[indiceColumna]?.innerText || '';

        // Si los valores son numéricos, comparar como números; de lo contrario, comparar como cadenas
        if (!isNaN(valorA) && !isNaN(valorB)) {
            return estadoActual === 'asc' ? parseFloat(valorA) - parseFloat(valorB) : parseFloat(valorB) - parseFloat(valorA);
        } else {
            return estadoActual === 'asc' ? valorA.localeCompare(valorB) : valorB.localeCompare(valorA);
        }
    });

    // Si el estado actual es ascendente, cambiar a descendente; de lo contrario, cambiar a ascendente
    estadoOrden[indiceColumna] = estadoActual === 'asc' ? 'desc' : 'asc';

    // Eliminar las filas existentes de datos de la tabla
    filas.forEach(function(fila) {
        tabla.querySelector('tbody').removeChild(fila);
    });

    // Agregar las filas ordenadas nuevamente a la tabla
    filasArray.forEach(function(fila) {
        tabla.querySelector('tbody').appendChild(fila);
    });
}