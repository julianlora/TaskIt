<?php
session_start();
include("../connect.php");
include("../controlador/NotificacionController.php");
$controladornotificacion = new NotificacionController($conexion);
    
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
        
        // Enviar notificacion
        $notificaciones = array(
            array('destinatario' => $id_usuario, 'mensaje'=> "¡Gracias por suscribirte a TaskIt! Ahora puedes compartir tus listas para crear y administrar equipos de trabajo más eficientes."
        ));
        $controladornotificacion->enviarNotificaciones($notificaciones);

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
        break;

    default:
        echo "No existe accion con ese nombre";
        break;
}

?>