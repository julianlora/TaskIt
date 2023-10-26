<?php
session_start();
include("../connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $accion = $_POST["accion"];
    $id_usuario = $_SESSION['id'];
    
    switch($accion){

        case "crear_etiqueta":
            $texto = $_POST["etiqueta"];
            // Crear la consulta SQL
            $sql = "INSERT INTO etiquetas (texto, id_usuario) VALUES ('$texto', '$id_usuario')";
            // Ejecutar la consulta
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }
            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);
            header("Location: ../index.php");
            break;

        case "filtrar_etiqueta":
            $etiqueta = $_POST["etiqueta"];
            
            $_SESSION['etiqueta'] = $etiqueta;
            
            header("Location: ../index.php");
            break;
        
        default:
            echo "No existe accion con ese nombre";
            break;
    }
    
} else {
    echo "El formulario no se ha enviado.";
}

?>