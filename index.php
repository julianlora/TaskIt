<?php
    session_start();
    require("connect.php"); 
    require("controlador/ListaController.php");
    $controladorlista = new ListaController($conexion);
    require("controlador/EtiquetaController.php");
    $controladoretiqueta = new EtiquetaController($conexion);
    require("controlador/CalendarioController.php");
    $controladorcalendario = new CalendarioController($conexion);
    require("controlador/NotificacionController.php");
    $controladornotificacion = new NotificacionController($conexion);
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
            <a href='index.php'><img class='logo' src="imagenes/Taskit2.png"></a>
            <form class='buscador' method="post">
                <label>Buscar
                <input type='hidden' name='accion' value='buscar'>
                <input type="search" name="busqueda" placeholder="Buscar..." />
                <input type="submit" value="Enviar">
                </label>
            </form>
            <?php
            $usuario = $_SESSION['usuario'];
            echo "
            <div class='usuario'>
                <div class='notificaciones-btn'>
                    <img class='bell' src='imagenes/bell.png'>";
                    $pendiente = $controladornotificacion->getNotificacionesPendientes();
                    if ($pendiente){
                        echo"<img class='yellow-circle show' src='imagenes/yellow-circle.png'>";
                    }
            $show = '';
            if($_SESSION['accion'] == 'notificacion abierta'){
                $show = 'show';
                $_SESSION['accion'] = 'notificacion leida';
            }
            echo"
                </div>
                <div id='notificaciones' class='notificaciones $show'>
                    <h2>Notificaciones</h2>";
                    $controladornotificacion->mostrarNotificaciones();
            echo"
                </div>
                <p class='nombre-usuario'>$usuario</p>
                <img class='profile-img' src='imagenes/profile-circle.png'>
                <div class='panel'>
                    Hola! $usuario
                    <a href='perfil/salir.php'>Cerrar sesión</a>
                </div>
            </div>"
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
            <a href='to_listas_compartidas.php'><h2>Listas compartidas</h2></a>
            <a href='to_calendario.php'><h2>Calendario</h2></a>
        </aside>

        <!-- Ventana Principal -->

        <?php
            if ($_SESSION['ventana'] != 'calendario'){
                $controladorlista->mostrarListasEnPantalla($_SESSION['etiqueta']);
            } else {
                $controladorcalendario->mostrarCalendario();
            }
            
        ?>
            
    </div>

    <!-- Footer (Opcional) -->
    <footer>
        <!-- Contenido del pie de página, como información de contacto, derechos de autor, etc. -->
    </footer>
</body>
<script src="script.js"></script>
</html>