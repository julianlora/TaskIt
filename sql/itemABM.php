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
            $id_item_padre = $_POST['id_sublista'];
            $nivel = $_POST['nivel'];
            $texto = $_POST["texto"];

            // Cambiar el tipo del item padre a sublista
            $sql = "UPDATE items SET tipo='sublista' WHERE id = '$id_item_padre' and nivel = '$nivel'";
            if (mysqli_query($conexion, $sql)) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }

            // Buscar la lista a la que pertenece
            $sql = "SELECT * FROM items WHERE id = '$id_item_padre' and nivel = '$nivel'";
            $resultado = mysqli_query($conexion, $sql);
            if ($resultado) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }
            $resultado = mysqli_fetch_array($resultado);
            $id_lista = $resultado['id_lista'];

            // Crear nuevo item tipo subitem
            $proximo_nivel = $nivel + 1;
            $sql = "INSERT INTO items (texto, id_lista, nivel, tipo, id_item_padre) VALUES ('$texto', '$id_lista', '$proximo_nivel', 'subitem', '$id_item_padre')";
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

            // Buscar datos
            $sql = "SELECT * FROM items WHERE id = '$id_item' and nivel = '$nivel' and id_lista = '$id_lista'";
            $resultado = mysqli_query($conexion, $sql);
            if ($resultado) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }
            $item = mysqli_fetch_array($resultado);
            $id_item_padre = $item['id_item_padre'];
            
            // VERIFICAR DEPENDENCIAS DE OTRAS TAREAS
            checkearHaciaArriba($id_item, $nivel, $id_lista, $id_item_padre, $conexion);
            checkearHaciaAbajo($id_item, $nivel, $id_lista, $id_item_padre, $conexion);

            mysqli_close($conexion);
            header("Location: ../index.php");
            break;
        
        case "descheckear":
            $id_item = $_POST['id_item'];
            $nivel = $_POST['nivel'];
            $id_lista = $_POST['id_lista'];

            // Buscar datos
            $sql = "SELECT * FROM items WHERE id = '$id_item' and nivel = '$nivel' and id_lista = '$id_lista'";
            $resultado = mysqli_query($conexion, $sql);
            if ($resultado) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }
            $item = mysqli_fetch_array($resultado);
            $id_item_padre = $item['id_item_padre'];

            // VERIFICAR DEPENDENCIAS DE OTRAS TAREAS
            descheckearHaciaArriba($id_item, $nivel, $id_lista, $id_item_padre, $conexion);
            descheckearHaciaAbajo($id_item, $nivel, $id_lista, $id_item_padre, $conexion);

            mysqli_close($conexion);
            header("Location: ../index.php");
            break;

    }
    
} else {
    echo "El formulario no se ha enviado.";
}


function checkearHaciaArriba($id_item, $nivel, $id_lista, $id_item_padre, $conexion){
    // Checkear item
    $sql = "UPDATE items SET checked=true WHERE id = '$id_item' and nivel = '$nivel' and id_lista='$id_lista'";
    if (mysqli_query($conexion, $sql)) {
        echo "Registro insertado con éxito.";
    } else {
        echo "Error al insertar el registro: " . mysqli_error($conexion);
    }

    // Comprobar si tiene niveles superiores
    if ($nivel > 0){

        // Buscar todos los items del nivel actual
        $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_lista = '$id_lista' and id_item_padre = '$id_item_padre'";
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }
        $cantidad_por_nivel = mysqli_num_rows($resultado);

        // Buscar todos los items del nivel actual que estén checkeados
        $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_lista = '$id_lista' and id_item_padre = '$id_item_padre' and checked = true";
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }
        $cantidad_checkeados = mysqli_num_rows($resultado);

        // Comprobar si todos los items del nivel actual están checkeados
        if($cantidad_por_nivel == $cantidad_checkeados){

            // Buscar item padre
            $nivel = $nivel - 1;
            $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_lista = '$id_lista' and id = '$id_item_padre'";
            $resultado = mysqli_query($conexion, $sql);
            if ($resultado) {
                echo "Registro insertado con éxito.";
            } else {
                echo "Error al insertar el registro: " . mysqli_error($conexion);
            }

            // Checkear nivel superior
            $item_padre = mysqli_fetch_array($resultado);
            checkearHaciaArriba($item_padre['id'], $item_padre['nivel'], $item_padre['id_lista'], $item_padre['id_item_padre'], $conexion);
        }

    }
}

