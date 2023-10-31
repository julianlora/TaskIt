<?php
session_start();
$_SESSION['accion'] = 'none';
$_SESSION['resultado'] = 'none';
header("Location: index.php");
?>