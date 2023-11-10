<?php
session_start();
require("../connect.php");
include("../controlador/NotificacionController.php");
$controladornotificacion = new NotificacionController($conexion);

// Verificar si se ha enviado el formulario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $accion = $_POST["accion"];
    
    switch($accion){
        case "crear_lista":
            $titulo = $_POST["titulo"];

            $id_usuario = $_SESSION['id'];
            $etiqueta = $_POST["etiqueta"];
            $fecha = $_POST['fecha'];

            // Crear la consulta SQL
            $sql = "INSERT INTO listas (titulo, id_usuario) VALUES ('$titulo', '$id_usuario')";

            // Ejecutar la consulta
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }

            $id_lista = mysqli_insert_id($conexion);

            if($etiqueta != 'ninguna'){
                $sql = "
                UPDATE listas
                SET etiqueta = '$etiqueta'
                WHERE id='$id_lista';";
                if (mysqli_query($conexion, $sql)) {
                    echo "Etiqueta actualizada con éxito.";
                } else {
                    echo "Error al actualizar la etiqueta: " . mysqli_error($conexion);
                }
            }

            if ($fecha != ''){
                $sql = "
                UPDATE listas
                SET fecha_finalizacion = '$fecha'
                WHERE id='$id_lista';";
                if (mysqli_query($conexion, $sql)) {
                    echo "Etiqueta actualizada con éxito.";
                } else {
                    echo "Error al actualizar la etiqueta: " . mysqli_error($conexion);
                }
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);
            header("Location: ../index.php");
            break;
        
        case "eliminar_lista":
            $id_lista = $_POST['id_lista'];
            $id_usuario = $_SESSION['id'];
            $lista = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM listas WHERE id='$id_lista'"));
            $titulo = $lista['titulo'];
            // Buscar miembros
            $sql = "SELECT * FROM usuarios WHERE id IN (
                SELECT id_usuario FROM listas_compartidas
                WHERE id_lista = '$id_lista' and id_usuario != '$id_usuario'
            )";
            $notificaciones = array();
            $miembros = mysqli_query($conexion, $sql);
            while($miembro = mysqli_fetch_array($miembros)){
                array_push($notificaciones, array(
                    'destinatario'=> $miembro['id'],
                    'mensaje'=> "La lista $titulo ha sido eliminada."
                ));
            }

            // Eliminar registro
            $sql = "DELETE FROM listas WHERE id = '$id_lista';";
            if (mysqli_query($conexion, $sql)) {
                echo "Registro eliminado con éxito.";
            } else {
                echo "Error al eliminar el registro: " . mysqli_error($conexion);
            }

            // Notificar miembros si es compartida
            if($lista['acceso'] == 'compartido'){
                $_SESSION['notificar'] = true;
                $_SESSION['notificaciones'] = $notificaciones;
                header("Location: notificacionABM.php");
            } else {
                mysqli_close($conexion);
                header("Location: ../index.php");
            }
            break;

        case "compartir_lista":
            $_SESSION['accion'] = 'compartir';
            $id_lista = $_POST['id_lista'];
            $_SESSION['id_lista'] = $id_lista;
            $usuario = $_SESSION['usuario'];
            $id_admin = $_SESSION['id'];
            $rol = $_POST['rol'];

            // Buscar usuario a compartir
            $usuario_compartido = $_POST['usuario'];
            $sql = "SELECT * FROM usuarios WHERE usuario='$usuario_compartido' and usuario != '$usuario';";
            $resultado = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($resultado) == 1){
                
                $resultado = mysqli_fetch_array($resultado);
                $nombre_usuario_compartido = $resultado['usuario'];
                $id_usuario_compartido = $resultado['id'];
                // Verificar si ya existe la relacion
                $sql = "SELECT * FROM listas_compartidas WHERE id_usuario = '$id_usuario_compartido' and id_lista = '$id_lista'";
                $resultado = mysqli_query($conexion, $sql);
                if (mysqli_num_rows($resultado) == 0){
                    // Si no existe, crear relacion del dueño
                    $resultado = mysqli_num_rows(mysqli_query($conexion, "SELECT * from listas_compartidas WHERE id_usuario = '$id_admin' and id_lista = '$id_lista'"));
                    if ($resultado == 0){
                        $sql = "INSERT INTO listas_compartidas (id_usuario, rol, id_lista) VALUES ('$id_admin', 'administrador', '$id_lista')";
                        if (mysqli_query($conexion, $sql)) {
                            echo "Lista compartida con éxito.";
                        } else {
                            echo "Error al compartir lista: " . mysqli_error($conexion);
                        }
                    }
                    // Crear relacion
                    $sql = "INSERT INTO listas_compartidas (id_usuario, rol, id_lista) VALUES ('$id_usuario_compartido', '$rol', '$id_lista')";
                    if (mysqli_query($conexion, $sql)) {
                        echo "Lista compartida con éxito.";
                    } else {
                        echo "Error al compartir lista: " . mysqli_error($conexion);
                    }
                    $_SESSION['resultado'] = 'compartida';
                    // Modificar tipo de acceso
                    $sql = "UPDATE listas SET acceso = 'compartido' WHERE id='$id_lista';";
                    if (mysqli_query($conexion, $sql)) {
                        echo "Tipo de acceso actualizado con éxito.";
                    } else {
                        echo "Error al actualizar tipo de acceso: " . mysqli_error($conexion);
                    }
                    // Notificar usuario agregado
                    $resultado = mysqli_fetch_array(mysqli_query($conexion, "SELECT titulo from listas WHERE id = '$id_lista'"));
                    $titulo = $resultado['titulo'];
                    $notificaciones = array(
                        array('destinatario' => $id_usuario_compartido, 'mensaje'=> "$usuario te ha unido a la lista $titulo como $rol."
                    ));
                    // Notificar miembros de la lista excepto admin que comparte y usuario compartido
                    $sql = "SELECT id FROM usuarios WHERE id IN (
                        SELECT id_usuario FROM listas_compartidas
                        WHERE id_lista = '$id_lista' and id_usuario != '$id_usuario_compartido' and id_usuario != '$id_admin'
                    )";
                    $resultado = mysqli_query($conexion, $sql);
                    while($miembro = mysqli_fetch_array($resultado)){
                        array_push($notificaciones, array(
                            'destinatario'=> $miembro['id'],
                            'mensaje'=> "$nombre_usuario_compartido se ha unido a la lista $titulo como $rol."
                        ));
                    }

                    // Enviar notificaciones
                    $controladornotificacion->enviarNotificaciones($notificaciones);
                } else {
                    $_SESSION['resultado'] = 'relacion existente';
                    mysqli_close($conexion);
                    header("Location: ../index.php");
                }
            } else {
                $_SESSION['resultado'] = 'usuario no encontrado';
                mysqli_close($conexion);
                header("Location: ../index.php");
            }
            
            break;
        
        case 'abandonar_lista':
            $id_lista = $_POST['id_lista'];
            $id_usuario = $_SESSION['id'];
            $sql = "DELETE FROM listas_compartidas WHERE id_lista = '$id_lista' and id_usuario = '$id_usuario';";
            if (mysqli_query($conexion, $sql)) {
                echo "Relación eliminada con éxito.";
            } else {
                echo "Error al eliminar la relación: " . mysqli_error($conexion);
            }

            // Notificar administradores
            $resultado = mysqli_fetch_array(mysqli_query($conexion, "SELECT titulo from listas WHERE id = '$id_lista'"));
            $titulo = $resultado['titulo'];
            $usuario = $_SESSION['usuario'];
            $sql = "SELECT * FROM usuarios WHERE id IN (SELECT listas_compartidas.id_usuario FROM listas_compartidas WHERE listas_compartidas.id_lista = '$id_lista' and listas_compartidas.rol = 'administrador')";
            $resultado = mysqli_query($conexion, $sql);

            $notificaciones = array();
            while($admin = mysqli_fetch_array($resultado)){
                array_push($notificaciones, array(
                    'destinatario'=> $admin['id'],
                    'mensaje'=> "$usuario ha abandonado la lista $titulo."
                ));
            }

            // Enviar notificaciones
            $controladornotificacion->enviarNotificaciones($notificaciones);

            mysqli_close($conexion);
            break;

        case 'esconder_terminadas':

            $id_lista = $_POST['id_lista'];
            $sql = "UPDATE listas SET esconder_terminadas=true WHERE id='$id_lista'";
            if (mysqli_query($conexion, $sql)) {
                echo "Lista actualizada con éxito.";
            } else {
                echo "Error al actualizar la lista: " . mysqli_error($conexion);
            }

            header("Location: ../index.php");
            mysqli_close($conexion);
            break;

        case 'mostrar_terminadas':

            $id_lista = $_POST['id_lista'];
            $sql = "UPDATE listas SET esconder_terminadas=false WHERE id='$id_lista'";
            if (mysqli_query($conexion, $sql)) {
                echo "Lista actualizada con éxito.";
            } else {
                echo "Error al actualizar la lista: " . mysqli_error($conexion);
            }

            header("Location: ../index.php");
            mysqli_close($conexion);
            break;
        
        case "modificar_etiqueta":
            $id_lista = $_POST['id_lista'];
            $etiqueta = $_POST['etiqueta'];

            $sql = "UPDATE listas SET etiqueta = '$etiqueta' WHERE id='$id_lista'";
            if (mysqli_query($conexion, $sql)) {
                echo "Etiqueta actualizada con éxito.";
            } else {
                echo "Error al actualizar la etiqueta: " . mysqli_error($conexion);
            }

            header("Location: ../index.php");
            mysqli_close($conexion);
            break;

        case "minimizar":
            $id_lista = $_POST['id_lista'];
            $minimizada = $_POST['minimizada'];

            $sql = "UPDATE listas SET minimizada = '$minimizada' WHERE id='$id_lista'";
            if (mysqli_query($conexion, $sql)) {
                echo "Lista minimizada con éxito.";
            } else {
                echo "Error al actualizar la etiqueta: " . mysqli_error($conexion);
            }

            header("Location: ../index.php");
            mysqli_close($conexion);
            break;

        case 'modificar_acceso':
            $id_lista = $_POST['id_lista'];
            $id_miembro = $_POST['id_miembro'];
            $rol = $_POST['rol'];
            $_SESSION['accion'] = 'compartir';
            $_SESSION['id_lista'] = $id_lista;
            $usuario = $_SESSION['usuario'];
            $id_admin = $_SESSION['id'];

            $sql = "UPDATE listas_compartidas SET rol = '$rol' WHERE id_lista='$id_lista' and id_usuario='$id_miembro'";
            if (mysqli_query($conexion, $sql)) {
                echo "Miembro actualizado con éxito.";
            } else {
                echo "Error al actualizar el miembro: " . mysqli_error($conexion);
            }

            // Notificar usuario modificado
            $resultado = mysqli_fetch_array(mysqli_query($conexion, "SELECT * from listas WHERE id = '$id_lista'"));
            $titulo = $resultado['titulo'];
            $notificaciones = array(
                array('destinatario' => $id_miembro, 'mensaje'=> "$usuario te ha convertido en $rol de la lista $titulo."
            ));

            // Buscar usuario a compartir
            $sql = "SELECT * FROM usuarios WHERE id='$id_miembro'";
            $resultado = mysqli_fetch_array(mysqli_query($conexion, $sql));
            $nombre_usuario_modificado = $resultado['usuario'];

            // Notificar miembros de la lista excepto admin que comparte y usuario compartido
            $sql = "SELECT id FROM usuarios WHERE id IN (
                SELECT id_usuario FROM listas_compartidas
                WHERE id_lista = '$id_lista' and id_usuario != '$id_miembro' and id_usuario != '$id_admin'
            )";
            $resultado = mysqli_query($conexion, $sql);
            while($miembro = mysqli_fetch_array($resultado)){
                array_push($notificaciones, array(
                    'destinatario'=> $miembro['id'],
                    'mensaje'=> "$nombre_usuario_modificado se ha convertido en $rol de la lista $titulo."
                ));
            }

            // Enviar notificaciones
            $controladornotificacion->enviarNotificaciones($notificaciones);

            mysqli_close($conexion);
            break;
        
        case 'quitar_acceso':
            $id_lista = $_POST['id_lista'];
            $id_miembro = $_POST['id_miembro'];
            $_SESSION['accion'] = 'compartir';
            $_SESSION['id_lista'] = $id_lista;

            $sql = "DELETE FROM listas_compartidas WHERE id_lista = '$id_lista' and id_usuario = '$id_miembro';";
            if (mysqli_query($conexion, $sql)) {
                echo "Relación eliminada con éxito.";
            } else {
                echo "Error al eliminar la relación: " . mysqli_error($conexion);
            }

            header("Location: ../index.php");
            mysqli_close($conexion);
            break;


        default:
            echo "No existe la accion $accion";
            break;
    }
} else {
    echo "El formulario no se ha enviado.";
}

?>