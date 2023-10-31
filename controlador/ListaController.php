<?php
include("ItemController.php");

class ListaController {
    private $listas = [];
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->cargarListasDelUsuario();
    }

    public function cargarListasDelUsuario(){
        $id = $_SESSION['id'];
        if (!isset($_POST['accion']) || $_POST['accion'] != 'buscar'){ // No filtradas por buscador
            $resultado = mysqli_query($this->conexion, "
            SELECT * from listas
            WHERE id_usuario = '$id'
            ORDER BY id DESC");
        } else { // Filtradas por buscador
            $busqueda = $_POST['busqueda'];
            $resultado = mysqli_query($this->conexion, "
            SELECT * from listas
            WHERE id_usuario = '$id' and (titulo LIKE '%$busqueda%' or (
                SELECT COUNT(items.id) FROM items
                WHERE items.id_lista = listas.id and items.texto LIKE '%$busqueda%'
            ) > 0) 
            ORDER BY id DESC");
        }
        while($fila = mysqli_fetch_array($resultado)){
            $this->listas[] = $fila;
        }

        // Cargar listas de las que es miembro excepto las creadas por el usuario
        $resultado = mysqli_query($this->conexion, "SELECT * FROM listas WHERE id IN (SELECT listas_compartidas.id_lista from listas_compartidas WHERE listas_compartidas.id_usuario = '$id') and id_usuario != $id");
        while($fila = mysqli_fetch_array($resultado)){
            $this->listas[] = $fila;
        }
    }

    public function insertarBotonNuevaLista(){
        echo"
        <form method='post'>
        <input type='hidden' name='accion' value='nueva_lista'>
        <button type='submit'>Nueva lista</button>
        </form>";
    }

    public function insertarFormularioCrearLista(){
        $date = date('Y-m-d');
        echo"
        <form class='animation' action='sql/listaABM.php' method='post'>
            <label for='titulo'>Título</label>
            <input type='text' name='titulo' required>
            <label for='etiqueta'>Etiqueta</label>
            <select name='etiqueta'>
                <option value='ninguna' selected>Ninguna</option>
            ";
            $controladoretiqueta = new EtiquetaController($this->conexion);
            $etiquetas = $controladoretiqueta->obtenerEtiquetas();
            foreach($etiquetas as $etiqueta){
                $texto = $etiqueta['texto'];
                echo"
                <option value='$texto'>$texto</option>
                ";
            }
            echo"
            </select><br>
            <label for='fecha'>Fecha de finalización:</label>
            <input type='date' id='fecha' name='fecha' min='$date'>

            <input type='hidden' name='accion' value='crear_lista'>
            <button type='submit'>Enviar</button>
        </form>";
    }

    public function mostrarListasEnPantalla($etiqueta){
        $item_controlador = new ItemController($this->conexion);
        if (isset($_POST['accion']) && $_POST['accion'] == 'buscar'){ // Si se realizó una búsqueda
            $busqueda = $_POST['busqueda'];
            echo "Resultados de búsqueda por '$busqueda':";
        }
        echo "<main>";
        if($etiqueta != 'todas'){
            echo "<h1>$etiqueta</h1>";
        }
        echo "<div class='ventanaprincipal listas'>";
        foreach($this->listas as $lista){
            $id = $lista['id'];

            // Buscar rol del usuario en la lista
            if($lista['acceso'] == 'compartido'){
                $id_usuario = $_SESSION['id'];
                $resultado = mysqli_fetch_array(mysqli_query($this->conexion, "SELECT * from listas_compartidas WHERE id_usuario = '$id_usuario' and id_lista = '$id'"));
                $rol = $resultado['rol'];
            } else {
                $rol = 'administrador';
            }
            // Filtrar por etiqueta y por acceso
            if (($etiqueta == $lista['etiqueta'] || $etiqueta == 'todas') && (($_SESSION['ventana'] == 'listas compartidas' && $lista['acceso'] == 'compartido') || $_SESSION['ventana'] != 'listas compartidas')){
                $titulo = $lista['titulo'];
                echo "
                <article id='$id' class='lista draggable'>
                    <div class='top-menu'>
                        <div class='drag $id'><img src='../TaskIt/imagenes/drag.png' draggable='false'></div>
                            <div class='top-right-menu'>";
                // Si la lista es compartida insertar icono
                if ($lista['acceso'] == 'compartido'){
                    $this->insertarIconoMiembros($id);
                }
                // Si tiene etiqueta agregar rotulo
                if ($etiqueta == 'todas' && $lista['etiqueta'] != ''){
                    $this->insertarRotulo($lista['etiqueta']);
                }
                echo"
                            <div class='listmenu'>
                                <div class='dropdown'>
                                    <img class='opcionesbtn $id' src='imagenes/three-dots.png'>
                                    <div id='opciones-$id' class='dropdown-content'>
                                        <button class='paper-btn show'>Esconder tareas terminadas</button>
                                        <button class='paper-btn show'>Esconder barra de progreso</button>
                                        <button class='paper-btn show'>Definir etiqueta</button>";
                                        // Funcionalidades exclusivas de administrador
                                        if($rol == 'administrador'){
                                            echo"<button class='paper-btn show'>Modificar fecha de finalización</button>";
                                            echo"
                                            <img class='starimg' src='../TaskIt/imagenes/star.png'>
                                            <button id='mostrarVentana-$id' class='paper-btn opcionbtn compartir $id show'>Compartir</button>";
                                            $this->insertarOpcionEliminar($lista);
                                        }
                echo"
                                    </div>
                                </div>
                            </div>";
                if ($rol == 'administrador'){$this->insertarVentanaCompartir($lista);}
                echo"
                        </div>
                    </div>
                    <div class='cabecera-lista'>
                        <h1 class='titulo'>
                            $titulo";
                            $this->insertarBarraDeProgreso($id);
                echo"
                        </h1>
                    </div>
                    <ul class='mainlist'>";
                    $item_controlador->cargarItemsDeLista($id, 0);
                echo "
                    </ul>
                    <div class='bottom-menu'>
                        <form action='sql/itemABM.php' method='post'>
                            <input type='hidden' name='accion' value='agregar_item'>
                            <input type='hidden' name='id_lista' value='$id'>
                            <input class='input-lista' type='text' name='texto' required>
                            <button type='submit'>Agregar tarea</button>
                        </form><br>
                        <div class='retract-btn'>
                            <button class='retractlistbtn'>^</button>
                        </div>
                    </div>
                </article>";
            }
        }
        echo "
            </div>
        </main>";
    }

    public function insertarIconoMiembros($id){
        $id_usuario = $_SESSION['id'];
        // Buscar administradores
        $admins = mysqli_query($this->conexion, "SELECT usuario FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'administrador') ORDER BY usuario;");
        // Buscar colaboradores
        $miembros = mysqli_query($this->conexion, "SELECT usuario FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'colaborador') ORDER BY usuario;");
        // Ventana de miembros
        echo"
        <img class='share-img $id' src='../TaskIt/imagenes/share2.png'>
        <ul id='miembros-$id' class='miembros'>
            <li class='owners'><b>Administradores:</b></li>";
            $flag = false;
            while($admin = mysqli_fetch_array($admins)){
                $admin = $admin[0];
                echo "<li class='miembro'>$admin</li>";
                if($admin == $_SESSION['usuario']){
                    $flag = true;
                }
            }
            echo"<li class='colaboradores'><b>Colaboradores:</b></li>";
            while($miembro = mysqli_fetch_array($miembros)){
                $miembro = $miembro[0];
                echo "<li class='miembro'>$miembro</li>";
            }
        // Agregar miembros solo dueños
        if ($flag){
            echo"
            <button class='agregar_miembro $id'>Agregar miembros</button>
            </ul>";
        } else {
            echo"</ul>";
        }
    }

    public function insertarRotulo($etiqueta){
        // $etiqueta = strtoupper($etiqueta);
        $id_usuario = $_SESSION['id'];

        // Buscar color de la etiqueta;
        $resultado = mysqli_query($this->conexion, " SELECT * from etiquetas WHERE id_usuario = '$id_usuario' and texto = '$etiqueta'");
        if(mysqli_num_rows($resultado) != 0){
            $resultado = mysqli_fetch_array($resultado);
            $color = strtoupper($resultado['color']);

            echo"<div class='rotulo' style='background-color:$color;'><form action='sql/etiquetaABM.php' method='post'>
            <input type='hidden' name='accion' value='filtrar_etiqueta'>
            <input type='hidden' name='etiqueta' value='$etiqueta'>
            <button class='etiqueta' type='submit'>$etiqueta</button>
            </form></div>";
        }
        
    }

    public function insertarVentanaCompartir($lista){
        $id = $lista['id'];
        $titulo = $lista['titulo'];

        // Activar ventana para dar feedback
        if(isset($_SESSION['accion']) && $_SESSION['accion'] == 'compartir' && $_SESSION['id_lista'] == $id){
            $clase = 'ventana_compartir ventana_compartir-activo show';
        } else {
            $clase = 'ventana_compartir';
        }
        echo"
        <div id='ventana_compartir-$id' class='$clase'>
            <div class='confirmacion-contenido'>
                <h2>Compartir '$titulo'</h2>";
        // Feedback resultado de operacion
        if(isset($_SESSION['resultado'])){
            switch($_SESSION['resultado']){
                case 'compartida':
                    echo"¡Lista compartida con éxito!";
                    break;
                case 'usuario no encontrado':
                    echo"No se ha encontrado un usuario con ese nombre";
                    break;
                case 'relacion existente':
                    echo"El usuario ya posee acceso a esta lista";
                    break;
            }
        }
        echo"
                <div class='confirmacion-botones'>
                    <form action='sql/listaABM.php' method='post'>
                        <input type='text' name='usuario' placeholder='Agregar persona' required>
                        <input type='hidden' name='accion' value='compartir_lista'>
                        <input type='hidden' name='id_lista' value='$id'>
                        <button class='confirmar_compartir' type='submit'>Compartir</button>
                    </form>
                    <button class='terminar_compartir $id'>Listo</button>
                </div>
            </div>
        </div>
        ";
    }

    public function insertarOpcionEliminar($lista){
        $id = $lista['id'];
        $titulo = $lista['titulo'];

        // Botón para eliminar
        echo"
        <button id='mostrarVentana-$id' class='paper-btn opcionbtn eliminar $id show'>Eliminar</button>";

        // Ventana de confirmacion escondida
        echo"
        <div id='ventana_confirmacion-$id' class='ventana_confirmacion'>
            <div class='confirmacion-contenido'>
                <h2>¿Desea eliminar la lista $titulo?</h2>
                <div class='confirmacion-botones'>
                    <form action='sql/listaABM.php' method='post'>
                        <input type='hidden' name='accion' value='eliminar_lista'>
                        <input type='hidden' name='id_lista' value='$id'>
                        <button class='confirmar_eliminar' type='submit'>Eliminar</button>
                    </form>
                    <button class='cancelar_eliminar $id'>Cancelar</button>
                </div>
            </div>
        </div>
        ";
    }

    public function insertarBarraDeProgreso($id){
        // Consultar cantidad de items nivel 0 en la lista
        $resultado = mysqli_query($this->conexion, " SELECT * from items WHERE id_lista = '$id' and nivel = 0");
        $cantMax = mysqli_num_rows($resultado);
        // Consultar cantidad de items checkeados nivel 0 en la lista
        $resultado = mysqli_query($this->conexion, " SELECT * from items WHERE id_lista = '$id' and nivel = 0 and checked = true");
        $cantChecked = mysqli_num_rows($resultado);
        echo "<meter value='$cantChecked' min='0' max='$cantMax' class='progress'></meter>";
    }
}
