<?php
    session_start();
    require("connect.php"); 
    require("controlador/ListaController.php");
    $controladorlista = new ListaController($conexion);
    require("controlador/EtiquetaController.php");
    $controladoretiqueta = new EtiquetaController($conexion);
    require("controlador/CalendarioController.php");
    $controladorcalendario = new CalendarioController($conexion);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskIt</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlaza a tu archivo de estilos CSS -->
</head>
<body>
    <h1 hidden="true">TaskIt</h1>
    <!-- Barra de Navegación Superior -->
    <header class="barra">
        <nav>
            <!-- Contenido de la barra de navegación, como logotipo, menús, etc. -->
            BARRA DE NAVEGACION
            <form class='buscador' method="post">
                <label>Buscar
                <input type='hidden' name='accion' value='buscar'>
                <input type="search" name="busqueda" placeholder="Buscar..." />
                <input type="submit" value="Enviar">
                </label>
            </form>
            <?php
            $usuario = $_SESSION['usuario'];
            echo "<a class='profile' href='perfil/panel.php'><img class='profile-icon' src='imagenes/profile-circle.png'></a>"
            ?>
        </nav>
    </header>

    <div class="container">
        <!-- Barra Lateral Izquierda -->
        <aside class="barralateral">
            <div style="display:flex;">
                <a href="to_listas.php"><h2>Mis listas</h2></a>
                <?php
                if (!isset($_POST['accion']) || $_POST['accion'] != 'nueva_lista'){
                    $controladorlista->insertarBotonNuevaLista();
                }
                ?>
            </div>
            <?php
                if (isset($_POST['accion']) && $_POST['accion'] == 'nueva_lista'){
                    $controladorlista->insertarFormularioCrearLista();
                }
                $controladoretiqueta->mostrarEtiquetasEnPantalla();
                if (isset($_POST['accion']) && $_POST['accion'] == 'crear_etiqueta'){
                    $controladoretiqueta->insertarFormularioCrearEtiqueta();
                } else {
                    $controladoretiqueta->insertarBotonCrearEtiqueta();
                }
            ?>
            <a href='to_calendario.php'><h2>Calendario</h2></a>
        </aside>

        <!-- Ventana Principal -->
        <!-- <main class="ventanaprincipal"> -->
            <!-- Contenido principal de la página, como tableros, tarjetas, listas, etc. -->

        <?php
            if ($_SESSION['ventana'] == 'listas'){
                $controladorlista->mostrarListasEnPantalla($_SESSION['etiqueta']);
            } else {
                $controladorcalendario->mostrarCalendario();
            }
            
        ?>
            
        <!-- </main> -->
    </div>

    <!-- Footer (Opcional) -->
    <footer>
        <!-- Contenido del pie de página, como información de contacto, derechos de autor, etc. -->
    </footer>
</body>
<script src="script.js"></script>
</html>