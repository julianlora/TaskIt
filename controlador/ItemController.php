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
        WHERE id_lista = '$id_lista' and nivel = $nivel 
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

        echo "
            <div class='item'>
                <li>
                    <div class='cabecera_item'>";
        
        // CHECKBOX
        if (!$checked){
            // Checkeada
            echo "
                        <form action='sql/itemABM.php' method='post'>
                            <input type='hidden' name='accion' value='checkear'>
                            <input type='hidden' name='id_item' value='$id_item'>
                            <input type='hidden' name='nivel' value='$nivel'>
                            <input type='hidden' name='id_lista' value='$id_lista'>
                            <button class='checkbox'><img class='unchecked' src='../TaskIt/unchecked.png'></button>
                        </form>
                        $texto";
        } else {
            // No checkeada
            echo "
                        <form action='sql/itemABM.php' method='post'>
                            <input type='hidden' name='accion' value='descheckear'>
                            <input type='hidden' name='id_item' value='$id_item'>
                            <input type='hidden' name='nivel' value='$nivel'>
                            <input type='hidden' name='id_lista' value='$id_lista'>
                            <button class='checkbox'><img class='checked' src='../TaskIt/checked.png'></button>
                        </form>
                        $texto";
        }
                        
        
        if (isset($_POST['accion']) && $_POST['accion'] == 'nuevo_subitem' && $_POST['id_item'] == $id_item){
            $this->insertarFormularioCrearSubitem($id_item, $nivel);
        } else {
            // BOTON DE NUEVO SUBITEM
            echo "
                        <form class='nuevo_subitem_btn' method='post'>
                            <input type='hidden' name='accion' value='nuevo_subitem'>
                            <input type='hidden' name='id_item' value='$id_item'>
                            <input type='hidden' name='id_lista' value='$id_lista'>
                            <button type='submit'>+</button>
                        </form>
            ";
        }

        echo"
                    </div>
        ";

        // SUBITEMS DEL ITEM
        if($tipo == 'sublista'){
            echo "
                    <ul>
                    ";
                    $proximo_nivel = $nivel + 1;
                    $this->cargarItemsDeLista($id_item, $proximo_nivel);
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
            <form action='sql/itemABM.php' method='post'>
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