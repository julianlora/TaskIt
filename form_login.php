<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Formulario de Login</title>
</head>

<body>
	<h2>Logueate</h2>
	<form action="login.php" method="post">
    	<label>Usuario
        	<input name="usuario" type="text" maxlength="12" />
        </label><br />
        <label>Contraseña
        	<input type="password" name="password" maxlength="12" />
        </label><br />
        	<input type="submit" value="Login"/>	
    </form>
    <a href="form_registro.php">Registrate</a> si no sos usuario.
    


</body>
</html>