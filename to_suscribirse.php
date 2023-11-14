<?php
session_start();
$_SESSION['ventana'] = 'suscripcion';
header("Location: index.php");
?>