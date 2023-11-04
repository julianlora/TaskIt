<?php
session_start();
$_SESSION['ventana'] = 'listas';
$_SESSION['etiqueta'] = '';
header("Location: index.php");
?>