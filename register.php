<?php
require_once './phpMail/enviarMail.php';
if($_SERVER['REQUEST_METHOD']==='POST')
{
   $config=parse_ini_file('archivos/config.dat', true); 
$ubi="archivos/usuarios.dat";
$nombres=$_POST['visitor_name'];
$email =$_POST['visitor_email'];
$password=password_hash(trim($_POST['visitor_password']), PASSWORD_DEFAULT);
$cod_ver=rand(10000 , 99999);
$fech_Activ=date('d/m/y h:i:s');

    $dat = "$email | $nombres | $password | $cod_ver | $fech_Activ | \n " ;
    
    $guardar=fopen($ubi,"a");
    if($guardar)
    {
    fwrite($guardar,$dat);
    fclose($guardar);
   }


    file_put_contents('log.dat', date('d/m/Y H:i:s') . ", Nuevo registro: $email\n", FILE_APPEND);

    echo "<div class='alert alert-success' role='alert'>Registro exitoso. Por favor verifica tu correo.</div>"; 
    
    
   $to=$email;
$asunto="Codigo De Verificasion";
$mensaje="Tu Codigo De verificasion es: $cod_ver";
$header = "From: " . $config['correo']['usuario'] . "\r\n";  
$header .= "Reply-To: " . $config['correo']['usuario'] . "\r\n";
$config = parse_ini_file('archivos/config.dat', true); 
$correo = $config['correo']['usuario'];
$clave = $config['token']['valor'];

$mensajeHTML = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    h1
{
    background-color: darkgoldenrod;
    color: gold;
    justify-content: center;
}
p
{
    background-color: burlywood;
    text-align: center;

}
h3
{
    background-color: rgba(0, 0, 0, 0.452);
    text-align: center;
}
h4
{
    background-color: darkgoldenrod;
    text-align: center;
}
    </style>
</head>
<body>
    <h1>bienvenido '.$nombres.'</h1>
    <p>Espero Que Sea De Su agrado la pagina</p>
    <p>Ah Continuación Le Comparto El Codigo De Verificacion</p>
    <h3>'.$cod_ver.'</h3>
    <h4>Este Es Un Mensaje Automatico No Respoder </h4>
</body>
</html>';

$resultado = enviarMail(
    $email,           
    $correo,           
    $clave,            
    $mensajeHTML,      
    "Código de Verificación" 
);

if ($resultado === true) {
    echo "<div class='alert alert-info' role='alert'>Se ha enviado el correo de verificación.</div>";
} else {
    echo "<div class='alert alert-danger' role='alert'>$resultado</div>";
}

}
?>