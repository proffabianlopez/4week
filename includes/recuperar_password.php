<?php
require 'funciones.php';

$email = trim($_POST['email']);
$usuarios = leer_usuarios();

$encontrado = false;
foreach ($usuarios as &$usuario) {
    if ($usuario[0] === $email) {
        $encontrado = true;
        $usuario[7] = 2; 
        $usuario[4] = rand(100000, 999999); 
        escribir_log("RECUPERACIÓN SOLICITADA - $email");
        break;
    }
}

guardar_usuarios($usuarios);

echo "Si su email se encuentra registrado, recibirá un correo con las instrucciones para recuperar su contraseña.";

