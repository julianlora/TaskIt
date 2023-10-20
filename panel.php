<?php session_start();?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>Panel</title>
</head>
<?php

if(isset($_SESSION['nombre']) and isset($_SESSION['apellido']) ){
	
echo "Hola! ";
echo $_SESSION['nombre']." ";
echo $_SESSION['apellido'];
echo "<p><img src='imagenes/cuadro.jpg' /></p>";
echo "<a href='salir.php'>Cerrar sesion</a>";

	
}else{
	echo "Solo usuarios registrados...";
//	include("form_registro.php");
	include("form_login.php");
}
?>

<body>
</body>
</html>