<?php

class ItemController {
    private $items = [];
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function cargarItemsDeLista($id_lista, $nivel){

        $resultado = mysqli_query($this->conexion, "
        SELECT * from items
        WHERE id_lista = '$id_lista' and nivel = 0
        ");
        while($item = mysqli_fetch_array($resultado)){
            $this->items[] = $item;
            $this->mostrarItemEnPantalla($item);
        }
        // mysqli_close($this->conexion);
        
    }

    public function cargarSubItemsDeLista($id_item_padre, $nivel, $id_lista){

        $resultado = mysqli_query($this->conexion, "
        SELECT * from items
        WHERE id_lista = '$id_lista' and nivel = $nivel and id_item_padre = '$id_item_padre' 
        ");
        while($item = mysqli_fetch_array($resultado)){
            $this->items[] = $item;
            $this->mostrarItemEnPantalla($item);
        }
        // mysqli_close($this->conexion);
        
    }

    public function mostrarItemEnPantalla($item){

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
            $img_path = '../TaskIt/imagenes/checked.png';
        }
        echo "
                        <form action='sql/itemABM.php' method='post'>
                            <input type='hidden' name='accion' value='$accion'>
                            <input type='hidden' name='id_item' value='$id_item'>
                            <input type='hidden' name='nivel' value='$nivel'>
                            <input type='hidden' name='id_lista' value='$id_lista'>
                            <button class='checkbox'><img class='$clase' src='$img_path'></button>
                        </form>
                        <p class='item-texto'>$texto</p>
                        <span class='item-menu m$id_item'>
                            <button class='nuevo-subitem-btn s$id_item'>+</button>
                            <form class='eliminar-item' action='sql/itemABM.php' method='post'>
                                <input type='hidden' name='accion' value='eliminar_item'>
                                <input type='hidden' name='id_item' value='$id_item'>
                                <input type='hidden' name='nivel' value='$nivel'>
                                <input type='hidden' name='id_lista' value='$id_lista'>
                                <input type='hidden' name='id_item_padre' value='$id_item_padre'>
                                <button type='submit'>x</button>
                            </form>
                        </span>
                    </div>
                    ";
                    $this->insertarFormularioCrearSubitem($id_item, $nivel);

        // SUBITEMS DEL ITEM
        if($tipo == 'sublista'){
            echo "
                    <ul>
                    ";
                    $proximo_nivel = $nivel + 1;
                    $this->cargarSubItemsDeLista($id_item, $proximo_nivel, $id_lista);
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
                <input type='text' name='texto' required>
                <button type='submit'>Agregar subitem</button>
            </form>
        ";
    }
}
?>