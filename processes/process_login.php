<?php
require_once("../utils/password_utils.php");

$email = $_POST['email'];
$password = $_POST['password'];
$updatedLines = [];
$loginSuccess = false;

$usersFile = fopen("../data/users.dat", "r");
while ($line = fgets($usersFile)) {
    $userData = explode("|", $line);
    $userEmail = trim($userData[0]);
    $userPassword = decryptPassword(trim($userData[2]));

    if ($userEmail == $email && $userPassword == $password && $userData[6] == 1) {
        $userData[4] = date("Y-m-d H:i:s");
        $line = implode('|', $userData) . PHP_EOL;
        $updatedLines[] = $line;
        $loginSuccess = true;

        $logFile = fopen("../data/logs.dat", "a");
        $logContent = $userEmail . "|" . date("Y-m-d H:i:s") . "|" . "Inicio de sesion" . "\n";
        fwrite($logFile, $logContent);
        fclose($logFile);

        echo "<script>
            alert('Sesión iniciada con éxito!');
            window.location.href = '../view/welcome.php';
            </script>";
        break;
    } else {
        $updatedLines[] = $line;
    }
}
fclose($usersFile);

if (!$loginSuccess) {
    $logFile = fopen("../data/logs.dat", "a");
    $logContent = $email . "|" . date("Y-m-d H:i:s") . "|" . "Error al iniciar sesion" . "\n";
    fwrite($logFile, $logContent);
    fclose($logFile);

    echo "<script>
        alert('Error: Correo o contraseña incorrectos');
        window.location.href = '../view/login.php';
        </script>";
    exit;
}

$usersFile = fopen("../data/users.dat", "w");
foreach ($updatedLines as $updatedLine) {
    fwrite($usersFile, $updatedLine);
}
fclose($usersFile);
?>