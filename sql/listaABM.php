<?php
session_start();
include("../connect.php");
// Verificar si se ha enviado el formulario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $accion = $_POST["accion"];
    
    switch($accion){
        case "crear_lista":
            $titulo = $_POST["titulo"];

            $id_usuario = $_SESSION['id'];
            $etiqueta = $_POST["etiqueta"];
            $fecha = $_POST['fecha'];

            // Crear la consulta SQL
            $sql = "INSERT INTO listas (titulo, id_usuario) VALUES ('$titulo', '$id_usuario')";

            // Ejecutar la consulta
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }

            if($etiqueta != 'ninguna'){
                $sql = "
                UPDATE listas
                SET etiqueta = '$etiqueta'
                WHERE titulo='$titulo' AND id_usuario = '$id_usuario';";
                if (mysqli_query($conexion, $sql)) {
                    echo "Etiqueta actualizada con éxito.";
                } else {
                    echo "Error al actualizar la etiqueta: " . mysqli_error($conexion);
                }
            }

            if ($fecha != ''){
                $sql = "
                UPDATE listas
                SET fecha_finalizacion = '$fecha'
                WHERE titulo='$titulo' AND id_usuario = '$id_usuario';";
                if (mysqli_query($conexion, $sql)) {
                    echo "Etiqueta actualizada con éxito.";
                } else {
                    echo "Error al actualizar la etiqueta: " . mysqli_error($conexion);
                }
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);
            header("Location: ../index.php");
            break;
        
        case "eliminar_lista":
            $id_lista = $_POST['id_lista'];
            // Crear la consulta SQL
            $sql = "DELETE FROM listas WHERE id = '$id_lista';";
            if (mysqli_query($conexion, $sql)) {
                echo "Registro eliminado con éxito.";
            } else {
                echo "Error al eliminar el registro: " . mysqli_error($conexion);
            }
            mysqli_close($conexion);
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