<?php
session_start();
include("../connect.php");

if (isset($_SESSION['notificar']) && $_SESSION['notificar'] = true) {
    
    $id_usuario_destino = $_SESSION['id_usuario_destino'];
    $texto = $_SESSION['notificacion'];
    $fecha = date('Y-m-d');

    // Crear notificacion
    $sql = "INSERT INTO notificaciones (id_usuario_destino, texto, fecha) VALUES ('$id_usuario_destino', '$texto', '$fecha')";
    if (mysqli_query($conexion, $sql)) {
        echo "Notificación creada con éxito.";
    } else {
        echo "Error al crear notificación: " . mysqli_error($conexion);
    }

    mysqli_close($conexion);
    header("Location: ../index.php");

} else if ($_SESSION['accion'] = 'notificacion abierta') {
    $id_usuario = $_SESSION['id'];

    // Actualizar estado de notificaciones
    $sql = "UPDATE notificaciones SET estado = 'leido' WHERE id_usuario_destino='$id_usuario';";
    if (mysqli_query($conexion, $sql)) {
        echo "Notificacion actualizada con éxito.";
    } else {
        echo "Error al actualizar notificacion: " . mysqli_error($conexion);
    }
    mysqli_close($conexion);
    header("Location: ../index.php");
}

?>