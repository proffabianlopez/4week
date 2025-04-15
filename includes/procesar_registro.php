<?php
require_once __DIR__ . '/funciones.php';

$email = trim($_POST['email']);
$apellido = trim($_POST['apellido']);
$nombre = trim($_POST['nombre']);
$password = $_POST['password'];
$confirmar = $_POST['confirmar'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    escribir_log("REGISTRO FALLIDO - Email inválido - $email");
    die('Email no válido.');
}

if ($password !== $confirmar) {
    escribir_log("REGISTRO FALLIDO - Contraseñas no coinciden - $email");
    die('Las contraseñas no coinciden.');
}

$usuarios = leer_usuarios();

foreach ($usuarios as $usuario) {
    if ($usuario[0] === $email) {
        escribir_log("REGISTRO FALLIDO - Email ya registrado - $email");
        die('No se pudo completar el registro. Si el email está registrado, inicie sesión o recupere su contraseña.');
    }
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$codigo = rand(100000, 999999); 
$fecha_activacion = date('Y-m-d');
$fecha_ultima = '';
$status = 0; 
$intentos = 0;

$usuarios[] = [
    $email,
    $apellido,
    $nombre,
    $hash,
    $codigo,
    $fecha_activacion,
    $fecha_ultima,
    $status,
    '',      
    $intentos
]; 

guardar_usuarios($usuarios);
escribir_log("REGISTRO exitoso - $email");

$asunto = "Activación de cuenta";
$mensaje = "Hola $nombre,\n\nGracias por registrarte. Para activar tu cuenta, haz clic en el siguiente enlace:\n";
$enlace = "http://localhost/4week/activar.php?email=" . urlencode($email) . "&codigo=$codigo";
$mensaje .= $enlace;
$mensaje .= "\n\nSi no puedes hacer clic en el enlace, cópialo y pégalo en tu navegador.";
$headers = "From: no-reply@instituto.edu.ar";


echo "Registro exitoso. Se ha enviado un correo de activación a $email (Simulado: $enlace).";
?>
