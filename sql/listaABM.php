<?php
session_start();
include("../connect.php");
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

            if($etiqueta != 'ninguna'){
                $sql = "
                UPDATE listas
                SET etiqueta = '$etiqueta'
                WHERE titulo='$titulo' AND id_usuario = '$id_usuario';";
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
                WHERE titulo='$titulo' AND id_usuario = '$id_usuario';";
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
            // Crear la consulta SQL
            $sql = "DELETE FROM listas WHERE id = '$id_lista';";
            if (mysqli_query($conexion, $sql)) {
                echo "Registro eliminado con éxito.";
            } else {
                echo "Error al eliminar el registro: " . mysqli_error($conexion);
            }
            mysqli_close($conexion);
            header("Location: ../index.php");
            break;

        case "compartir_lista":
            $_SESSION['accion'] = 'compartir';
            $id_lista = $_POST['id_lista'];
            $_SESSION['id_lista'] = $id_lista;
            $usuario = $_SESSION['usuario'];
            $id_admin = $_SESSION['id'];

            // Si no existe, crear relacion del dueño
            $resultado = mysqli_num_rows(mysqli_query($conexion, "SELECT * from listas_compartidas WHERE id_usuario = '$id_admin' and id_lista = '$id_lista'"));
            if ($resultado == 0){
                $sql = "INSERT INTO listas_compartidas (id_usuario, rol, id_lista) VALUES ('$id_admin', 'administrador', '$id_lista')";
                if (mysqli_query($conexion, $sql)) {
                    echo "Lista compatida con éxito.";
                } else {
                    echo "Error al compartir lista: " . mysqli_error($conexion);
                }
            }

            // Buscar usuario a compartir
            $usuario_compartido = $_POST['usuario'];
            $sql = "SELECT * FROM usuarios WHERE usuario='$usuario_compartido' and usuario != '$usuario';";
            $resultado = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($resultado) == 1){
                $resultado = mysqli_fetch_array($resultado);
                $id_usuario_compartido = $resultado['id'];
                // Verificar si ya existe la relacion
                $sql = "SELECT * FROM listas_compartidas WHERE id_usuario = '$id_usuario_compartido' and id_lista = '$id_lista'";
                $resultado = mysqli_query($conexion, $sql);
                if (mysqli_num_rows($resultado) == 0){
                    // Crear relacion
                    $sql = "INSERT INTO listas_compartidas (id_usuario, id_lista) VALUES ('$id_usuario_compartido', '$id_lista')";
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
                    // Mandar notificacion
                    $resultado = mysqli_fetch_array(mysqli_query($conexion, "SELECT titulo from listas WHERE id = '$id_lista'"));
                    $titulo = $resultado['titulo'];
                    $_SESSION['notificar'] = true;
                    $_SESSION['notificacion'] = "$usuario te ha compartido la lista $titulo.";
                    $_SESSION['id_usuario_destino'] = $id_usuario_compartido;
                    header("Location: notificacionABM.php");
                } else {
                    $_SESSION['resultado'] = 'relacion existente';
                }
                
            } else {
                $_SESSION['resultado'] = 'usuario no encontrado';
            }
            mysqli_close($conexion);
            // header("Location: ../index.php");
            break;
        
        default:
            echo "No existe accion con ese nombre";
            break;
    }
} else {
    echo "El formulario no se ha enviado.";
}

?>