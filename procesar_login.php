<?php
require_once './helpers.php';

// Función para verificar y actualizar usuarios no activados
function verificar_cuentas_no_activadas()
{
  $archivo_de_usuarios = './archivos/Usuarios.dat';
  $dias_limite = 3; // Límite de días para activar la cuenta

  if (file_exists($archivo_de_usuarios)) {
    $archivo = fopen($archivo_de_usuarios, 'r');
    $usuarios_actualizados = [];
    $fecha_actual = new DateTime();

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_usuario = explode("|", $linea);

        // Verificar si tenemos todos los campos esperados
        if (count($datos_usuario) >= 7) {
          $status_usuario = (int)trim($datos_usuario[6]);

          // Solo procesar usuarios con status 0
          if ($status_usuario === 0) {
            // Obtener fecha de registro
            $fecha_registro = trim($datos_usuario[5]);

            if (!empty($fecha_registro)) {
              try {
                $fecha_registro_obj = new DateTime($fecha_registro);
                $diferencia = $fecha_actual->diff($fecha_registro_obj);

                // Si han pasado más de X días desde el registro
                if ($diferencia->days >= $dias_limite) {
                  $datos_usuario[6] = 4;

                  // Registrar en el log
                  if (function_exists('registrar_log')) {
                    registrar_log(trim($datos_usuario[1]), 'ACCOUNT_INACTIVE_TIMEOUT');
                  }

                  $linea = implode("|", $datos_usuario);
                }
              } catch (Exception $e) {
                // Error al procesar la fecha, no hacemos cambios
              }
            }
          }
        }
        $usuarios_actualizados[] = $linea;
      }
    }
    fclose($archivo);

    // Guardar los cambios en el archivo
    $archivo = fopen($archivo_de_usuarios, 'w');
    foreach ($usuarios_actualizados as $linea) {
      fwrite($archivo, $linea);
    }
    fclose($archivo);
  }
}

// Ejecutar verificación de cuentas no activadas
verificar_cuentas_no_activadas();

