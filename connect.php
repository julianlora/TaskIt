<?php
$conexion = mysqli_connect("localhost", "root", "root", "taskit") or die("No se pudo establecer una conexión");
if (!$conexion) {
    die("Error en la conexión a la base de datos: " . mysqli_connect_error());
}
?>