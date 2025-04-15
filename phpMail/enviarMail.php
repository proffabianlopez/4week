<?php
require_once 'class.phpmailer.php';
require_once 'class.smtp.php';

function enviarMail($aquien, $cuerpoMail = '', $asuntoMail = '', $adjunto = '', $desde, $credencial) 
{
    // Establecer contenido predeterminado del cuerpo si no se proporciona
    if ($cuerpoMail == '') { 
        $cuerpoMail = 'No se ingreso un detalle en el cuerpo del mail'; 
    }
    // Establecer asunto predeterminado si no se proporciona
    if ($asuntoMail == '') { 
        $asuntoMail = 'Nueva notificación del evento';
    }
    
    $aquienOculto = ''; // Destinatario oculto para BCC

    $mail = new PHPMailer(); // Crear una nueva instancia de PHPMailer

    $mail->isSMTP(); // Usar SMTP
    $mail->SMTPAuth = true; // Habilitar autenticación SMTP
    $mail->SMTPSecure = "tls"; // Usar encriptación TLS
    $mail->Host = "smtp.gmail.com"; // Servidor SMTP
    $mail->Port = 587; // Puerto SMTP
    $mail->Username = $desde; // Nombre de usuario SMTP
    $mail->Password = $credencial; // Contraseña SMTP
    $mail->CharSet = 'UTF-8'; // Establecer codificación de caracteres a UTF-8

    // Validar correo del remitente y credenciales
    if (!filter_var($desde, FILTER_VALIDATE_EMAIL)) {
        return "Error: El correo del remitente no es válido.";
    }
    if (empty($credencial)) {
        return "Error: La credencial SMTP no puede estar vacía.";
    }

    $mail->setFrom("noresponder@miplataforma.com", "Registración de usuario"); // Correo y nombre del remitente
    $mail->addReplyTo("soporteusuarios@miplataforma.com", "Reclamo registración de usuarios"); // Correo y nombre para responder
    $mail->addAddress($aquien); // Agregar correo del destinatario
    $mail->addBCC($aquienOculto); // Agregar destinatario oculto (BCC)
    $mail->Subject = $asuntoMail; // Establecer el asunto del correo

    $mail->msgHTML($cuerpoMail); // Establecer el contenido del cuerpo del correo como HTML

    if ($adjunto != '') { 
        $mail->addAttachment($adjunto); // Adjuntar un archivo si se proporciona
    }

    $mail->isHTML(true); // Especificar que el correo está en formato HTML

    // Enviar el correo y verificar si hay errores
    if ($mail->send()) { 
        echo "Se ha enviado satisfactoriamente el mail"; // Mensaje de éxito
        $devolver = true; // Retornar true en caso de éxito
    } else { 
        $devolver = "Error en el envío: " . $mail->ErrorInfo; // Mejorar formato del mensaje de error
    }
    return $devolver; // Retornar el resultado
}
?>

