<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
	<h2>Registrate en el sitio</h2>
    <form action="registro.php" method="post" >
    	<!-- <label>Nombre
        	<input type="text" name="nombre" required />
        </label><br />
		<label>Apellido
        	<input type="text" name="apellido" required />
        </label><br />
        <label>Email
        	<input type="email" name="email" required />
        </label><br /> -->
        <label>Nombre de usuario
        	<input name="usuario" type="text" maxlength="12" />
        </label><br />
        <label>Contraseña
        	<input type="password" name="password" maxlength="12" />
        </label><br />
        <!-- <label>
            <input name="newsletter" type="checkbox" value="si" checked="checked" /> Sí, deseo recibir informacion por mail.
        </label><br /> -->
        <input type="submit" value="Registrarse"/>	
    </form>

</body>
</html>