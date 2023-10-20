<?php
include("../connect.php");
// Verificar si se ha enviado el formulario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recuperar los datos del formulario
    $texto = $_POST["texto"];
    $id_lista = $_POST["id_lista"];

    // Crear la consulta SQL
    $sql = "INSERT INTO items (texto, id_lista) VALUES ('$texto', '$id_lista')";

    // Ejecutar la consulta
    if (mysqli_query($conexion, $sql)) {
        echo "Registro insertado con éxito.";
    } else {
        echo "Error al insertar el registro: " . mysqli_error($conexion);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
    header("Location: ../index.php");
} else {
    echo "El formulario no se ha enviado.";
}

?>