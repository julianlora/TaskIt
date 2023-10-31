<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login de Usuarios</title>
</head>

<body>

<?php
$usuario=$_POST['usuario'];
$password=md5($_POST['password']);

include("../connect.php");

$consulta=mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password'");

$resultado=mysqli_num_rows($consulta);

if($resultado!=0){
	$respuesta=mysqli_fetch_array($consulta);
	header("Location: ../index.php");
	
	// $_SESSION['nombre']=$respuesta['nombre'];
	// $_SESSION['apellido']=$respuesta['apellido'];
	$_SESSION['usuario']=$respuesta['usuario'];
	$_SESSION['id']=$respuesta['id'];
	$_SESSION['ventana']='listas';
	$_SESSION['etiqueta']='todas';
	$_SESSION['accion'] = 'none';
		
	// 	echo "Hola ".$_SESSION['nombre']." ".$_SESSION['apellido']."<br />";
	// 	echo "Acceso al panel de usuarios.<br/>";
	// 	echo "<a href='panel.php'>Panel</a>";	

}else{
	echo "No es un usuario registrado";
	include ("form_registro.php");
}

?>

</body>
</html>