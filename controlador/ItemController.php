<?php

class ItemController {
    private $items = [];
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function cargarItemsDeLista($id_lista, $nivel, $rol, $esconder){

        if($esconder){
            $condicion = 'and checked != true';
        } else {
            $condicion = '';
        }

        $resultado = mysqli_query($this->conexion, 
        "SELECT * from items
        WHERE id_lista = '$id_lista' and nivel = 0 $condicion
        ");
        while($item = mysqli_fetch_array($resultado)){
            $this->items[] = $item;
            $this->mostrarItemEnPantalla($item, $rol, $esconder);
        }
        // mysqli_close($this->conexion);
        
    }

    public function cargarSubItemsDeLista($id_item_padre, $nivel, $id_lista, $rol, $esconder){

        if($esconder){
            $condicion = 'and checked != true';
        } else {
            $condicion = '';
        }

        $resultado = mysqli_query($this->conexion, 
        "SELECT * from items
        WHERE id_lista = '$id_lista' and nivel = $nivel and id_item_padre = '$id_item_padre' $condicion
        ");
        while($item = mysqli_fetch_array($resultado)){
            $this->items[] = $item;
            $this->mostrarItemEnPantalla($item, $rol, $esconder);
        }
        // mysqli_close($this->conexion);
        
    }

    public function mostrarItemEnPantalla($item, $rol, $esconder){

        $texto = $item['texto'];
        $id_item = $item['id'];
        $id_lista = $item['id_lista'];
        $nivel = $item['nivel'];
        $tipo = $item['tipo'];
        $checked = $item['checked'];
        $id_item_padre = $item['id_item_padre'];

        echo "
            <div id='$id_item' class='item'>
                <li>
                    <div class='cabecera-item $id_item'>";
        
        // CHECKBOX
        if (!$checked){
            // Checkeada
            $accion = 'checkear';
            $clase = 'unchecked';
            $img_path = '../TaskIt/imagenes/unchecked.png';
        } else {
            // No checkeada
            $accion = 'descheckear';
            $clase = 'checked';
            $img_path = '../TaskIt/imagenes/checked2.png';
        }
        // Checkbox
        if ($rol != 'lector'){
            echo "
                        <form action='sql/itemABM.php' method='post'>
                            <input type='hidden' name='accion' value='$accion'>
                            <input type='hidden' name='id_item' value='$id_item'>
                            <input type='hidden' name='nivel' value='$nivel'>
                            <input type='hidden' name='id_lista' value='$id_lista'>
                            <button class='checkbox no-lector'><img class='$clase' src='$img_path'></button>
                        </form>";
        } else {
            echo"       <span class='checkbox'><img class='$clase' src='$img_path'></span>";
        }
        // Texto del item
        echo"           <p class='item-texto e$id_item'>$texto</p>";
        // Edición escondida
        echo"           <div class='edicion-texto e$id_item'>
                        <form class='editar-texto e$id_item' action='sql/itemABM.php' method='post'>
                            <input type='hidden' name='accion' value='editar_texto'>
                            <input type='hidden' name='id_item' value='$id_item'>
                            <input class='static' type='text' name='nuevo_texto' value='$texto' required>
                            <button type='submit'><img class='btn' src='../TaskIt/imagenes/done.png'></button>
                        </form>
                        </div>";
        // Menu del item
        if($rol!='lector'){
            echo"
                        <span class='item-menu m$id_item'>
                            <button><img class='nuevo-subitem-btn s$id_item btn' src='../TaskIt/imagenes/add.png'></button>
                            <form class='eliminar-item' action='sql/itemABM.php' method='post'>
                                <input type='hidden' name='accion' value='eliminar_item'>
                                <input type='hidden' name='id_item' value='$id_item'>
                                <input type='hidden' name='nivel' value='$nivel'>
                                <input type='hidden' name='id_lista' value='$id_lista'>
                                <input type='hidden' name='id_item_padre' value='$id_item_padre'>
                                <button type='submit'><img class='btn' src='../TaskIt/imagenes/delete.png'></button>
                            </form>
                        </span>
                    </div>
                    ";
                    $this->insertarFormularioCrearSubitem($id_item, $nivel);
        }
        // SUBITEMS DEL ITEM
        if($tipo == 'sublista'){
            echo "
                    <ul>
                    ";
                    $proximo_nivel = $nivel + 1;
                    $this->cargarSubItemsDeLista($id_item, $proximo_nivel, $id_lista, $rol, $esconder);
                    echo "
                    </ul>
            ";
        }

        echo "
                </li>
        </div>
        ";
        
    }

    public function insertarFormularioCrearSubitem($id_sublista, $nivel){
        echo "
            <form class='crear-subitem s$id_sublista' action='sql/itemABM.php' method='post'>
                <input type='hidden' name='accion' value='agregar_subitem'>
                <input type='hidden' name='id_sublista' value='$id_sublista'>
                <input type='hidden' name='nivel' value='$nivel'>
                <input class='static' type='text' name='texto' placeholder='Subitem' required>
                <button type='submit'><img src='imagenes/done.png'></button>
            </form>
        ";
    }
}
?>