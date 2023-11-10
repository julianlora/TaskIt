<?php

class NotificacionController {
    private $notificaciones = [];
    private $conexion;
    private $notificacionesPendientes = false;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->cargarNotificacionesDelUsuario();
    }

    public function cargarNotificacionesDelUsuario(){

        $id = $_SESSION['id'];
        $resultado = mysqli_query($this->conexion, "SELECT * from notificaciones WHERE id_usuario_destino = '$id' ORDER BY id DESC");
        while($notificacion = mysqli_fetch_array($resultado)){
            $this->notificaciones[] = $notificacion;
            if ($notificacion['estado'] == 'pendiente'){
                $this->notificacionesPendientes = true;
            }
        }
    }

    public function getNotificacionesPendientes(){
        return $this->notificacionesPendientes;
    }

    public function mostrarNotificaciones(){
        foreach($this->notificaciones as $notif){
            $mensaje = $notif['mensaje'];
            echo"
            <div class='notificacion'>
                <p>- $mensaje</p>";
                if($notif['estado'] == 'pendiente'){
                    echo"<img class='yellow-circle-notif' src='../TaskIt/imagenes/yellow-circle.png'>";
                }
            echo"
            </div>";
        }
    }

    public function enviarNotificaciones($notificaciones){
        $_SESSION['notificar'] = true;
        $_SESSION['notificaciones'] = $notificaciones;
        header("Location: notificacionABM.php");
    }
}
?>