<?php
require_once('../phpMail/enviarMail.php');
require_once('../utils/password_utils.php');

$email = $_POST['email'];

$config = '../data/config.dat';

$usersFile = fopen("../data/users.dat", "r");

while($line = fgets($usersFile)) {
    $user = explode("|", $line);
    $name = $user[1];
    $password = decryptPassword($user[2]);
    if ($user[0] == $email) {        
        
        $content = "<h1>Recuperar contraseña</h1>
        <p>Hola $name,</p>
        <p>Hemos recibido una solicitud para restablecer tu contraseña. Si no solicitaste este cambio, puedes ignorar este correo.</p>"
        . "<p> Para recumerar tu contraseña, haz clic en el siguiente enlace:</p>
        <p><a href='http://localhost/4week/view/reset_password.php?email=$email'>Recuperar contraseña</a></p>";

        $fileConfig = fopen($config, 'r');

        $line = fgets($fileConfig);
        $fields = explode("|", $line);
        $emailFrom = $fields[0];
        $credential = $fields[1];

        enviarMail($email, $content, "Recuperar contraseña", '', $emailFrom, $credential);
        fclose($fileConfig);
        
        break;
    }
}
fclose($usersFile);

echo "<script>
    alert('Se ha enviado un correo a $email con el código de verificación. En caso de no recibirlo, verifica tu carpeta de spam o verifica que el correo sea correcto.');
    window.location.href = '../view/reset_password.php';
    </script>";
?>