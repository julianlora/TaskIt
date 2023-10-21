<?php
    session_start();
    require("connect.php"); 
    include("controlador/ListaController.php");
    $controladorlista = new ListaController($conexion);
    include("controlador/EtiquetaController.php");
    $controladoretiqueta = new EtiquetaController($conexion);
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
            <div style="display:flex;">
                <h2>Mis listas</h2>
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
        </aside>

        <!-- Ventana Principal -->
        <main class="ventanaprincipal">
            <!-- Contenido principal de la página, como tableros, tarjetas, listas, etc. -->

            <?php
                $controladorlista->mostrarListasEnPantalla();
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