if (isset($_POST)) {
  $archivo_de_usuarios = './archivos/Usuarios.dat';
  $archivo_de_config = './archivos/config.dat';
  $email = (isset($_POST['email'])) ? $_POST['email'] : false;
  $password = (isset($_POST['password'])) ? $_POST['password'] : false;
  $remember = (isset($_POST['remember'])) ? true : false;
  $fecha_actual = date('Y-m-d H:i:s');
  $login_exitoso = false;
  $status_usuario = null;
  $nombre_usuario = '';

  // Leer el número máximo de intentos desde config.dat
  $max_intentos = 3; // Valor por defecto
  if (file_exists($archivo_de_config)) {
    $config = fopen($archivo_de_config, 'r');
    while (!feof($config)) {
      $linea = fgets($config);
      if (!empty(trim($linea))) {
        $datos_config = explode("|", $linea);
        if (count($datos_config) >= 3) {
          $max_intentos = (int)trim($datos_config[2]);
          break;
        }
      }
    }
    fclose($config);
  }

  if ($email && $password && file_exists($archivo_de_usuarios)) {
    $archivo = fopen($archivo_de_usuarios, 'r');
    $usuarios_actualizados = [];
    $usuario_encontrado = false;
    $intentos_usuario = 0;

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_usuario = explode("|", $linea);

        // Verificar si tenemos todos los campos esperados
        if (count($datos_usuario) >= 7) {
          // Si este es el usuario que intenta iniciar sesión
          if (trim($datos_usuario[1]) === $email) {
            $usuario_encontrado = true;
            $status_usuario = (int)trim($datos_usuario[6]);

            // Verificar si el usuario ya está bloqueado o inactivo
            if ($status_usuario === 3 || $status_usuario === 4) {
              $login_exitoso = false;
              $usuarios_actualizados[] = $linea;
              continue;
            }

            // Verificar si el usuario no está validado y han pasado más de 3 días
            if ($status_usuario === 0) {
              $fecha_registro = trim($datos_usuario[4]);
              if (!empty($fecha_registro)) {
                try {
                  $fecha_registro_obj = new DateTime($fecha_registro);
                  $fecha_actual_obj = new DateTime();
                  $diferencia = $fecha_actual_obj->diff($fecha_registro_obj);

                  // Si han pasado más de 3 días
                  if ($diferencia->days >= 3) {
                    // Cambiar status a 4 (inactivo)
                    $datos_usuario[6] = 4;
                    $status_usuario = 4;
                    $linea = implode("|", $datos_usuario);
                    $usuarios_actualizados[] = $linea;

                    // Registrar en el log
                    if (function_exists('registrar_log')) {
                      registrar_log($email, 'ACCOUNT_INACTIVE_TIMEOUT');
                    }

                    continue;
                  }
                } catch (Exception $e) {
                  // Error al procesar la fecha, continuamos normalmente
                }
              }
            }

            // Obtener intentos fallidos (añadimos un nuevo campo si no existe)
            $intentos_usuario = isset($datos_usuario[7]) ? (int)trim($datos_usuario[7]) : 0;

            // Verificar contraseña
            if (password_verify($password, trim($datos_usuario[2]))) {
              $login_exitoso = true;
              $nombre_usuario = trim($datos_usuario[0]);

              // Actualizar fecha de última actividad y resetear intentos fallidos
              $datos_usuario[5] = $fecha_actual;

              // Asegurarnos de que el array tenga 8 elementos para el reseteo de intentos
              if (count($datos_usuario) < 8) {
                $datos_usuario[7] = 0; // Añadir el campo si no existe
              } else {
                $datos_usuario[7] = 0; // Sobrescribir si ya existe
                // Recortar el array a 8 elementos para eliminar cualquier campo adicional
                $datos_usuario = array_slice($datos_usuario, 0, 8);
              }

              $linea = implode("|", $datos_usuario);
            } else {
              // Incrementar intentos fallidos
              $intentos_usuario++;

              // Asegurarnos de que el array tenga 8 elementos (7 + intentos)
              if (count($datos_usuario) < 8) {
                $datos_usuario[7] = $intentos_usuario; // Añadir el campo si no existe
              } else {
                $datos_usuario[7] = $intentos_usuario; // Sobrescribir si ya existe
                // Recortar el array a 8 elementos para eliminar cualquier campo adicional
                $datos_usuario = array_slice($datos_usuario, 0, 8);
              }

              // Si excede el máximo de intentos, cambiar status a 3 (bloqueado)
              if ($intentos_usuario >= $max_intentos) {
                $datos_usuario[6] = 3; // Estado bloqueado
                $status_usuario = 3;
              }

              $linea = implode("|", $datos_usuario);
            }
          }
        }
        $usuarios_actualizados[] = $linea;
      }
    }
    fclose($archivo);

    // Guardar los cambios en el archivo de usuarios
    $archivo = fopen($archivo_de_usuarios, 'w');
    foreach ($usuarios_actualizados as $linea) {
      fwrite($archivo, $linea);
    }
    fclose($archivo);

    if ($login_exitoso) {
      // Registrar el inicio de sesión en log
      registrar_log($email, 'LOG_OK');
    } elseif ($usuario_encontrado) {
      // Registrar error en log
      registrar_log($email, 'LOG_FAIL');

      // Si el usuario ha sido bloqueado en este intento
      if ($status_usuario === 3 && $intentos_usuario >= $max_intentos) {
        registrar_log($email, 'USER_BLOCKED');
      }
    }
  }

  if ($login_exitoso) {
    session_start();
    $_SESSION['email'] = $email;
    $_SESSION['nombre'] = $nombre_usuario;
    $_SESSION['status'] = $status_usuario;

    // Si el usuario quiere recordar su contraseña
    if ($remember) {
      // Generar un token único para la cookie
      $token = bin2hex(random_bytes(32));

      // Guardar el token en el archivo de Usuarios.dat
      guardar_token_remember($email, $token);

      // Establecer que la cookie dure 30 días
      setcookie("remember_user", $email, time() + 2592000, "/", "", true, true);
      setcookie("remember_token", $token, time() + 2592000, "/", "", true, true);
    }

    switch ($status_usuario) {
      case 0:
        header('Location: ./no_validado.php');
        break;

      case 1:
        header('Location: ./panel_de_usuario.php');
        exit;

      case 2:
        header('Location: ./recuperar.php');
        exit;

      case 3:
        header('Location: ./bloqueado.html');
        break;

      case 4:
        header('Location: ./inactivo.html');
        break;
    }
  } else {
    // Si el usuario fue encontrado, verificar su estado
    if ($usuario_encontrado) {
      switch ($status_usuario) {
        case 3:
          header('Location: ./bloqueado.html');
          exit;
        case 4:
          header('Location: ./inactivo.html');
          exit;
        default:
          header('Location: ./login.php?error=1');
          exit;
      }
    } else {
      header('Location: ./login.php?error=1');
      exit;
    }
  }
} else {
  // Verificar si existe la cookie de "recordar contraseña"
  if (isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_token'])) {
    $email = $_COOKIE['remember_user'];
    $token = $_COOKIE['remember_token'];

    // Verificar si el token es válido
    if (verificar_token_remember($email, $token)) {
      // Login automático
      session_start();
      $datos_usuario = obtener_datos_usuario($email);

      if ($datos_usuario) {
        $_SESSION['email'] = $email;
        $_SESSION['nombre'] = $datos_usuario['nombre'];
        $_SESSION['status'] = $datos_usuario['status'];

        // Actualizar fecha de última actividad
        actualizar_ultima_actividad($email);

        switch ($datos_usuario['status']) {
          case 0:
            header('Location: ./no_validado.php');
            break;
          case 1:
            header('Location: ./panel_de_usuario.php');
            exit;
          case 2:
            header('Location: ./recuperar.php');
            exit;
          case 3:
            header('Location: ./bloqueado.html');
            break;
          case 4:
            header('Location: ./inactivo.html');
            break;
        }
      }
    } else {
      // Token inválido, eliminar cookies
      setcookie("remember_user", "", time() - 3600, "/", "", true, true);
      setcookie("remember_token", "", time() - 3600, "/", "", true, true);
      header('Location: ./login.php');
      exit;
    }
  } else {
    header('Location: ./login.php');
    exit;
  }
}

