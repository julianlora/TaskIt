<?php session_start() ?>

<!DOCTYPE html>
<html lang="es" data-dark>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="data:,">
    <title>TaskIt</title>
</head>
<body >
	
	<?php
	// Reset ventana de workplace
	$_SESSION['ventana'] = 'listas';

	// Cambio de ventanas
	if(isset($_GET['accion']) && $_GET['accion'] != ''){
		$home = 'hide';
		$login = 'hide';
		$registro = 'hide';

		switch($_GET['accion']){
			case 'login':
				$login = '';
				break;
			case 'registro':
				$registro = '';
				break;
		}
		$_GET['accion'] = '';
	} else {
		$home = '';
		$login = 'hide';
		$registro = 'hide';
	}

	// Detectar si el usuario está logueado
	if(isset($_SESSION['usuario'])){
		$login_nav = 'hide';
		$registro_nav = 'hide';
		$listas = '';
		$comenzar='../index.php';
	}else{
		$comenzar ="index.php?accion=login";
	}
	?>

	<!--Seccion Header -->

	
	<header class="header">
		<a href="index.php#" class="logo"><img src='../imagenes/Taskit2.png'></a>

		<div  id="menu-icon"><i class="ltt ltt-desactive">TT</i></div>


		<nav class="navbar">
			<a href="index.php#" class="active">Home</a>
			<a href="index.php#nosotros">Nosotros</a>
			<!-- <a href="index.php#acercaDe">Acerca de</a> -->
			<a href="index.php#contacto">Contacto</a>
			<a class="<?php echo $login_nav?>" href="index.php?accion=login">LogIn</a>
			<a class="<?php echo $registro_nav?>" href="index.php?accion=registro">Registrarse</a>

			<?php
			if(isset($_SESSION['usuario'])){
				echo"
				<span class='to-mis-listas'>
					<a id='workplace' href='../index.php'>Mis listas<img class='goto' src='../imagenes/goto.png'></a>
					<div class='cover btn'></div>
				</span>
				";
			}
			?>

			<span class="active-nav"></span>
		</nav>

	</header>

	<!--Seccion Home -->
	
	<section class="home <?php echo $home ?>" id="home">
		
		<div class="home-content">
			
			<h1>Future <span><i>T</i>aski<i>T</i></span></h1>	
			<div class="text-animate">
				<h3>Work smarter<br> Not harder</h3>
			</div>
			<p>Bienvenido a TaskIt, un espacio virtual diseñado para simplificar tu vida diaria. Ya sea planificando proyectos ambiciosos o creando listas de compras, TaskIt ofrece una plataforma intuitiva para la gestión eficiente de tareas</p>

			<div class="btn-box">
				<!-- <a href="#" class="btn">Nosotros</a> -->
				<a href="<?php echo $comenzar?>" class="btn">Comenzar</a>
			</div>

		</div>

	</section>

	<!--Seccion Home -->

	<!--Seccion Nosotros -->

	<section class="nosotros <?php echo $home ?>" id="nosotros">
		<h2 class="heading">Sobre<span> Nosotros</span></h2>	

		<div class="nosotros-img">
			<p><i class="ltt">TT</i></p>
			<span class="circule-spin"></span>
		</div>

		<div class="sobre-nosotros">

			<p>En TaskIt, no solo ofrecemos una plataforma para la gestión de tareas, sino que también construimos un entorno que potencia la productividad y la colaboración. Nuestra misión es proporcionar a individuos y equipos la herramienta perfecta para planificar, coordinar y ejecutar proyectos de manera eficiente.

Nos apasiona la idea de simplificar la vida diaria mediante una interfaz intuitiva y funciones poderosas. Desde la planificación de proyectos complejos hasta la organización de simples listas, TaskIt se adapta a tus necesidades, permitiéndote concentrarte en lo que realmente importa.

En TaskIt, creemos que la productividad y la colaboración pueden ser emocionantes y accesibles para todos. Únete a nosotros en este viaje mientras transformamos la manera en que abordamos las tareas cotidianas y hacemos que trabajar juntos sea una experiencia más fluida y gratificante.

