<?php
session_start();
$_SESSION['ventana'] = 'listas';
header("Location: index.php");
?>