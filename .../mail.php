<?php
require_once './phpMail/enviarMail.php'; // Incluir la utilidad para enviar correos
$configuracion = 'config.dat'; // Ruta al archivo de configuración
$user = $_POST["visitor_email"]; // Obtener el correo electrónico del usuario desde la solicitud POST
$code_verif = cripto_6(8); // Generar un código de verificación de 8 caracteres utilizando la función cripto_6

// Definir el contenido del correo en formato HTML
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
            <p>Le damos la bienvenida, <strong style="color: #2980b9;">' . $user . '</strong>.</p>
            <p>Gracias por registrarse en nuestro evento. A continuación encontrará su código de verificación, el cual necesitará para acceder a actividades exclusivas durante la jornada:</p>
            <div style="margin: 30px 0; text-align: center;">
                <span style="display: inline-block; background-color: #ecf0f1; padding: 15px 25px; border-radius: 8px; font-size: 20px; font-weight: bold; color: #27ae60;">' . $code_verif . '</span>
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

// Intentar enviar el correo
try {
    if (!file_exists($configuracion)) { // Verificar si el archivo de configuración existe
        throw new Exception("No se pudo abrir el archivo"); // Lanzar una excepción si el archivo no existe
    } else {
        $archivo = fopen($configuracion, 'r') or die("no puedo abrir archivo de datos"); // Abrir el archivo de configuración
        while (!feof($archivo)) { // Leer el archivo línea por línea
            $linea = fgets($archivo); // Obtener la línea actual
            $datos = explode("|", $linea); // Dividir la línea en partes
            $desde = $datos[0]; // Extraer el correo del remitente
            $credencial = $datos[1]; // Extraer la credencial
        }
        // Enviar el correo utilizando la función enviarMail
        enviarMail($user, $contenido, $asuntoMail, $adjunto = '', $desde, $credencial);
    }
} catch (Exception $e) { // Manejar excepciones
    echo "Error (File: " . $e->getFile() . ", line " . $e->getLine() . "): " . $e->getMessage(); // Mostrar el mensaje de error
    echo "<script> window.open('https://www.ole.com.ar','_blank'); </script>"; // Abrir una URL de respaldo
} finally {
    echo "<script> window.open('login.html','_blank'); </script>"; // Redirigir a la página de inicio de sesión
}

// Definir varias funciones para generar códigos aleatorios
function cripto_1($len) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz'; // Definir el conjunto de caracteres permitidos
    if ($len > 0 && $len <= 36) { // Verificar si la longitud es válida
        return substr(str_shuffle($permitted_chars), 0, $len); // Generar una cadena aleatoria
    } else {
        return substr(str_shuffle($permitted_chars), 0, 8); // Por defecto, generar una cadena de 8 caracteres
    }
}

function cripto_2($len) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Definir el conjunto de caracteres permitidos
    if ($len > 0 && $len <= 62) { // Verificar si la longitud es válida
        return substr(str_shuffle($permitted_chars), 0, $len); // Generar una cadena aleatoria
    } else {
        return substr(str_shuffle($permitted_chars), 0, 8); // Por defecto, generar una cadena de 8 caracteres
    }
}

function cripto_3($len) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Definir el conjunto de caracteres permitidos
    $input_length = strlen($permitted_chars); // Obtener la longitud del conjunto de caracteres
    $random_string = ''; // Inicializar la cadena aleatoria
    for ($i = 0; $i < $len; $i++) { // Bucle para generar la cadena
        $random_character = substr(str_shuffle($permitted_chars), 0, 1); // Obtener un carácter aleatorio
        $random_string .= $random_character; // Agregar el carácter a la cadena
    }
    return $random_string; // Devolver la cadena generada
}

function cripto_4($len) {
    return bin2hex(random_bytes($len)); // Generar una cadena hexadecimal aleatoria
}

function cripto_5($len) {
    return substr(md5(time()), 0, $len); // Generar una cadena aleatoria basada en hash MD5
}

function cripto_6($len) {
    return substr(sha1(time()), 0, $len); // Generar una cadena aleatoria basada en hash SHA1
}
?>