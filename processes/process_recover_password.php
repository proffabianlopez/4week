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
        
        $content = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    color: #333;
                    line-height: 1.6;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    padding: 20px;
                    background: #ffffff;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .email-header {
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 20px;
                    color: #007BFF;
                }
                .email-body {
                    margin-bottom: 20px;
                }
                .email-footer {
                    font-size: 12px;
                    color: #555;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>Hola $name,</div>
                <div class='email-body'>
                    Su contraseña es $password.
                </div>
                <div class='email-footer'>
                    Este es un mensaje generado automáticamente. Por favor, no respondas a este correo.
                </div>
            </div>
        </body>
        </html>
        ";

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
    window.location.href = '../view/login.php';
    </script>";
?>