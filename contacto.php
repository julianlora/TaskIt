<?php 

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoge los datos del formulario
    $nombre = $_POST["nombre"];
    $mensaje = $_POST["mensaje"];
    $telefono = $_POST["telefono"];
    $asunto = $_POST["asunto"];
    $correo = $_POST["correo"];

    // Dirección de correo electrónico a la que se enviará el mensaje
    $destinatario = "equipo-taskit@outlook.com";

    // Asunto del correo
    $asunto = "Nuevo mensaje desde el formulario";

    // Construye el cuerpo del mensaje
    $cuerpoMensaje = "Nombre: $nombre\n";
    $cuerpoMensaje .= "Asunto: $asunto\n";
    $cuerpoMensaje .= "Correo: $correo\n";
    $cuerpoMensaje .= "Numero de telefono: $telefono\n";
    $cuerpoMensaje .= "Mensaje:\n$mensaje";

    // Crea una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Outlook
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'equipo-taskit@outlook.com';
        $mail->Password = 'laboratorio23';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configuración adicional
        $mail->setFrom('equipo-taskit@outlook.com', 'TaskIt');
        $mail->addAddress('equipo-taskit@outlook.com', 'Destinatario');

        // Contenido del correo
        $mail->isHTML(false);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpoMensaje;

        // Envía el correo
        $mail->send();
        echo "Correo enviado con éxito";

        // Mandar mail de respuesta
        $mail->clearAllRecipients();
        $mail->addAddress($correo, $nombre);

        // Contenido del correo
        $mail->isHTML(false);
        $mail->Subject = 'Contacto TaskIt';
        $mail->Body = 'Gracias por contactarte con el equipo de TaskIt! Te responderemos en breve.';

        // Envía el correo
        $mail->send();
        echo "Correo enviado con éxito";
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

    header("Location: home/index.php?resultado=exito#contacto");

}
?>