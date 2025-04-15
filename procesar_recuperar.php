<?php
require_once './helpers.php';

// Procesar el cambio de contraseña
if (isset($_POST['action']) && $_POST['action'] == 'cambiar') {
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $code = isset($_POST['code']) ? trim($_POST['code']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
  $mensaje = '';
  $tipo_mensaje = '';

  // Validaciones
  if (empty($email) || empty($code)) {
    $mensaje = 'Se requiere correo electrónico y código de verificación.';
    $tipo_mensaje = 'danger';
  } elseif (empty($password)) {
    $mensaje = 'Por favor, ingrese una contraseña.';
    $tipo_mensaje = 'warning';
  } elseif ($password !== $confirm_password) {
    $mensaje = 'Las contraseñas no coinciden.';
    $tipo_mensaje = 'warning';
  } else {
    // Verificar si el código es válido
    $codigo_valido = false;
    $archivo_de_usuarios = './archivos/Usuarios.dat';
    $client_ip = $_SERVER['REMOTE_ADDR'];
    $datos_usuario_encontrado = null;

    if (file_exists($archivo_de_usuarios)) {
      $archivo = fopen($archivo_de_usuarios, 'r');

      while (!feof($archivo)) {
        $linea = fgets($archivo);
        if (!empty(trim($linea))) {
          $datos_usuario = explode("|", trim($linea));

          // Verificar si encontramos al usuario y si el código coincide
          if (
            count($datos_usuario) >= 7 &&
            trim($datos_usuario[1]) === $email &&
            trim($datos_usuario[3]) === $code &&
            (trim($datos_usuario[6]) == 1 || trim($datos_usuario[6]) == 2)
          ) {
            // Verificar IP o código
            if ((isset($datos_usuario[5]) && trim($datos_usuario[5]) == $client_ip) || trim($datos_usuario[3]) === $code) {
              $codigo_valido = true;
              $datos_usuario_encontrado = $datos_usuario;
              break;
            }
          }
        }
      }
      fclose($archivo);
    }

    if (!$codigo_valido) {
      $mensaje = 'La solicitud de recuperación no es válida o ha expirado.';
      $tipo_mensaje = 'danger';
      header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
      exit;
    }

    // Actualizar la contraseña en el archivo
    $directorio_archivos = './archivos';
    $archivo_temp = $directorio_archivos . '/Usuarios_temp.dat';
    $password_actualizado = false;
    $fecha_actual = date('Y-m-d H:i:s');

    // Asegurar que el directorio tiene permisos adecuados
    if (!is_writable($directorio_archivos)) {
      // Intentar cambiar permisos (en entornos donde PHP tiene permiso)
      @chmod($directorio_archivos, 0755); // 0755 = permisos para directorio
    }

    // Asegurar que el archivo original tiene permisos adecuados
    if (file_exists($archivo_de_usuarios) && !is_writable($archivo_de_usuarios)) {
      // Intentar cambiar permisos (en entornos donde PHP tiene permiso)
      @chmod($archivo_de_usuarios, 0644); // 0644 = permisos para archivo
    }

    if (file_exists($archivo_de_usuarios)) {
      // Leer todo el contenido del archivo
      $contenido_archivo = file_get_contents($archivo_de_usuarios);

      if ($contenido_archivo === false) {
        $mensaje = 'Error al leer el archivo de usuarios. Inténtelo más tarde.';
        $tipo_mensaje = 'danger';
        header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
        exit;
      }

      // Dividir el contenido en líneas
      $lineas = explode("\n", $contenido_archivo);
      $nuevo_contenido = [];

      foreach ($lineas as $linea) {
        if (!empty(trim($linea))) {
          $datos_usuario = explode("|", trim($linea));

          // Verificar si este es el usuario que estamos buscando
          if (
            count($datos_usuario) >= 7 &&
            trim($datos_usuario[1]) === $email &&
            trim($datos_usuario[3]) === $code &&
            (trim($datos_usuario[6]) == 1 || trim($datos_usuario[6]) == 2)
          ) {
            // Actualizar la contraseña y el status
            $datos_usuario[2] = password_hash($password, PASSWORD_BCRYPT);
            $datos_usuario[3] = ''; // Limpiar el código de recuperación
            $datos_usuario[5] = $fecha_actual;
            $datos_usuario[6] = 1; // Status activo

            // Asegurarnos de que el array tenga 8 elementos para el reseteo de intentos
            if (count($datos_usuario) < 8) {
              $datos_usuario[7] = 0; // Añadir el campo si no existe
            } else {
              $datos_usuario[7] = 0; // Sobrescribir si ya existe
              // Recortar el array a 8 elementos para eliminar cualquier campo adicional
              $datos_usuario = array_slice($datos_usuario, 0, 8);
            }

            // Añadir la línea modificada
            $nuevo_contenido[] = implode("|", $datos_usuario);
            $password_actualizado = true;
          } else {
            // Añadir la línea sin modificar
            $nuevo_contenido[] = $linea;
          }
        }
      }

      // Guardar el nuevo contenido directamente al archivo original
      // Intentamos primero escribir al archivo temporal
      if (file_put_contents($archivo_temp, implode("\n", $nuevo_contenido)) !== false) {
        // Si tiene éxito, tratamos de reemplazar el original
        if (!rename($archivo_temp, $archivo_de_usuarios)) {
          // Si falla, intentamos escribir directamente al original
          if (file_put_contents($archivo_de_usuarios, implode("\n", $nuevo_contenido)) === false) {
            $mensaje = 'Error al guardar los cambios. Inténtelo más tarde.';
            $tipo_mensaje = 'danger';
            header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
            exit;
          }
          // Eliminar el archivo temporal si existe
          if (file_exists($archivo_temp)) {
            @unlink($archivo_temp);
          }
        }
      } else {
        // Si falla el archivo temporal, intentamos escribir directamente
        if (file_put_contents($archivo_de_usuarios, implode("\n", $nuevo_contenido)) === false) {
          $mensaje = 'Error al guardar los cambios. Inténtelo más tarde.';
          $tipo_mensaje = 'danger';
          header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
          exit;
        }
      }

      if ($password_actualizado) {
        // Registrar en log
        registrar_log($email, 'REC_OK');

        // Redirigir al login con mensaje de éxito
        header('Location: ./login.php?password_changed=1');
        exit;
      } else {
        $mensaje = 'No se pudo actualizar la contraseña. La solicitud podría haber expirado.';
        $tipo_mensaje = 'danger';
      }
    } else {
      $mensaje = 'Error: Archivo de usuarios no encontrado.';
      $tipo_mensaje = 'danger';
    }
  }

  // Si llegamos aquí, hubo algún error
  header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
  exit;
} else {
  // Si no hay acción de cambio, redirigir al login
  header("Location: ./login.php");
  exit;
}
