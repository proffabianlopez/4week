<?php
require 'funciones.php';

$email = $_POST['email'];
$password = $_POST['password'];
$config = leer_config(); 
$usuarios = leer_usuarios();

$user_encontrado = false;

foreach ($usuarios as &$usuario) {
    if ($usuario[0] === $email) {
        $user_encontrado = true;
        $status = (int)$usuario[7];  
        $hash = $usuario[3];         
        $intentos = isset($usuario[9]) ? (int)$usuario[9] : 0;  

        if ($status === 3) {  
            escribir_log("Intento de login con cuenta BLOQUEADA - $email");
            header('Location: ../bloqueado.php');
            exit;
        }

        if ($status != 1) {  // 
            escribir_log("LOGIN FALLIDO - Cuenta no activa o en proceso - $email");
            header('Location: ../index.php?error=Credenciales inválidas');
            exit;
        }

        if (password_verify($password, $hash)) {  
            $usuario[6] = date('Y-m-d H:i:s');  
            $usuario[9] = 0;  
            escribir_log("LOGIN exitoso - $email");
            guardar_usuarios($usuarios);  

            session_start();
            $_SESSION['usuario'] = [
                'email' => $usuario[0],
                'apellido' => $usuario[1],
                'nombre' => $usuario[2],
                'ultima_actividad' => $usuario[6]
            ];
            header('Location: ../bienvenida.php');
            exit;
        } else {
            $intentos++;  
            $usuario[9] = $intentos;

            if ($intentos >= $config['max_intentos']) {  
                $usuario[7] = 3;  
                escribir_log("CUENTA BLOQUEADA - $email");
            } else {
                escribir_log("LOGIN FALLIDO - Credenciales inválidas - $email - Intentos: $intentos");
            }
            guardar_usuarios($usuarios);  
            header('Location: ../index.php?error=Credenciales inválidas');
            exit;
        }
    }
}

if (!$user_encontrado) { 
    escribir_log("LOGIN FALLIDO - Credenciales inválidas (usuario no encontrado) - $email");
    header('Location: ../index.php?error=Credenciales inválidas');
    exit;
}
?>

