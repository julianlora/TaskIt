<?php
session_start();
include("../connect.php");
// Verificar si se ha enviado el formulario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $accion = $_POST["accion"];
    
    switch($accion){
        case "crear_lista":
            $titulo = $_POST["titulo"];
            $id = $_SESSION['id'];
            // Crear la consulta SQL
            $sql = "INSERT INTO listas (titulo, id_usuario) VALUES ('$titulo', '$id')";

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
    }
    
} else {
    echo "El formulario no se ha enviado.";
}

?>