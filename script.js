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
            } else if (clase == 'compartir'){
                let id_lista = clases[3]
                document.getElementById(`ventana_compartir-${id_lista}`).classList.add("ventana_compartir-activo")
            } else if (clase == 'agregar_miembro'){
                let id_lista = clases[1]
                document.getElementById(`ventana_compartir-${id_lista}`).classList.add("ventana_compartir-activo")
            } else if (clase == 'terminar_compartir'){
                let id_lista = clases[1]
                document.getElementById(`ventana_compartir-${id_lista}`).classList.remove("ventana_compartir-activo")
                window.location.href = "to_terminar_compartir.php";
            } else if (clase == 'share-img'){
                document.getElementById(`miembros-${clases[1]}`).classList.toggle("show");
            } else if (clase == 'bell' || clase == 'yellow-circle' || clase == 'notificaciones-btn'){
                document.getElementById(`notificaciones`).classList.toggle("show");
                if (document.querySelector('.yellow-circle').classList.contains("show")){
                    document.querySelector('.yellow-circle').classList.remove("show")
                    window.location.href = "to_notificacion_abierta.php";
                }
                
            }
        }
    } else {
        window.location.href = "index.php"; // usar para cerrar cosas abiertas
    }
});


// MOVER LISTA

const dragbtns = document.querySelectorAll('.drag'); // Todos los draggable
const listas = document.querySelectorAll('.lista'); // Todos los containers donde se pueden dejar

dragbtns.forEach(dragbtn => {
    dragbtn.addEventListener('mouseover', () => {
        document.getElementById(`${dragbtn.classList[1]}`).draggable = true
    })

    dragbtn.addEventListener('mouseleave', () => {
        document.getElementById(`${dragbtn.classList[1]}`).draggable = false
    })
})

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