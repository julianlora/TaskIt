window.addEventListener('click', function(event) {
    // Verificar si el elemento clickeado tiene clases
    if (event.target.classList) {
        // Obtener un array de las clases del elemento
        var clases = event.target.classList;
        
        // Puedes iterar a través de las clases si el elemento tiene más de una
        for (var i = 0; i < clases.length; i++) {
            var clase = clases[i];
            let id_lista
            let id_item
            switch (clase){
                case 'opcionesbtn':
                    document.getElementById(`opciones-${clases[1]}`).classList.toggle("show");
                    break
                case 'eliminar':
                    id_lista = clases[3]
                    document.getElementById(`ventana_confirmacion-${id_lista}`).classList.add("ventana_confirmacion-activo")
                    break
                case 'cancelar_eliminar':
                    id_lista = clases[1]
                    document.getElementById(`ventana_confirmacion-${id_lista}`).classList.remove("ventana_confirmacion-activo")
                    break
                case 'compartir':
                    id_lista = clases[3]
                    document.getElementById(`ventana_compartir-${id_lista}`).classList.add("ventana_compartir-activo")
                    break
                case'agregar_miembro':
                    id_lista = clases[1]
                    document.getElementById(`ventana_compartir-${id_lista}`).classList.add("ventana_compartir-activo")
                    break
                case'terminar_compartir':
                    id_lista = clases[1]
                    document.getElementById(`ventana_compartir-${id_lista}`).classList.remove("ventana_compartir-activo")
                    window.location.href = "to_terminar_compartir.php";
                    break
                case'share-img':
                    document.getElementById(`miembros-${clases[1]}`).classList.toggle("show");
                    break
                case 'bell':
                case 'yellow-circle':
                case 'notificaciones-btn':
                    document.getElementById(`notificaciones`).classList.toggle("show");
                    if (document.querySelector('.yellow-circle').classList.contains("show")){
                        document.querySelector('.yellow-circle').classList.remove("show")
                        window.location.href = "to_notificacion_abierta.php";
                    }
                    break
                case 'nuevo-subitem-btn':
                    id_item = clases[1]
                    document.querySelector(`.crear-subitem.${id_item}`).classList.toggle("show");
                    break
                case 'profile-img':
                    document.querySelector(`.panel`).classList.toggle("show");
                    break
            }
        }
    }
});


const items = document.querySelectorAll('.cabecera-item')
items.forEach(item => {
    item.addEventListener('mouseover', () =>{
        let id_item = item.classList[1]
        document.querySelector(`.item-menu.m${id_item}`).style.visibility = 'visible'
    })
    item.addEventListener('mouseleave', () =>{
        let id_item = item.classList[1]
        document.querySelector(`.item-menu.m${id_item}`).style.visibility = 'hidden'
    })
})


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