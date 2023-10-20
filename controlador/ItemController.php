<?php

class ItemController {
    private $items = [];
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function cargarItemsDeLista($id_lista){

        $resultado = mysqli_query($this->conexion, "
        SELECT * from items
        WHERE id_lista = '$id_lista'
        ");
        while($item = mysqli_fetch_array($resultado)){
            $this->items[] = $item;
            $this->mostrarItemEnPantalla($item);
        }
        
    }

    public function mostrarItemEnPantalla($item){

        $texto = $item['texto'];
        echo "
            <li>
                <input type='checkbox'>
                $texto
            </li>
            ";

        
    }
}
?>