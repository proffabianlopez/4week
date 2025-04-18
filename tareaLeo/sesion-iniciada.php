<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sesion iniciada</title>
</head>
<body>

<?php

$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];


echo "Bienvenido: ". $username;




?>

<br>
<a href="entrar.html"> <button>Cerrar sesion</button> </a>

</body>
</html>