</p>

			<!-- <div class="btn-box btns">
				<a href="#" class="btn">Leer Mas!</a>
			</div> -->
		</div>

	</section>

	<!--Seccion Nosotros -->

	<!--Seccion Acerca de -->

	<!-- <section id="acercaDe" class="section <?php echo $home ?>" data-scroll-spy>

            <h2><span>Acerca</span> De</h2>

            <video src="#" width="600" height="400" muted controls loop></video>

    </section> -->

	<!--Seccion Acerca de -->



	<!--Seccion Contacto -->
	<section class="contacto <?php echo $home ?>" id="contacto">
		<h2 class="heading">Contacte<span>Nos</span></h2>
		<form method="post" action="../contacto.php">
			
			<div class="input-box">
				<div class="input-field">
					<input type="text" name="nombre" placeholder="Nombre" required="">
					<span class="focus"></span>
				</div>
				<div class="input-field">
					<input type="text" name="asunto"  placeholder="Asunto" required>
					<span class="focus"></span>
				</div>
			</div>
			
			<div class="input-box">
				<div class="input-field">
					<input type="number" name="telefono" placeholder="Numero de telefono" required>
					<span class="focus"></span>
				</div>
				<div class="input-field">
					<input type="text" name="correo" placeholder="Email" required>
					<span class="focus"></span>
				</div>
			</div>

			<div class="textarea-field">
				<textarea id="" cols="30" rows="10" name="mensaje" placeholder="Tu mensaje..." required></textarea>
				<span class="focus"></span>
			</div>

			<div class="btn-box btns">
				<button type="submit" class="btn">Enviar</button>
				
			</div>

			<?php
			if(isset($_GET['resultado']) && $_GET['resultado'] == 'exito'){
				echo "<p style='font-size:18px;margin-top:15px;'>¡Correo enviado!</p>";
			}
			?>

		</form>

	</section>

	<!-- Seccion Login -->

	<section class='login <?php echo $login?>'>
		<h2 class='heading'>Logueate</h2>
		<form action="../login/login.php" method="post">
			<div class="input-box">
				<div class="input-field">
					<input type="text"  maxlength="12" name="usuario" placeholder="Usuario" required="">
					<span class="focus"></span>
				</div>
			</div>
			<div class="input-box">
				<div class="input-field">
					<input type="password" maxlength="12"  name="password" placeholder="Contraseña" required="">
					<span class="focus"></span>
				</div>
			</div>
			<div class="btn-box btns">
				<button type="submit" class="btn">Login</button>
			</div><br>
			<p style='font-size:1.5em;margin-top:20px;'>¿No tienes cuenta? <a href="index.php?accion=registro">Registrate</a></p>
		</form>
		
	</section>

	<!-- Seccion Registro -->

	<section class='registro <?php echo $registro?>'>
		<h2 class='heading'>Regístrate</h2>
		<form action="../login/registro.php" method="post">
			<div class="input-box">
				<div class="input-field">
					<input type="text"  maxlength="12" name="nombre" placeholder="Nombre" required="">
					<span class="focus"></span>
				</div>
				<div class="input-field">
					<input type="text"  maxlength="12" name="apellido" placeholder="Apellido" required="">
					<span class="focus"></span>
				</div>
				<div class="input-field">
					<input type="email"  maxlength="50" name="email" placeholder="Correo" required="">
					<span class="focus"></span>
				</div>
				<div class="input-field">
					<input type="text"  maxlength="12" name="usuario" placeholder="Usuario" required="">
					<span class="focus"></span>
				</div>
			</div>
			<div class="input-box">
				<div class="input-field">
					<input type="password" maxlength="12"  name="password" placeholder="Contraseña" required="">
					<span class="focus"></span>
				</div>
			</div>
			<div class="btn-box btns">
				<button type="submit" class="btn">Regístrarse</button>
			</div><br>
			<p style='font-size:1.5em;margin-top:20px;'>¿Ya tienes cuenta? <a href="index.php?accion=login">Logueate</a></p>
		</form>
		
	</section>
	
	<!--Seccion Footer -->
	<footer class="footer" id="footer">

		<div class="footer-text">
			
			<p>Empodera tu productividad con TaskIt.<br> Contacto: equipo-taskit@outlook.com</p>

		</div>

		<div class="footer-iconTop">
			
			<a href="#home"><i class="bx-up-arrow-alt">Up!</i></a>

		</div>

	</footer>

	<!--Seccion Footer -->
	
    <script src="js/index.js" type="module"></script>
</body>
</html>