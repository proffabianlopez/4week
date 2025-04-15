<?php

$validateCode = $_POST['validateCode'];

$usersFile = fopen('../data/users.dat', 'r');
$updatedLines = [];
$validation = false;

while ($line = fgets($usersFile)) {
    $userData = explode('|', $line);

    if (trim($userData[3]) == $validateCode) {
        $userData[5] = date("Y-m-d H:i:s");
        $userData[6] = 1;
        $email = $userData[0];
        $line = implode('|', $userData) . PHP_EOL;
        $validation = true;
    }
    $updatedLines[] = $line;
}
fclose($usersFile);

$usersFile = fopen('../data/users.dat', 'w');
foreach ($updatedLines as $updatedLine) {
    fwrite($usersFile, $updatedLine);
}
fclose($usersFile);

if(!$validation) {
    $logFile = fopen("../data/logs.dat", "a");
    $logContent = $email . "|" . date("Y-m-d H:i:s") . "|" . "Error al validar código" . "\n";
    fwrite($logFile, $logContent);
    fclose($logFile);
    echo '<script>
            alert("Código de validación incorrecto. Intenta nuevamente.");
            window.location.href = "../view/validate_code.php"; 
        </script>';
    exit();
}elseif($validation){
    $logFile = fopen("../data/logs.dat", "a");
    $logContent = $email . "|" . date("Y-m-d H:i:s") . "|" . "Validación de código" . "\n";
    fwrite($logFile, $logContent);
    fclose($logFile);
    echo '<script>
            alert("Código de validación exitoso. Ahora puedes iniciar sesión.");
            window.location.href = "../view/login.php"; 
        </script>';
}

?>