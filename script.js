const ventanaprincipal = document.querySelector(".ventanaprincipal")

function crearLista(){
    const crearListaBtn = document.querySelector(".nuevalista")
    crearListaBtn.hidden = true
    let form = document.createElement('div')
    // edit.classList.add('edit', `e${item.id}`)
    let content = `
    <form action="sql/listaABM.php" method="post">
    <label for="nombre">Titulo</label>
    <input type="text" name="titulo" required>
    <input type='hidden' name='accion' value='crear_lista'>
    <button type="submit">Enviar</button>
    </form>
    `
    form.innerHTML = content
    crearListaBtn.insertAdjacentElement("afterend", form)
}

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
            }
        }
    }
});