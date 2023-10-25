<?php
session_start();
$_SESSION['ventana'] = 'calendario';
header("Location: index.php");
?>