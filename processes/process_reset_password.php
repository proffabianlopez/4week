<?php

echo "Credenciales recibidas: " . $_GET['email'] . " - " . $_POST['newPassword'] . "<br>";

require_once '../utils/password_utils.php';

$usersFile = fopen('../data/users.dat', 'r');

while($line = fgets($usersFile) !== false){
    $userData = explode('|', $line);
    $userPassword = trim($userData[2]);
}

echo "Contraseña actual: " . $userPassword . "<br>";

fclose($usersFile);

?>