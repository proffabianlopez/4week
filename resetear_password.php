<?php
require 'funciones.php';

$email = trim($_POST['email']);
$codigo_ingresado = trim($_POST['codigo']);
$password = $_POST['password'];
$confirmar = $_POST['confirmar'];

if ($password !== $confirmar) {
    escribir_log("RESET FALLIDO - Contraseñas distintas - $email");
    die('Las contraseñas no coinciden.');
}

$usuarios = leer_usuarios();
$actualizado = false;

foreach ($usuarios as &$usuario) {
    if ($usuario[0] === $email && $usuario[7] == 2) {
        if ($usuario[4] == $codigo_ingresado) {
            $usuario[3] = password_hash($password, PASSWORD_DEFAULT);
            $usuario[7] = 1; 
            $usuario[6] = date('Y-m-d');
            escribir_log("RESET EXITOSO - Contraseña cambiada - $email");
            $actualizado = true;
            break;
        } else {
            escribir_log("RESET FALLIDO - Código incorrecto - $email");
            die('Código incorrecto.');
        }
    }
}

if (!$actualizado) {
    escribir_log("RESET FALLIDO - Email no encontrado o estado incorrecto - $email");
    die('No se pudo actualizar la contraseña.');
}

guardar_usuarios($usuarios);
header('Location: ../index.php?reset=ok');
exit;
