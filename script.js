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


// MOVER LISTA
const listas = document.querySelectorAll('.lista'); // Todos los containers donde se pueden dejar
listas.forEach(lista => {
    lista.addEventListener('dragstart', () => {
        lista.classList.add('dragging',`${lista.id}`)
    })

    lista.addEventListener('dragend', () => {
        lista.classList.remove('dragging',`${lista.id}`)
    })

    lista.addEventListener('dragover', (e) => {
        e.preventDefault()
        const draggedLista = document.querySelector('.dragging')
        const rect = lista.getBoundingClientRect();
        const offsetY = e.clientY - rect.top;
        const halfHeight = rect.height / 2;
        if (offsetY < halfHeight) {
            // Cursor en la mitad superior del elemento objetivo
            lista.insertAdjacentElement('beforebegin', draggedLista)
        } else {
            // Cursor en la mitad inferior del elemento objetivo
            lista.insertAdjacentElement('afterend', draggedLista)
        }
    })
})