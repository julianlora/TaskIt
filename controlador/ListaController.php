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
    }

    public function mostrarListasEnPantalla(){
        $item_controlador = new ItemController($this->conexion);
        foreach($this->listas as $lista){
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
                                <button class='paper-btn show'>Modificar fecha de finalización</button>
                                <button class='paper-btn show'>Eliminar</button>
                            </div>
                        </div>
                        </span>
                    </h1>
                    <ul class='mainlist'>";
            $item_controlador->cargarItemsDeLista($id);
            echo "</ul>
                    <div class='bottom-menu'>
                        <form action='sql/itemABM.php' method='post'>
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
?>