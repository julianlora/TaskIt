<?php
session_start();
$_SESSION['ventana'] = 'listas compartidas';
$_SESSION['etiqueta'] = '';
header("Location: index.php");
?>