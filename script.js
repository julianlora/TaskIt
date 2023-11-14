var lastElementOpened = '';
window.addEventListener('click', function(event) {
    console.log(event.target)
    // Cerrar ventanas abiertas cuando el elemento seleccionado no es el mismo que se abrió por última vez, ni el primero en abrirse, ni parte del proceso que se abrió
    if (lastElementOpened != event.target && lastElementOpened != '' && !event.target.classList.contains('show') && !event.target.classList.contains('static')){
        document.querySelectorAll('.show').forEach(openedElement => {
            openedElement.classList.toggle('show')
        })
        document.querySelectorAll('.hide').forEach(hiddenElement => {
            hiddenElement.classList.toggle('hide')
        })
    }
    lastElementOpened = event.target

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
                case 'nueva-lista':
                    document.querySelector('.crear-lista').classList.toggle('show');
                    break
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
                    document.getElementById(`opciones-${id_lista}`).classList.toggle("show");
                    break
                case'editar_miembros':
                    id_lista = clases[1]
                    document.getElementById(`ventana_compartir-${id_lista}`).classList.add("ventana_compartir-activo")
                    break
                case'terminar_compartir':
                    id_lista = clases[1]
                    document.getElementById(`ventana_compartir-${id_lista}`).classList.remove("ventana_compartir-activo")
                    var xhr = new XMLHttpRequest();
                    var url = "to_terminar_compartir.php";
                    xhr.open("POST", url, true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send(); 
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
                case 'item-texto':
                    id_item = clases[1]
                    document.querySelector(`.item-texto.${id_item}`).classList.toggle("hide");
                    document.querySelector(`.edicion-texto.${id_item}`).classList.toggle("show");
                    break
                // case 'cancelar-editar':
                //     id_item = clases[1]
                //     document.querySelector(`.item-texto.${id_item}`).classList.toggle("hide");
                //     document.querySelector(`.edicion-texto.${id_item}`).classList.toggle("show");
                //     break
                case 'modificar-etiqueta-btn':
                    id_item = clases[1]
                    document.querySelector(`.ventana-etiqueta.l${id_item}`).classList.toggle("show");
                    document.getElementById(`opciones-${id_item}`).classList.remove("show");
                    break
                case 'cancelar-modificar-etiqueta':
                    id_item = clases[1]
                    document.querySelector(`.ventana-etiqueta.l${id_item}`).classList.toggle("show");
                    break
                case 'editar-miembro':
                    id_item = clases[1]
                    document.querySelector(`.opciones-miembro.o${id_item}`).classList.toggle("show");
                    break
            }
        }
    }



});

// // Abrir formulario crear lista
// document.querySelector('.nueva-lista').addEventListener('click', () => {
//     document.querySelector('.crear-lista').classList.toggle('show');
// })

// Retraer lista
document.querySelectorAll('.minimizar-btn').forEach(retractBtn => {
    retractBtn.addEventListener('click', () => {
        const clases = retractBtn.classList
        const id_lista = clases[1]
        const mainList = document.querySelector(`.mainlist.m${id_lista}`)
        let minimizada = true
        if (mainList.classList[2] == 'minimizada'){
            mainList.classList.add('maximizando')
            mainList.classList.remove('minimizada')
            document.querySelector(`.agregar-tarea.a${id_lista}`).classList.remove('minimizada')
            document.querySelector(`.min-btn.m${id_lista}`).src = 'imagenes/minimizar.png'
            minimizada = 0

            mainList.addEventListener('animationend', function() {
                mainList.classList.remove('maximizando')
            }, { once: true }); 
        } else {
            mainList.classList.add('minimizando')
            minimizada = 1

            mainList.addEventListener('animationend', function() {
                mainList.classList.add('minimizada')
                mainList.classList.remove('minimizando')
                document.querySelector(`.agregar-tarea.a${id_lista}`).classList.add('minimizada')
                document.querySelector(`.min-btn.m${id_lista}`).src = 'imagenes/maximizar.png'
            }, { once: true }); 
        }
        // Modificar base de datos
        var xhr = new XMLHttpRequest();
        var url = "sql/listaABM.php"; // Ruta a tu script PHP

        // Configurar la solicitud POST (puedes usar GET o POST según tus necesidades)
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Enviar la solicitud AJAX
        xhr.send(`accion=minimizar&minimizada=${minimizada}&id_lista=${id_lista}`); 
    })
})

// VISIBILIDAD DE MENU DE ITEMS
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


function seleccionarPlanMensual(){
    console.log('test')
}