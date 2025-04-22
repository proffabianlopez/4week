<?php

require_once './phpMail/class.phpmailer.php';
require_once './phpMail/enviarMail.php';

    $configuracion='./config.dat';
    $data_user='./usuarios.dat';
    $user=$_POST ['email'];
    $apellido = $_POST['lastname'];
    $nombre = $_POST['name'];
    $password=$_POST ['password'];
    $codigoVerificacion = rand(1000, 9999);
    $fechaActivacion = date("Y-m-d H:i:s");
    $fechaUltActividad = date("Y-m-d H:i:s");
    $status= 0;
    $contenido = '<html>
        <head><meta charset="UTF-8" /></head>
        <body style="background-color: #f0f0f0; font-family: Arial, sans-serif;">
            <h2>Tu código de verificación es:</h2>
            <h1 style="color:blue;">'.$codigoVerificacion.'</h1>
        </body>
        </html>';
    $asuntoMail = 'Código de verificación';

    if(!file_exists($data_user)){
        echo "El archivo usuarios no existe.";
    }
    else{
        $archivo=fopen($data_user, 'r') or die("No se puede abrir el archivo usuarios");
        while (!feof($archivo) && $status==0){
            $linea=fgets($archivo);
            $datos=explode('|', $linea);
            $users=$datos[0];
            $statusArchivo=$datos[7];
            if(strcmp($users,$user)==0){
                $status=1;
                break;
            }
        }
        fclose($archivo);
        
        if ($status==1){
            echo 'El usuario ya existe';
        }
        else{
            $archivo=fopen($data_user,'a+') or die("no puedo abrir archivo de usuarios para escritura");
            fputs($archivo,$user."|".$apellido."|".$nombre."|".$password."|".$codigoVerificacion."|".$fechaActivacion."|".$fechaUltActividad."|".$status . "\n");
            fclose($archivo);
            $archivo=fopen($configuracion,'r') or die("no puedo abrir archivo de configuracion");
                while(!feof($archivo)){
                    $linea=fgets($archivo);
                    $configuracion=explode("|",$linea);
                    $desde=$configuracion[0];
                    $credencial=$configuracion[1];
                    }      
            $envio = enviarMail($user,$contenido,$asuntoMail, $adjunto='',$desde,$credencial) ;
            if($envio){
                echo '<script>
                        alert("Se ha enviado satisfactoriamente el mail");
                    </script>';
                echo '<script>      
                        window.location="login.html";
                    </script>';
            }
        }
    }
?>