function checkearHaciaAbajo($id_item, $nivel, $id_lista, $id_item_padre, $conexion){
    // Buscar datos de item checkeado
    $sql = "SELECT * FROM items WHERE id = '$id_item' and nivel = '$nivel' and id_lista = '$id_lista'";
    $resultado = mysqli_query($conexion, $sql);
    if ($resultado) {
        echo "Registro insertado con éxito.";
    } else {
        echo "Error al insertar el registro: " . mysqli_error($conexion);
    }
    $item = mysqli_fetch_array($resultado);
    $tipo = $item['tipo'];

    // Comprobar si tiene subitems
    if ($tipo == 'sublista'){

        // Buscar subitems
        $nivel = $nivel + 1;
        $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_lista = '$id_lista' and id_item_padre = '$id_item'";
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }

        // Checkear subitems
        while($item = mysqli_fetch_array($resultado)){
            // Solo si no están ya checkeados
            if($item['checked'] == false){
                $id_item = $item['id'];
                $id_item_padre = $item['id_item_padre'];
                $nivel = $item['nivel'];
                // Checkear subitem
                $sql = "UPDATE items SET checked=true WHERE id = '$id_item' and nivel = '$nivel' and id_lista = '$id_lista'";
                if (mysqli_query($conexion, $sql)) {
                    echo "Registro insertado con éxito.";
                } else {
                    echo "Error al insertar el registro: " . mysqli_error($conexion);
                }
                checkearHaciaAbajo($id_item, $nivel, $id_lista, $id_item_padre, $conexion);
            }
            
        }
    }
}

function descheckearHaciaArriba($id_item, $nivel, $id_lista, $id_item_padre, $conexion){
    // Descheckear item
    $sql = "UPDATE items SET checked=false WHERE id = '$id_item' and nivel = '$nivel' and id_lista = $id_lista";
    if (mysqli_query($conexion, $sql)) {
        echo "Registro insertado con éxito.";
    } else {
        echo "Error al insertar el registro: " . mysqli_error($conexion);
    }

    // Comprobar si tiene niveles superiores
    if ($nivel > 0){

        // Buscar item padre
        $nivel = $nivel - 1;
        $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_lista = '$id_lista' and id = '$id_item_padre'";
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }

        // Descheckear nivel superior
        $item_padre = mysqli_fetch_array($resultado);
        descheckearHaciaArriba($item_padre['id'], $item_padre['nivel'], $item_padre['id_lista'], $item_padre['id_item_padre'], $conexion);
        

    }
}

function descheckearHaciaAbajo($id_item, $nivel, $id_lista, $id_item_padre, $conexion){
    // Buscar datos de item descheckeado
    $sql = "SELECT * FROM items WHERE id = '$id_item' and nivel = '$nivel' and id_lista = '$id_lista'";
    $resultado = mysqli_query($conexion, $sql);
    if ($resultado) {
        echo "Registro insertado con éxito.";
    } else {
        echo "Error al insertar el registro: " . mysqli_error($conexion);
    }
    $item = mysqli_fetch_array($resultado);
    $tipo = $item['tipo'];

    // Comprobar si tiene subitems
    if ($tipo == 'sublista'){

        // Buscar subitems
        $nivel = $nivel + 1;
        $sql = "SELECT * FROM items WHERE nivel = '$nivel' and id_item_padre = '$id_item' and id_lista = '$id_lista'";
        $resultado = mysqli_query($conexion, $sql);
        if ($resultado) {
            echo "Registro insertado con éxito.";
        } else {
            echo "Error al insertar el registro: " . mysqli_error($conexion);
        }

        // Descheckear subitems
        while($item = mysqli_fetch_array($resultado)){
            // Solo si están checkeados
            if($item['checked'] == true){
                $id_item = $item['id'];
                $id_lista = $item['id_lista'];
                $nivel = $item['nivel'];
                $id_item_padre = $item['id_item_padre'];
                // Descheckear subitem
                $sql = "UPDATE items SET checked=false WHERE id = '$id_item' and nivel = '$nivel' and id_lista='$id_lista'";
                if (mysqli_query($conexion, $sql)) {
                    echo "Registro insertado con éxito.";
                } else {
                    echo "Error al insertar el registro: " . mysqli_error($conexion);
                }
                descheckearHaciaAbajo($id_item, $nivel, $id_lista, $id_item_padre, $conexion);
            }
            
        }
    }
}

?>