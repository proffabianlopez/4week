<?php

if($_SERVER['REQUEST_ METOD']==='POST')
{
    $nombres =($_POST['name']);
    $email =($_POST['email']);
    $password=password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $cod_ver=rand(10000 , 99999);
    $fech_Activ=date('d/m/y h:i:s');

    $dat = "$email,$nombre,$password,$cod_ver,$fech_activ,,,0\n" ;
    file_put_contents('usuario.dat',$dat,FILE_APPEND);

    file_put_contents('log.dat', date('d/m/Y H:i:s') . ", Nuevo registro: $email\n", FILE_APPEND);

    echo "<div class='alert alert-success' role='alert'>Registro exitoso. Por favor verifica tu correo.</div>";  
}


?>