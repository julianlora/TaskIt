window.addEventListener('click', function(event) {
    // Verificar si el elemento clickeado tiene clases
    if (event.target.classList) {
        // Obtener un array de las clases del elemento
        var clases = event.target.classList;
        
        // Puedes iterar a través de las clases si el elemento tiene más de una
        for (var i = 0; i < clases.length; i++) {
            var clase = clases[i];
            if (clase == 'opcionesbtn'){
                document.getElementById(`opciones-${clases[1]}`).classList.toggle("show");
            } else if (clase == 'eliminar'){
                let id_lista = clases[3]
                document.getElementById(`ventana_confirmacion-${id_lista}`).classList.add("ventana_confirmacion-activo")
            } else if (clase == 'cancelar_eliminar'){
                let id_lista = clases[1]
                document.getElementById(`ventana_confirmacion-${id_lista}`).classList.remove("ventana_confirmacion-activo")
            }
        }
    } else {
        window.location.href = "index.php"; // usar para cerrar cosas abiertas
    }
});