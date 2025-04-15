<?php
require_once('../utils/password_utils.php');

$name = $_POST['name'];
$email = $_POST['email'];
$password = encryptPassword($_POST['password']);
$verificationCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
$activationDate = 0;
$lastActiveDate = 0;
$state = 0;

$file = fopen("../data/users.dat", "a");
fwrite($file, $email . "|" . $name . "|" . $password . "|" . $verificationCode . "|" . $activationDate . "|" . $lastActiveDate . "|" . $state . "\n");
fclose($file);

$logFile = fopen("../data/logs.dat", "a");
$logContent = $email . "|" . date("Y-m-d H:i:s") . "|" . "Registro de usuario" . "\n";
fwrite($logFile, $logContent);
fclose($logFile);

require_once('../phpMail/enviarMail.php');

$config = '../data/config.dat';
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
            Gracias por registrarte en nuestro sitio.<br><br>
            Tu código de verificación es: <strong>$verificationCode</strong><br><br>
            Saludos,<br>
            El equipo de soporte
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

enviarMail($email, $content, "Código de verificación", '', $emailFrom, $credential);
fclose($fileConfig);

echo '<script>
        alert("Registro exitoso. Por favor, revisa tu correo para el código de verificación.");
        window.location.href = "../view/validate_code.php"; 
    </script>';

?>