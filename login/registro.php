<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registro</title>
</head>

<body>

<?php
	// $nombre = $_POST['nombre'];
	// $apellido = $_POST['apellido'];
	// $email = $_POST['email'];
	$usuario = $_POST['usuario'];
	$password = md5($_POST['password']);

/*if(isset($_POST['newsletter'])){
	$news="si";
}else{
	$news="no";
}*/
	// if ($_POST['newsletter'] === "") {
	// 	$news="no";
	// } else {
	// 	$news="si";
	// }


	include("connect.php");

	// $_SESSION['nombre'] = $nombre;

	$consulta = mysqli_query($conexion, "INSERT INTO usuarios (usuario, password) VALUES('$usuario', '$password')");


	header("Location:form_login.php");

?>	
    

</body>
</html>