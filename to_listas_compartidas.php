<?php
session_start();
$_SESSION['ventana'] = 'listas compartidas';
header("Location: index.php");
?>