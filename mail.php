<?php
require_once './phpMail/enviarMail.php';
require_once './helpers.php';

if (isset($_POST)) {
  $configuracion = './archivos/config.dat';
  $archivo_de_usuarios = './archivos/Usuarios.dat';
  $username = (isset($_POST['visitor_name'])) ? $_POST['visitor_name'] : false;
  $user = (isset($_POST['visitor_email'])) ? $_POST['visitor_email'] : false;
  $password = (isset($_POST['visitor_password'])) ? $_POST['visitor_password'] : false;
  $errores = 0;
  $code_verif = cripto_6(8);
  $fecha_actual = date('Y-m-d H:i:s');
  $status = 0;
  $intentos = 0;

  // Validaciones
  if (!$username || empty(trim($username))) {
    $errores++;
  } elseif (!preg_match('/^[A-Z\sa-z]{3,20}$/', $username)) {
    $errores++;
  }

  if (!$user || empty(trim($user))) {
    $errores++;
  } elseif (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
    $errores++;
  }

  if (!$password || empty(trim($password))) {
    $errores++;
  }

  if (file_exists($archivo_de_usuarios) && $user) {
    $correo_existe = false;
    $archivo = fopen($archivo_de_usuarios, 'r');

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_usuario = explode("|", $linea);
        if (count($datos_usuario) >= 2 && trim($datos_usuario[1]) === $user) {
          $correo_existe = true;
          break;
        }
      }
    }
    fclose($archivo);

    if ($correo_existe) {
      $errores++;
      registrar_log($user, 'REG_FAIL');
    }
  }

  $contenido = '
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro al Evento</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <div style="max-width: 640px; margin: 40px auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); padding: 40px;">
        <h1 style="color: #2c3e50; font-size: 24px; text-align: center;">¡Registro exitoso al evento masivo!</h1>
        <div style="color: #333; font-size: 16px; line-height: 1.6;">
            <p>Le damos la bienvenida, <strong style="color: #2980b9;">' . $user . '</strong>.</p>
            <p>Gracias por registrarse en nuestro evento. A continuación encontrará su código de verificación, el cual necesitará para activar su cuenta:</p>
            <div style="margin: 30px 0; text-align: center;">
                <span style="display: inline-block; background-color: #ecf0f1; padding: 15px 25px; border-radius: 8px; font-size: 20px; font-weight: bold; color: #27ae60;">' . $code_verif . '</span>
            </div>
            <p style="text-align: center;"><strong style="color: #34495e;">¡Muchas gracias por su asistencia!</strong></p>
        </div>
        <hr style="margin: 40px 0; border: none; border-top: 1px solid #ddd;">
        <p style="font-size: 12px; color: #999; text-align: center;">Este correo fue enviado automáticamente. Por favor, no responda a este mensaje.</p>
    </div>
</body>
</html>';

  try {
    if (!file_exists($configuracion)) {
      throw new Exception("No se pudo abrir el archivo de configuración");
    } else {
      if ($errores === 0) {
        $archivo = fopen($configuracion, 'r') or die("no puedo abrir archivo de datos");
        $desde = '';
        $credencial = '';

        while (!feof($archivo)) {
          $linea = fgets($archivo);
          if (!empty(trim($linea))) {
            $datos = explode("|", $linea);
            if (count($datos) >= 2) {
              $desde = trim($datos[0]);
              $credencial = trim($datos[1]);
            }
          }
        }
        fclose($archivo);

        // Encriptar la contraseña antes de guardar
        $password_encriptado = password_hash($password, PASSWORD_BCRYPT);

        // Guardar datos del usuario en el archivo Usuarios.dat
        $datos_usuario = $username . "|" . $user . "|" . $password_encriptado . "|" . $code_verif . "|" . "" . "|" . $fecha_actual . "|" . $status . "|" . $intentos . PHP_EOL;
        $archivo_usuarios = fopen($archivo_de_usuarios, 'a+') or die("No se pudo abrir el archivo de usuarios");
        fwrite($archivo_usuarios, $datos_usuario);
        fclose($archivo_usuarios);

        // Registrar el usuario en log
        registrar_log($user, 'REG');
        enviarMail($user, $contenido, isset($asuntoMail) ? $asuntoMail : 'Activación de cuenta', isset($adjunto) ? $adjunto : '', $desde, $credencial);
      } else {
        // Registrar error en log
        if (!$correo_existe && $user) {
          registrar_log($user ? $user : 'unknown', 'REG_FAIL');
        }

        header('Location: ./register.html');
        exit;
      }
    }
  } catch (Exception $e) {
    // Registrar error en log
    registrar_log($user ? $user : 'unknown', 'REG_FAIL');

    echo "Error (File: " . $e->getFile() . ", line " .
      $e->getLine() . "): " . $e->getMessage();
    echo "<script> window.open('https://www.ole.com.ar','_blank'); </script>";
  } finally {
    if ($errores === 0 && !headers_sent()) {
      header('Location: ./login.php');
      exit;
    }
  }
} else {
  header('Location: ./register.html');
  exit;
}
