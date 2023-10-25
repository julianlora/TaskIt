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
        $resultado = mysqli_query($this->conexion, "
        SELECT * from listas
        WHERE id_usuario = '$id'
        ORDER BY id DESC
        ");
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
        </form>
        ";
    }

    public function insertarFormularioCrearLista(){
        echo"
        <form class='animation' action='sql/listaABM.php' method='post'>
        <label for='titulo'>Titulo</label>
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
        </select>
        <input type='hidden' name='accion' value='crear_lista'>
        <button type='submit'>Enviar</button>
        </form>
        ";
    }

    public function mostrarListasEnPantalla(){
        $item_controlador = new ItemController($this->conexion);
        foreach($this->listas as $lista){
            if (!isset($_SESSION['etiqueta']) || $_SESSION['etiqueta'] == $lista['etiqueta'] || $_SESSION['etiqueta'] == 'todas'){
                $titulo = $lista['titulo'];
                $id = $lista['id'];
                echo "
                    <article class='lista'>
                        <h1 class='titulo'>
                            $titulo
                            <meter class='progress'></meter>
                            <span class='listmenu'>
                            <div class='dropdown'>
                                <button class='opcionesbtn lista'>Opciones</button>
                                <div id='opciones-lista' class='dropdown-content'>
                                    <button class='paper-btn show'>Esconder tareas terminadas</button>
                                    <button class='paper-btn show'>Esconder barra de progreso</button>
                                    <button class='paper-btn show'>Definir etiqueta</button>
                                    <button class='paper-btn show'>Modificar fecha de finalización</button>
                                    <button class='paper-btn show'>Eliminar</button>
                                </div>
                            </div>
                            </span>
                        </h1>
                        <ul class='mainlist'>";
                $item_controlador->cargarItemsDeLista($id, 0);
                echo "</ul>
                        <div class='bottom-menu'>
                            <form action='sql/itemABM.php' method='post'>
                                <input type='hidden' name='accion' value='agregar_item'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='text' name='texto' required>
                                <button type='submit'>Agregar item</button>
                            </form>
                            <br><button class='retractlistbtn'>^</button>
                        </div>
                    </article>
                ";
            }
            
        }
        
        
    }
}
?>