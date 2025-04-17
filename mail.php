<?php
require_once './phpMail/enviarMail.php';
$configuracion='config.dat';
$user=$_POST ["visitor_email"];
$code_verif=cripto_6(8);
$contenido = '
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro al Evento</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <div style="max-width: 640px; margin: 40px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 40px;">
        <h1 style="color: #2c3e50; font-size: 24px; text-align: center;">¡Registro exitoso al evento masivo!</h1>
        <div style="color: #333; font-size: 16px; line-height: 1.6;">
            <p>Le damos la bienvenida, <strong style="color: #2980b9;">'. $user .'</strong>.</p>
            <p>Gracias por registrarse en nuestro evento. A continuación encontrará su código de verificación, el cual necesitará para acceder a actividades exclusivas durante la jornada:</p>
            <div style="margin: 30px 0; text-align: center;">
                <span style="display: inline-block; background-color: #ecf0f1; padding: 15px 25px; border-radius: 8px; font-size: 20px; font-weight: bold; color: #27ae60;">'. $code_verif .'</span>
            </div>
            <p style="text-align: center;"><strong style="color: #34495e;">¡Muchas gracias por su asistencia!</strong></p>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="https://evento2025.com/confirmar" style="background-color: #3498db; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 6px; font-size: 16px; display: inline-block;">
                    Confirmar mi participación
                </a>
            </div>
        </div>
        <hr style="margin: 40px 0; border: none; border-top: 1px solid #ddd;">
        <p style="font-size: 12px; color: #999; text-align: center;">Este correo fue enviado automáticamente. Por favor, no responda a este mensaje.</p>
    </div>
</body>
</html>';

try{
  if (!file_exists($configuracion)) 
  {
    throw new Exception("No se pudo abrir el archivo");
  }
  else{
    $archivo=fopen($configuracion,'r') or die("no puedo abrir archivo de datos");
    while(!feof($archivo)) 
    {
        $linea=fgets($archivo);
        $datos=explode("|",$linea);
        $desde=$datos[0];
        $credencial=$datos[1];
    }      
    
    enviarMail($user,$contenido,$asuntoMail, $adjunto='',$desde,$credencial) ;
  }

}catch (Exception $e) {
    echo "Error (File: ".$e->getFile().", line ".
          $e->getLine()."): ".$e->getMessage();
          echo "<script> window.open('https://www.ole.com.ar','_blank'); </script>";
}finally{
  echo "<script> window.open('login.html','_blank'); </script>";

}


function cripto_1($len)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
  if ($len >0 && $len<=36)
  {
    return substr(str_shuffle($permitted_chars), 0, $len);
  }
  else
  {
    return substr(str_shuffle($permitted_chars), 0, 8);
  }
}

function cripto_2($len)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  if ($len >0 && $len<=62)
  {
    return substr(str_shuffle($permitted_chars), 0, $len);
  }
  else
  {
    return substr(str_shuffle($permitted_chars), 0, 8);
  }
}

 
function cripto_3($len) {
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $input_length = strlen($permitted_chars);
  $random_string = '';
  for($i = 0; $i < $len; $i++) {
        $random_character = substr(str_shuffle($permitted_chars), 0, 1);
        $random_string .= $random_character;
  }
 
  return $random_string;
}

function cripto_4($len) {
  return bin2hex(random_bytes($len));
}
function cripto_5($len) {
  return substr(md5(time()), 0, $len);
}
function cripto_6($len) {
  return substr(sha1(time()), 0, $len);
}
?>