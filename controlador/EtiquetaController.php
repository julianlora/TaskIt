<?php

class EtiquetaController {
    private $etiquetas = [];
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->cargarEtiquetasDelUsuario();
    }

    public function cargarEtiquetasDelUsuario(){

        $id = $_SESSION['id'];
        $resultado = mysqli_query($this->conexion, "
        SELECT * from etiquetas
        WHERE id_usuario = '$id'
        ");
        while($etiqueta = mysqli_fetch_array($resultado)){
            $this->etiquetas[] = $etiqueta;
        }
    }

    public function obtenerEtiquetas(){
        return $this->etiquetas;
    }

    public function insertarBotonCrearEtiqueta(){
        echo"
        <form class='nueva-etiqueta' method='post'>
        <input type='hidden' name='accion' value='crear_etiqueta'>
        <button type='submit'><img src='../TaskIt/imagenes/add.png'></button>
        </form>
        ";
    }

    public function insertarFormularioCrearEtiqueta(){
        echo"
        <form class='crear-etiqueta' action='sql/etiquetaABM.php' method='post'>
        <label for='etiqueta'>Etiqueta</label>
        <input type='text' name='etiqueta' required>
        <input type='hidden' name='accion' value='crear_etiqueta'>
        <label for='colorInput'>Color</label>
        <input type='color' id='colorInput' name='colorInput' value='#202124'>
        <button type='submit'>Crear</button>
        </form>";
    }

    public function mostrarEtiquetasEnPantalla(){
        // Insertar etiquetas del usuario
        echo"<p><b>Filtrar por:</b></p>";
        foreach($this->etiquetas as $etiqueta){
            $texto = $etiqueta['texto'];
            echo "
            <form action='sql/etiquetaABM.php' method='post'>
            <input type='hidden' name='accion' value='filtrar_etiqueta'>
            <input type='hidden' name='etiqueta' value='$texto'>
            <button class='etiqueta' type='submit'>- $texto</button>
            </form>
            ";
        }

        
    }
}
?>