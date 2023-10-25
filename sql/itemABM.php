<?php
session_start();
include("../connect.php");
// Verificar si se ha enviado el formulario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recuperar los datos del formulario
    $accion = $_POST["accion"];
    
    switch($accion){

        case "agregar_item":
            $id_lista = $_POST["id_lista"];
            $texto = $_POST["texto"];
            
            // Crear la consulta SQL
            $sql = "INSERT INTO items (texto, id_lista, nivel, tipo) VALUES ('$texto', '$id_lista', 0, 'item')";

            // Ejecutar la consulta
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($conexion);
            header("Location: ../index.php");
            break;
        
        case "agregar_subitem":
            $id_sublista = $_POST['id_sublista'];
            $nivel = $_POST['nivel'];
            $texto = $_POST["texto"];

            // Cambiar el tipo del item padre a sublista
            $sql = "UPDATE items SET tipo='sublista' WHERE id = '$id_sublista' and nivel = '$nivel'";
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }

            // Crear nuevo item tipo subitem
            $proximo_nivel = $nivel + 1;
            $sql = "INSERT INTO items (texto, id_lista, nivel, tipo) VALUES ('$texto', '$id_sublista', '$proximo_nivel', 'subitem')";
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }
            mysqli_close($conexion);
            header("Location: ../index.php");
            break;

        case "checkear":
            $id_item = $_POST['id_item'];
            $nivel = $_POST['nivel'];
            $id_lista = $_POST['id_lista'];

            

            // VERIFICAR DEPENDENCIAS DE OTRAS TAREAS
            // Hacia arriba
            checkearHaciaArriba($id_item, $nivel, $id_lista, $conexion);

            
            



            mysqli_close($conexion);
            header("Location: ../index.php");
            break;
        
        case "descheckear":
            $id_item = $_POST['id_item'];
            $nivel = $_POST['nivel'];

            $sql = "UPDATE items SET checked=false WHERE id = '$id_item' and nivel = '$nivel'";
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }
            mysqli_close($conexion);
            header("Location: ../index.php");
            break;

    }
    
} else {
    echo "El formulario no se ha enviado.";
}


function checkearHaciaArriba($id_item, $nivel, $id_lista, $conexion){
    $sql = "UPDATE items SET checked=true WHERE id = '$id_item' and nivel = '$nivel'";
    if (mysqli_query($conexion, $sql)) {
        echo "Registro insertado con éxito.";
    } else {
        echo "Error al insertar el registro: " . mysqli_error($conexion);
    }

    if ($nivel > 0){

        $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_lista = '$id_lista'";
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }
        $cantidad_por_nivel = mysqli_num_rows($resultado);

        $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_lista = '$id_lista' and checked = true";
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }
        $cantidad_checkeados = mysqli_num_rows($resultado);

        if($cantidad_por_nivel == $cantidad_checkeados){// Si todos los items del nivel están checkeados
            // Checkear nivel superior
            $nivel = $nivel - 1;
            $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id = '$id_lista'";
            $resultado = mysqli_query($conexion, $sql);
            if ($resultado) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }
            $item_padre = mysqli_fetch_array($resultado);
            checkearHaciaArriba($item_padre['id'], $item_padre['nivel'], $item_padre['id_lista'], $conexion);
        }

    }
}

?>