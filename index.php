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
            <a href="home/index.php"><img class='logo' src="imagenes/Taskit2.png"></a>

            <form method="post" class="search-form">
                <div class="search-bar">
                    <input type="text" name="busqueda" placeholder="Buscar...">
                    <button type="submit">
                    <img src="imagenes/lupa.png" alt="Icono de Búsqueda">
                    </button>
                </div>
                <input type='hidden' name='accion' value='buscar'>
            </form>
            <?php
            $usuario = $_SESSION['nombre'].' '.$_SESSION['apellido'];
            echo "
            <div class='usuario'>";
            if ($_SESSION['categoria'] == 'estandar'){
                echo"
                <div class='suscripcion-btn'><a href='to_suscribirse.php'><b>Suscribirse</b></a></div>";
            }
            echo"
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
                <img class='profile-img' src='imagenes/profile-circle.png'>
                <div class='panel'>
                    <p>¡Hola $usuario!</p>
                    <a href='home/index.php'>Home</a>
                    <a href='home/index.php#contacto'>Contacto</a>
                    <a href='perfil/salir.php'>Cerrar sesión</a>
                </div>
            </div>"
            ?>
        </nav>
    </header>

    <?php
    if ($_SESSION['ventana'] == 'listas' | $_SESSION['ventana'] == 'listas compartidas' | $_SESSION['ventana'] == 'calendario'){
        $container = 'display:flex;';
    } else {
        $container = 'display:none;';
    }
    ?>
    <div class="container" style=<?php echo $container?>>
        <!-- Barra Lateral Izquierda -->
        
        <aside class="barralateral">
            <div class='cover'></div>
            <a href="to_listas.php"><img src='imagenes/listas.png'><h2>Mis listas</h2></a>
            <?php
            if (!isset($_POST['accion']) || $_POST['accion'] != 'nueva_lista'){
                $controladorlista->insertarBotonNuevaLista();
            }
            ?>
            <?php
                $controladorlista->insertarFormularioCrearLista();
                $controladoretiqueta->mostrarEtiquetasEnPantalla();
                if (isset($_POST['accion']) && $_POST['accion'] == 'crear_etiqueta'){
                    $controladoretiqueta->insertarFormularioCrearEtiqueta();
                } else {
                    $controladoretiqueta->insertarBotonCrearEtiqueta();
                }
            ?>
            <a href='to_listas_compartidas.php'><img src='imagenes/share2.png'><h2>Compartidas conmigo</h2></a>
            <a href='to_calendario.php'><img src='imagenes/calendario.png'><h2>Calendario</h2></a>
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

    <!-- VENTANA SUSCRIPCION -->

    <?php
    if ($_SESSION['ventana'] == 'suscripcion'){
        $display = 'display:block;';
    } else {
        $display = 'display:none;';
    }
    ?>

    <div class='ventana-suscripcion' style='<?php echo $display?>'>
        <h1>Elige un plan</h1>
        <p>Sucríbete a TaskIt para compartir tus listas y crear equipos de trabajo más eficientes.</p>
        <div class="subscription-panel static">
            
            <div class="subscription-card mensual">
                <h2>Mensual</h2>
                <p class='precio-mes'><b>$100/mes</b></p>
                <button class='plan-btn mensual static'>Seleccionar</button>
            </div>
            <div class="subscription-card anual">
                <h2>Anual</h2>
                <p class='precio-mes'><b>$50/mes</b></p>
                <p>$600 precio total</p>
                <button class='plan-btn anual static'>Seleccionar</button>
            </div>

        </div>
        <!-- Insertar boton de pago -->
        <?php
        if ($_SESSION['ventana'] == 'suscripcion'){
            include('mercadopago.php');
        }
        ?>
    </div>
    
</body>
<script src="script.js"></script>
</html>