function guardar_token_remember($email, $token)
{
  $archivo_tokens = './archivos/tokens_remember.dat';
  $fecha_expiracion = date('Y-m-d H:i:s', time() + 2592000);

  // Eliminar tokens anteriores para este usuario
  if (file_exists($archivo_tokens)) {
    $archivo = fopen($archivo_tokens, 'r');
    $tokens = [];

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_token = explode("|", $linea);
        // Si no es el mismo usuario, mantener la línea
        if (count($datos_token) >= 3 && trim($datos_token[0]) !== $email) {
          $tokens[] = $linea;
        }
      }
    }
    fclose($archivo);

    // Agregar el nuevo token
    $tokens[] = "$email|$token|$fecha_expiracion\n";

    // Guardar todos los tokens
    $archivo = fopen($archivo_tokens, 'w');
    foreach ($tokens as $linea) {
      fwrite($archivo, $linea);
    }
    fclose($archivo);
  } else {
    // Si el archivo no existe, crearlo
    $archivo = fopen($archivo_tokens, 'w');
    fwrite($archivo, "$email|$token|$fecha_expiracion\n");
    fclose($archivo);
  }
}

function verificar_token_remember($email, $token)
{
  $archivo_tokens = './archivos/tokens_remember.dat';

  if (file_exists($archivo_tokens)) {
    $archivo = fopen($archivo_tokens, 'r');

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_token = explode("|", $linea);

        if (count($datos_token) >= 3) {
          $email_guardado = trim($datos_token[0]);
          $token_guardado = trim($datos_token[1]);
          $fecha_expiracion = trim($datos_token[2]);

          // Verificar si coincide email y token, y si no ha expirado
          if (
            $email_guardado === $email && $token_guardado === $token &&
            strtotime($fecha_expiracion) > time()
          ) {
            fclose($archivo);
            return true;
          }
        }
      }
    }
    fclose($archivo);
  }

  return false;
}

function obtener_datos_usuario($email)
{
  $archivo_de_usuarios = './archivos/Usuarios.dat';

  if (file_exists($archivo_de_usuarios)) {
    $archivo = fopen($archivo_de_usuarios, 'r');

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_usuario = explode("|", $linea);

        if (count($datos_usuario) >= 7 && trim($datos_usuario[1]) === $email) {
          fclose($archivo);
          return [
            'nombre' => trim($datos_usuario[0]),
            'status' => (int)trim($datos_usuario[6])
          ];
        }
      }
    }
    fclose($archivo);
  }

  return false;
}

function actualizar_ultima_actividad($email)
{
  $archivo_de_usuarios = './archivos/Usuarios.dat';
  $fecha_actual = date('Y-m-d H:i:s');

  if (file_exists($archivo_de_usuarios)) {
    $archivo = fopen($archivo_de_usuarios, 'r');
    $usuarios_actualizados = [];

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_usuario = explode("|", $linea);

        if (count($datos_usuario) >= 7 && trim($datos_usuario[1]) === $email) {
          $datos_usuario[5] = $fecha_actual;

          // Asegurarnos de mantener solo 8 campos si existe el campo de intentos
          if (count($datos_usuario) >= 8) {
            $datos_usuario = array_slice($datos_usuario, 0, 8);
          }

          $linea = implode("|", $datos_usuario);
        }

        $usuarios_actualizados[] = $linea;
      }
    }
    fclose($archivo);

    $archivo = fopen($archivo_de_usuarios, 'w');
    foreach ($usuarios_actualizados as $linea) {
      fwrite($archivo, $linea);
    }
    fclose($archivo);

    // Registrar actividad en log
    if (function_exists('registrar_log')) {
      registrar_log($email, 'AUTO_LOG_OK');
    }
  }
}
