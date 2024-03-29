<?php
include("ItemController.php");
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ListaController {
    private $listas = [];
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->cargarListasDelUsuario();
    }

    public function getLista($id){
        foreach($this->listas as $lista){
            if($lista['id'] == $id){
                return $lista;
            }
        }
    }

    public function cargarListasDelUsuario(){
        $id = $_SESSION['id'];
        // No filtradas por buscador
        if (!isset($_POST['accion']) || $_POST['accion'] != 'buscar'){
            $resultado = mysqli_query($this->conexion, "
            SELECT * from listas
            WHERE id_usuario = '$id'
            ORDER BY id DESC");
            while($fila = mysqli_fetch_array($resultado)){
                $this->listas[] = $fila;
            }
            // Cargar listas de las que es miembro excepto las creadas por el usuario
            $resultado = mysqli_query($this->conexion, "SELECT * FROM listas WHERE id IN (SELECT listas_compartidas.id_lista from listas_compartidas WHERE listas_compartidas.id_usuario = '$id') and id_usuario != $id");
            while($fila = mysqli_fetch_array($resultado)){
                $this->listas[] = $fila;
            }
         // Filtradas por buscador
        } else { 
            // Propias
            $busqueda = $_POST['busqueda'];
            $resultado = mysqli_query($this->conexion, "
            SELECT * from listas
            WHERE id_usuario = '$id' and (titulo LIKE '%$busqueda%' or (
                SELECT COUNT(items.id) FROM items
                WHERE items.id_lista = listas.id and items.texto LIKE '%$busqueda%'
            ) > 0) and acceso != 'compartido'
            ORDER BY id DESC");
            while($fila = mysqli_fetch_array($resultado)){
                $this->listas[] = $fila;
            }
            // Compartidas
            $resultado = mysqli_query($this->conexion, "
            SELECT * from listas
            WHERE id IN (
                SELECT listas_compartidas.id_lista FROM listas_compartidas
                WHERE listas_compartidas.id_usuario = '$id'
            ) and (titulo LIKE '%$busqueda%' or (
                SELECT COUNT(items.id) FROM items
                WHERE items.id_lista = listas.id and items.texto LIKE '%$busqueda%'
            ) > 0) 
            ORDER BY id DESC");
            while($fila = mysqli_fetch_array($resultado)){
                $this->listas[] = $fila;
            }
        }

        $this->notificarFinalizacion();
    }

    public function notificarFinalizacion(){
        $hoy = date('Y-m-d');
        foreach($this->listas as $lista){
            // Si finaliza hoy mandar mail
            if($lista['fecha_finalizacion'] == $hoy && $lista['finalizacion_notificada'] == False){

                // Dirección de correo electrónico a la que se enviará el mensaje
                $destinatario = "equipo-taskit@outlook.com";
                
                // Asunto del correo
                $titulo = $lista['titulo'];
                $asunto = "Finalizacion de lista: '$titulo'";

                // Construye el cuerpo del mensaje
                $cuerpoMensaje = "La lista '$titulo' ha llegado a su fecha de finalizacion.";

                // Crea una nueva instancia de PHPMailer
                $mail = new PHPMailer(true);
                
                // Configuración del servidor SMTP de Outlook
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'equipo-taskit@outlook.com';
                $mail->Password = 'laboratorio23';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Configuración adicional
                $address = $_SESSION['email'];
                $mail->setFrom('equipo-taskit@outlook.com', 'TaskIt');
                $mail->addAddress($address, 'Destinatario');

                // Contenido del correo
                $mail->isHTML(false);
                $mail->Subject = $asunto;
                $mail->Body = $cuerpoMensaje;

                // Envía el correo
                $mail->send();

                $id = $lista['id'];
                $sql = "UPDATE listas SET finalizacion_notificada = true WHERE id ='$id';";
                if (mysqli_query($this->conexion, $sql)) {
                    echo "Lista actualizada con éxito.";
                } else {
                    echo "Error al actualizar lista: " . mysqli_error($conexion);
                }
                
            }
        }
    }

    public function insertarBotonNuevaLista(){
        // echo"
        // <form method='post'>
        // <input type='hidden' name='accion' value='nueva_lista'>
        // <button type='submit'>Nueva lista</button>
        // </form>";
        echo"
        <img class='nueva-lista static' src='../TaskIt/imagenes/add2.png'>";
    }

    public function insertarFormularioCrearLista(){
        $date = date('Y-m-d');
        echo"
        <form class='crear-lista animation static' action='sql/listaABM.php' method='post'>
            <label for='titulo'>Título</label>
            <input class='static' type='text' name='titulo' required>
            <label for='etiqueta'>Etiqueta</label>
            <select class='static' name='etiqueta'>
                <option value='' selected>--Sin etiqueta--</option>
            ";
            $controladoretiqueta = new EtiquetaController($this->conexion);
            $etiquetas = $controladoretiqueta->obtenerEtiquetas();
            foreach($etiquetas as $etiqueta){
                $texto = $etiqueta['texto'];
                echo"
                <option value='$texto'>$texto</option>
                ";
            }
            echo"
            </select><br>
            <label for='fecha'>Fecha de finalización:</label>
            <input class='static' type='date' id='fecha' name='fecha' min='$date'>

            <input type='hidden' name='accion' value='crear_lista'>
            <button class='static' type='submit'>Crear</button>
        </form>";
    }

    public function mostrarListasEnPantalla($etiqueta){
        $item_controlador = new ItemController($this->conexion);
        echo "<main>";
        if (isset($_POST['accion']) && $_POST['accion'] == 'buscar'){ // Si se realizó una búsqueda
            $busqueda = $_POST['busqueda'];
            echo "<p class='busqueda-aclariacion'>Resultados de búsqueda por '$busqueda':</P>";
        }
        if($etiqueta != ''){
            echo "<h1>$etiqueta</h1>";
        }
        echo "<div class='ventanaprincipal listas'>";
        foreach($this->listas as $lista){
            $id = $lista['id'];
            $esconder = $lista['esconder_terminadas'];
            $id_usuario = $_SESSION['id'];
            $propietarioId = $lista['id_usuario']; 

            // Buscar rol del usuario en la lista
            if($lista['acceso'] == 'compartido'){
                $resultado = mysqli_fetch_array(mysqli_query($this->conexion, "SELECT * from listas_compartidas WHERE id_usuario = '$id_usuario' and id_lista = '$id'"));
                $rol = $resultado['rol'];
            } else {
                $rol = 'administrador';
            }
            // Filtrar por etiqueta y por acceso
            if (($etiqueta == $lista['etiqueta'] || $etiqueta == '') && (($_SESSION['ventana'] == 'listas compartidas' && $lista['acceso'] == 'compartido' && $propietarioId != $id_usuario) || ($_SESSION['ventana'] == 'listas' && $propietarioId == $id_usuario))){
                $titulo = $lista['titulo'];
                echo "
                <article id='$id' class='lista draggable'>
                    <div class='cover-lista'></div>
                    <div class='cover-lista-2'></div>
                    <div class='top-menu'>
                        <div class='drag $id'><img src='../TaskIt/imagenes/drag.png' draggable='false'></div>
                            <div class='top-right-menu'>";
                // Si la lista es compartida insertar icono
                if ($lista['acceso'] == 'compartido'){
                    $this->insertarIconoMiembros($id, $propietarioId);
                }
                // Si tiene etiqueta agregar rotulo
                if ($etiqueta == '' && $lista['etiqueta'] != ''){
                    $this->insertarRotulo($lista['etiqueta']);
                }
                // MENU DE OPCIONES
                echo"
                            <div class='listmenu'>
                                <div class='dropdown'>
                                    <img class='opcionesbtn $id' src='imagenes/three-dots.png'>
                                    <div id='opciones-$id' class='dropdown-content'>";
                                    $this->insertarOpcionEsconderTerminadas($lista);
                                    $this->insertarOpcionModificarEtiqueta($lista);
                                    // Funcionalidades exclusivas de administrador
                                    if($rol == 'administrador'){
                                        $this->insertarOpcionModificarFecha($lista);
                                        if($_SESSION['categoria'] == 'suscriptor'){
                                            $clase = '';
                                        } else {
                                            $clase = 'disabled';
                                        }
                                        echo"
                                        <img class='starimg' src='../TaskIt/imagenes/star.png'>
                                        <button id='mostrarVentana-$id' class='paper-btn opcionbtn compartir $id show $clase'>Compartir</button>";
                                    }
                                    if($propietarioId == $_SESSION['id']){
                                        $boton = 'Eliminar';
                                    } else {
                                        $boton = 'Abandonar';
                                    }
                                    // Botón para eliminar o abandonar
                                    echo"
                                    <button id='mostrarVentana-$id' class='paper-btn opcionbtn eliminar $id'>$boton</button>";
                echo"
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
                // Ventanas de confirmacion
                $this->insertarVentanaModificarEtiqueta($lista);
                $this->insertarVentanaModificarFecha($lista);
                
                // TITULO
                echo"
                    <div class='cabecera-lista'>
                        <h1 class='titulo'>
                            $titulo";
                            // $this->insertarBarraDeProgreso($id);
                // ITEMS
                echo"
                        </h1>
                    </div>";
                if($lista['minimizada']){
                    $clase = 'minimizada';
                    $imagen = 'maximizar.png';
                } else {
                    $clase = '';
                    $imagen = 'minimizar.png';
                }
                echo"
                    <ul class='mainlist m$id $clase'>";
                    $item_controlador->cargarItemsDeLista($id, 0, $rol, $esconder);
                // MENU INFERIOR
                echo "
                    </ul>
                    <div class='bottom-menu'>";
                if($rol!='lector'){
                    echo"<form class='agregar-tarea a$id $clase' action='sql/itemABM.php' method='post'>
                            
                            <div id='agregar-bar'>
                                <input class='input-lista' type='text' name='texto' placeholder='Agregar tarea' required>
                                <button type='submit'>
                                    <img src='imagenes/done.png'>
                                </button>
                            </div>

                            <input type='hidden' name='accion' value='agregar_item'>
                            <input type='hidden' name='id_lista' value='$id'>
                        </form><br>
                        
                        ";
                }
                echo"
                        <div class='retract-btn'>
                            <button class='minimizar-btn $id'><img class='min-btn m$id' src='../TaskIt/imagenes/$imagen'></button>
                        </div>
                    </div>
                </article>";
                if ($rol == 'administrador'){$this->insertarVentanaCompartir($lista,$propietarioId);}
                $this->insertarVentanaEliminarOAbandonar($lista, $rol, $propietarioId);
            }
        }
        echo "
            </div>
        </main>";
    }

    public function insertarIconoMiembros($id, $propietarioId){
        $id_usuario = $_SESSION['id'];
        // Buscar administradores
        $admins = mysqli_query($this->conexion, "SELECT * FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'administrador') ORDER BY usuario;");
        $cantAdmins = mysqli_num_rows($admins);
        // Buscar colaboradores
        $colaboradores = mysqli_query($this->conexion, "SELECT * FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'colaborador') ORDER BY usuario;");
        $cantColaboradores = mysqli_num_rows($colaboradores);
        // Buscar lectores
        $lectores = mysqli_query($this->conexion, "SELECT * FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'lector') ORDER BY usuario;");
        $cantLectores = mysqli_num_rows($lectores);
        // Ventana de miembros
        echo"
        <img class='share-img $id' src='../TaskIt/imagenes/share2.png'>
        <ul id='miembros-$id' class='miembros'>
            <li class='owners'><b>Administradores ($cantAdmins):</b></li>";
            $flag = false;
            while($admin = mysqli_fetch_array($admins)){
                $admin_usuario = $admin['usuario'];
                if($admin['id'] == $propietarioId){
                    $detalle = ' (propietario)';
                } else {
                    $detalle = '';
                }
                $admin_nombre_apellido = $admin['nombre'].' '.$admin['apellido'].$detalle;
                echo "<li class='miembro'>$admin_nombre_apellido</li>";
                if($admin_usuario == $_SESSION['usuario']){
                    $flag = true;
                }
            }
            echo"<li class='colaboradores'><b>Colaboradores ($cantColaboradores):</b></li>";
            while($colaborador = mysqli_fetch_array($colaboradores)){
                $colaborador_usuario = $colaborador['usuario'];
                $colaborador_nombre_apellido = $colaborador['nombre'].' '.$colaborador['apellido'];
                echo "<li class='colaborador'>$colaborador_nombre_apellido</li>";
            }
            echo"<li class='lectores'><b>Lectores ($cantLectores):</b></li>";
            while($lector = mysqli_fetch_array($lectores)){
                $lector_usuario = $lector['usuario'];
                $lector_nombre_apellido = $lector['nombre'].' '.$lector['apellido'];
                echo "<li class='lector'>$lector_nombre_apellido</li>";
            }
        // Agregar miembros solo dueños
        if ($flag){
            echo"
            <button id='editar-miembros' class='editar_miembros $id'>Editar miembros</button>
            </ul>";
        } else {
            echo"</ul>";
        }
    }

    public function insertarRotulo($etiqueta){
        // $etiqueta = strtoupper($etiqueta);
        $id_usuario = $_SESSION['id'];

        // Buscar color de la etiqueta;
        $resultado = mysqli_query($this->conexion, " SELECT * from etiquetas WHERE id_usuario = '$id_usuario' and texto = '$etiqueta'");
        if(mysqli_num_rows($resultado) != 0){
            $resultado = mysqli_fetch_array($resultado);
            $color = strtoupper($resultado['color']);

            echo"<div class='rotulo' style='background-color:$color;'><form action='sql/etiquetaABM.php' method='post'>
            <input type='hidden' name='accion' value='filtrar_etiqueta'>
            <input type='hidden' name='etiqueta' value='$etiqueta'>
            <button class='etiqueta' type='submit'><b>$etiqueta</b></button>
            </form></div>";
        }
        
    }

    public function insertarOpcionEsconderTerminadas($lista){

        $id = $lista['id']; 
        if($lista['esconder_terminadas']){
            $accion = 'mostrar_terminadas';
            $boton = 'Mostrar tareas terminadas';
        } else {
            $accion = 'esconder_terminadas';
            $boton = 'Esconder tareas terminadas';
        }

        echo"
        <form action='sql/listaABM.php' method='post'>
            <input type='hidden' name='accion' value='$accion'>
            <input type='hidden' name='id_lista' value='$id'>
            <button class='paper-btn show' type='submit'>$boton</button>
        </form>";
    }

    public function insertarOpcionModificarEtiqueta($lista){
        $id = $lista['id'];
        if($lista['etiqueta'] == ''){
            $boton = 'Asignar etiqueta';
        } else {
            $boton = 'Modificar etiqueta';
        }
        
        echo"
        <button class='modificar-etiqueta-btn $id paper-btn show'>$boton</button>";
    }

    public function insertarVentanaModificarEtiqueta($lista){
        $id = $lista['id'];
        $etiquetaActual = $lista['etiqueta'];
        echo"
        <div class='ventana-etiqueta l$id static'>
            <form class='modificar-etiqueta' action='sql/listaABM.php' method='post'>
                <label class='static' for='etiqueta'>Seleccionar</label>
                <select class='static' name='etiqueta'>
                    <option value=''>--sin etiqueta--</option>
        ";
        $controladoretiqueta = new EtiquetaController($this->conexion);
        $etiquetas = $controladoretiqueta->obtenerEtiquetas();
        foreach($etiquetas as $etiqueta){
            $texto = $etiqueta['texto'];
            if ($texto == $etiquetaActual){
                echo"
                    <option value='$texto' selected>$texto</option>
                ";
            } else {
                echo"
                    <option value='$texto'>$texto</option>
                ";
            }
        }
        echo"
                </select><br>
                <input type='hidden' name='id_lista' value='$id'>
                <input type='hidden' name='accion' value='modificar_etiqueta'>
                <button class='paper-btn show' type='submit'>Confirmar</button>
            </form>
        </div>";
    }

    public function insertarOpcionModificarFecha($lista){
        $id = $lista['id'];
        if($lista['fecha_finalizacion'] == ''){
            $boton = 'Asignar fecha de finalizacion';
        } else {
            $boton = 'Modificar fecha de finalizacion';
        }
        
        echo"
        <button class='modificar-fecha-btn $id paper-btn show'>$boton</button>";
    }

    public function insertarVentanaModificarFecha($lista){
        $id = $lista['id'];
        $date = date('Y-m-d');
        $etiquetaActual = $lista['etiqueta'];
        echo"
        <div class='ventana-fecha l$id static'>
            <form class='modificar-fecha' action='sql/listaABM.php' method='post'>

                <label class='static' for='fecha'>Seleccionar fecha:</label>
                <input class='static' type='date' name='fecha' min='$date'>
                <label>
                    <input class='static' type='checkbox' name='sinfecha'>
                    Sin fecha
                </label>
                <input type='hidden' name='id_lista' value='$id'>
                <input type='hidden' name='accion' value='modificar_fecha'><br>
                <button class='paper-btn show' type='submit'>Confirmar</button>
            </form>
        </div>";
    }

    public function insertarVentanaCompartir($lista, $propietarioId){
        $id = $lista['id'];
        $titulo = $lista['titulo'];

        // Activar ventana para dar feedback
        if(isset($_SESSION['accion']) && $_SESSION['accion'] == 'compartir' && $_SESSION['id_lista'] == $id){
            $clase = 'ventana_compartir ventana_compartir-activo';
        } else {
            $clase = 'ventana_compartir';
        }
        echo"
        <div id='ventana_compartir-$id' class='$clase'>
            <div class='confirmacion-contenido'>
                <h2>Compartir '$titulo'</h2>
                <ul class='listado-miembros'>";
        
        $id_usuario = $_SESSION['id'];
        // Buscar administradores
        $admins = mysqli_query($this->conexion, "SELECT * FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'administrador') ORDER BY usuario;");
        $cantAdmins = mysqli_num_rows($admins);
        // Buscar colaboradores
        $colaboradores = mysqli_query($this->conexion, "SELECT * FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'colaborador') ORDER BY usuario;");
        $cantColaboradores = mysqli_num_rows($colaboradores);
        // Buscar lectores
        $lectores = mysqli_query($this->conexion, "SELECT * FROM usuarios WHERE usuarios.id IN (SELECT id_usuario from listas_compartidas WHERE listas_compartidas.id_lista = '$id' and rol = 'lector') ORDER BY usuario;");
        $cantLectores = mysqli_num_rows($lectores);
        // Ventana de miembros
        echo"
            <li class='owners'><b>Administradores ($cantAdmins):</b></li>";
            while($admin = mysqli_fetch_array($admins)){
                $admin_usuario = $admin['usuario'];
                if($admin['id'] == $propietarioId){
                    $detalle = ' (propietario)';
                } else {
                    $detalle = '';
                }
                $admin_nombre_apellido = $admin['nombre'].' '.$admin['apellido'].$detalle;
                $id_miembro = $admin['id'];
                echo "<li class='miembro'>$admin_nombre_apellido";
                if($id_miembro != $id_usuario && $admin['id'] != $propietarioId){
                    echo"
                        <a><img class='editar-miembro $id_miembro' src='../TaskIt/imagenes/triangle.png'></a>
                        <div class='opciones-miembro o$id_miembro'>
                            <p>Cambiar a</p>
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='rol' value='colaborador'>
                                <input type='hidden' name='accion' value='modificar_acceso'>
                                <button type='submit'>Colaborador</button>
                            </form>
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='rol' value='lector'>
                                <input type='hidden' name='accion' value='modificar_acceso'>
                                <button type='submit'>Lector</button>
                            </form>
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='accion' value='quitar_acceso'>
                                <button type='submit'>Quitar acceso</button>
                            </form>
                        </div>
                    </li>";
                }
                
            }
            echo"<li class='colaboradores'><b>Colaboradores ($cantColaboradores):</b></li>";
            while($colaborador = mysqli_fetch_array($colaboradores)){
                $colaborador_usuario = $colaborador['usuario'];
                $colaborador_nombre_apellido = $colaborador['nombre'].' '.$colaborador['apellido'];
                $id_miembro = $colaborador['id'];
                echo "<li class='miembro'>$colaborador_nombre_apellido
                        <a><img class='editar-miembro $id_miembro' src='../TaskIt/imagenes/triangle.png'></a>
                        <div class='opciones-miembro o$id_miembro'>
                            <p>Cambiar a</p>";
                if($colaborador['categoria'] == 'suscriptor'){
                    echo"
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='rol' value='administrador'>
                                <input type='hidden' name='accion' value='modificar_acceso'>
                                <button type='submit'>Administrador</button>
                            </form>";
                }
                echo"
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='rol' value='lector'>
                                <input type='hidden' name='accion' value='modificar_acceso'>
                                <button type='submit'>Lector</button>
                            </form>
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='accion' value='quitar_acceso'>
                                <button type='submit'>Quitar acceso</button>
                            </form>
                        </div>
                    </li>";
            }
            echo"<li class='lectores'><b>Lectores ($cantLectores):</b></li>";
            while($lector = mysqli_fetch_array($lectores)){
                $lector_usuario = $lector['usuario'];
                $lector_nombre_apellido = $lector['nombre'].' '.$lector['apellido'];
                $id_miembro = $lector['id'];
                echo "<li class='miembro'>$lector_nombre_apellido
                        <a><img class='editar-miembro $id_miembro' src='../TaskIt/imagenes/triangle.png'></a>
                        <div class='opciones-miembro o$id_miembro'>
                            <p>Cambiar a</p>";
                if($lector['categoria'] == 'suscriptor'){
                    echo"
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='rol' value='administrador'>
                                <input type='hidden' name='accion' value='modificar_acceso'>
                                <button type='submit'>Administrador</button>
                            </form>";
                }
                echo"
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='rol' value='colaborador'>
                                <input type='hidden' name='accion' value='modificar_acceso'>
                                <button type='submit'>Colaborador</button>
                            </form>
                            <form action='sql/listaABM.php' method='post'>
                                <input type='hidden' name='id_miembro' value='$id_miembro'>
                                <input type='hidden' name='id_lista' value='$id'>
                                <input type='hidden' name='accion' value='quitar_acceso'>
                                <button type='submit'>Quitar acceso</button>
                            </form>
                        </div>
                    </li>";
            }

        echo"
                </ul>
                ";
        // Feedback resultado de operacion
        if(isset($_SESSION['resultado'])){
            switch($_SESSION['resultado']){
                case 'compartida':
                    echo"¡Lista compartida con éxito!";
                    break;
                case 'usuario no encontrado':
                    echo"No se ha encontrado un usuario con ese nombre";
                    break;
                case 'relacion existente':
                    echo"El usuario ya posee acceso a esta lista";
                    break;
                case 'no suscriptor':
                    echo"Sólo usuarios pagos pueden ser asignados como administradores";
                    break;
                case 'lista propia':
                    echo"No puedes compartirte tu propia lista";
                    break;
            }
        }
        echo"
                <div class='confirmacion-botones'>
                    <form action='sql/listaABM.php' method='post'>
                        <input type='text' name='usuario' placeholder='Ingrese el usuario' required>
                        <input type='hidden' name='accion' value='compartir_lista'>
                        <input type='hidden' name='id_lista' value='$id'>
                        <label for='rol'>Rol</label>
                        <select name='rol'>
                            <option value='colaborador' selected>Colaborador</option>
                            <option value='administrador'>Administrador (sólo suscriptores)</option>
                            <option value='lector'>Lector</option>
                        </select>
                        <button class='confirmar_compartir' type='submit'>Compartir</button>
                    </form>
                </div>
                <a class='terminar_compartir-btn'><img class='terminar_compartir $id' src='../TaskIt/imagenes/salir.png'></a>
            </div>
            
        </div>
        ";
    }

    public function insertarVentanaEliminarOAbandonar($lista, $rol, $propietarioId){
        $id = $lista['id'];
        $titulo = $lista['titulo'];
        
        if($propietarioId == $_SESSION['id']){
            $boton = 'Eliminar';
            $accion = 'eliminar_lista';
        } else {
            $boton = 'Abandonar';
            $accion = 'abandonar_lista';
        }

        // Ventana de confirmacion escondida
        echo"
        <div id='ventana_confirmacion-$id' class='ventana_confirmacion'>
            <div class='confirmacion-contenido'>
                <h2>¿Desea eliminar la lista '$titulo'?</h2>
                <div class='confirmacion-botones'>
                    <form action='sql/listaABM.php' method='post'>
                        <input type='hidden' name='accion' value='$accion'>
                        <input type='hidden' name='id_lista' value='$id'>
                        <button class='confirmar_eliminar confirmar' type='submit'>$boton</button>
                    </form>
                    <button class='cancelar_eliminar $id'>Cancelar</button>
                </div>
            </div>
        </div>
        ";
    }

    public function insertarBarraDeProgreso($id){
        // Consultar cantidad de items nivel 0 en la lista
        $resultado = mysqli_query($this->conexion, " SELECT * from items WHERE id_lista = '$id' and nivel = 0");
        $cantMax = mysqli_num_rows($resultado);
        // Consultar cantidad de items checkeados nivel 0 en la lista
        $resultado = mysqli_query($this->conexion, " SELECT * from items WHERE id_lista = '$id' and nivel = 0 and checked = true");
        $cantChecked = mysqli_num_rows($resultado);
        echo "<meter value='$cantChecked' min='0' max='$cantMax' class='progress'></meter>";
    }
}
