<?php

class ItemController {
    private $items = [];
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $resultado = mysqli_query($this->conexion, "SELECT * from items");
        while($fila = mysqli_fetch_array($resultado)){
            $this->items[] = $fila;
        }
    }

    public function cargarItemsDeLista($id_lista){
        foreach($this->items as $item){
            if ($item['id_lista'] == $id_lista){
                $this->mostrarItemEnPantalla($item);
            }
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