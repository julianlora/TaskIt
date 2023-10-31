<?php
session_start();
$_SESSION['ventana'] = 'listas';
$_SESSION['etiqueta'] = 'todas';
header("Location: index.php");
?>