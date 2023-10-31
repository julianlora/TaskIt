<?php
session_start();
$_SESSION['accion'] = 'notificacion abierta';
header("Location: sql/notificacionABM.php");
?>