<?php
session_start();
include("../connect.php");
    
$accion = $_GET["accion"];
$id_usuario = $_SESSION['id'];

switch($accion){

    case "suscripcion":

        $plan = $_GET['plan'];
        $fecha = date('Y-m-d');
        
        // Crear suscripcion
        $sql = "INSERT INTO suscripciones (id_usuario, fecha, plan) VALUES ('$id_usuario', '$fecha', '$plan')";
        if (mysqli_query($conexion, $sql)) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }

        // Cambiar categoria del usuario
        $sql = "UPDATE usuarios SET categoria = 'suscriptor' WHERE id = '$id_usuario'";
        if (mysqli_query($conexion, $sql)) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }

        $_SESSION['ventana'] = 'listas';
        $_SESSION['categoria'] = 'suscriptor';
        
        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
        header("Location: ../index.php");
        break;

    default:
        echo "No existe accion con ese nombre";
        break;
}

?>