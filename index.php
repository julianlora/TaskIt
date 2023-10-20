<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskIt</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlaza a tu archivo de estilos CSS -->
</head>
<?php require("connect.php"); ?>
<body>
    <!-- Barra de Navegación Superior -->
    <header class="barra">
        <nav>
            <!-- Contenido de la barra de navegación, como logotipo, menús, etc. -->
            BARRA DE NAVEGACION
            <?php
            $usuario = $_SESSION['usuario'];
            echo "<a href='perfil/panel.php'>$usuario</a>"
            ?>
        </nav>
    </header>

    <div class="container">
        <!-- Barra Lateral Izquierda -->
        <aside class="barralateral">
            <!-- Contenido de la barra lateral, como menús, enlaces, etc. -->
            BARRA LATERAL
            <h2>Todo</h2>
        </aside>

        <!-- Ventana Principal -->
        <main class="ventanaprincipal">
            <!-- Contenido principal de la página, como tableros, tarjetas, listas, etc. -->
            
            <button class="nuevalista" onclick=crearLista()>Nueva lista</button>

            <?php
                include("controlador/ListaController.php");
                $controlador = new ListaController($conexion);
                $controlador->mostrarListasEnPantalla();
                
                
            ?>
        </main>
    </div>

    <!-- Footer (Opcional) -->
    <footer>
        <!-- Contenido del pie de página, como información de contacto, derechos de autor, etc. -->
    </footer>
</body>
<script src="script.js"></script>
</html>