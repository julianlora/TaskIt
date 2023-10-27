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
        
        // mysqli_close($this->conexion);
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
            if ($etiqueta == $lista['etiqueta'] || $etiqueta == 'todas'){ // Filtrar por etiqueta
                $titulo = $lista['titulo'];
                $id = $lista['id'];
                echo "
                <article id='$id' class='lista draggable' draggable='true'>";
                // Si tiene etiqueta agregar rotulo
                if ($etiqueta == 'todas' && $lista['etiqueta'] != ''){
                    $this->insertarRotulo($lista['etiqueta']);
                }
                echo"
                    <div class='drag $id'>HHH</div>
                    <div class='listmenu'>
                        <div class='dropdown'>
                            <img class='opcionesbtn $id' src='imagenes/three-dots.png'>
                            <div id='opciones-$id' class='dropdown-content'>
                                <button class='paper-btn show'>Esconder tareas terminadas</button>
                                <button class='paper-btn show'>Esconder barra de progreso</button>
                                <button class='paper-btn show'>Definir etiqueta</button>
                                <button class='paper-btn show'>Modificar fecha de finalización</button>";
                                $this->insertarOpcionEliminar($lista);
                echo"
                            </div>
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

    public function insertarRotulo($etiqueta){
        // $etiqueta = strtoupper($etiqueta);
        echo"<div class='rotulo'><form action='sql/etiquetaABM.php' method='post'>
        <input type='hidden' name='accion' value='filtrar_etiqueta'>
        <input type='hidden' name='etiqueta' value='$etiqueta'>
        <button class='etiqueta' type='submit'>$etiqueta</button>
        </form></div>";
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
