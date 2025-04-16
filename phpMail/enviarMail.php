<?php
require_once './phpMail/class.phpmailer.php';
require_once './phpMail/class.smtp.php';

function enviarMail($aquien, $cuerpoMail = '', $asuntoMail = '', $adjunto = '', $desde, $credencial)
{
  if ($cuerpoMail == '') {
    $cuerpoMail = 'No se ingreso un detalle en el cuerpo del mail';
  }
  if ($asuntoMail == '') {
    $asuntoMail = 'Nueva notificación del evento';
  }
  $aquienOculto = '';

  $mail = new PHPMailer();

  $mail->isSMTP();
  $mail->SMTPAuth   = true;
  $mail->SMTPSecure = "tls";
  $mail->Host       = "smtp.gmail.com";
  $mail->Port       = 587;
  $mail->Username = $desde;
  $mail->Password = $credencial;
  $mail->CharSet = 'UTF-8';
  // Crear una nueva instancia PHPMailer
  // Set que se va a enviar el mensaje de
  $mail->setFrom("noresponder@miplataforma.com", "Registración de usuario");
  // Establecer una alternativa dirección de respuesta
  $mail->addReplyTo("soporteusuarios@miplataforma,com", "Reclamo registración de usuarios");
  // Set que se va a enviar al mensaje
  $mail->addAddress($aquien);
  $mail->addBCC($aquienOculto);
  // Establecer la línea de asunto
  $mail->Subject  = $asuntoMail;
  // Lea un cuerpo de mensaje HTML desde un archivo externo, convertir las imágenes referenciadas a incrustado,
  // Convertir HTML en un cuerpo alternativo de texto sin formato básico
  //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
  //    $mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images
  $mail->msgHTML($cuerpoMail); //Create message bodies and embed images
  // Sustituir el cuerpo de texto plano con una creada manualmente
  //$mail->AltBody = 'Se trata de un cuerpo de mensaje de texto sin formato';

  if ($adjunto != '') {
    // Adjuntar un archivo de imagen
    $mail->addAttachment($adjunto);
  }

  $mail->isHTML(true);

  // Enviar el mensaje, comprobar si hay errores
  if ($mail->send()) {
    echo "Se ha enviado satisfactoriamente el mail";
    $devolver = TRUE;
  } else {
    $devolver = "Error en el envio N°: " . $mail->ErrorInfo;
  }
  return $devolver;
}
