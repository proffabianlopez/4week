<?php
require_once './helpers.php';

// Procesar el cambio de contraseña
if (isset($_POST['action']) && $_POST['action'] == 'cambiar') {
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
  $mensaje = '';
  $tipo_mensaje = '';

  // Validaciones
  if (empty($email)) {
    $mensaje = 'Se requiere un correo electrónico valido.';
    $tipo_mensaje = 'danger';
  } elseif (empty($password)) {
    $mensaje = 'Por favor, ingrese una contraseña.';
    $tipo_mensaje = 'warning';
  } elseif ($password !== $confirm_password) {
    $mensaje = 'Las contraseñas no coinciden.';
    $tipo_mensaje = 'warning';
  } else {
    $archivo_de_usuarios = './archivos/Usuarios.dat';
    $datos_usuario_encontrado = null;

    if (file_exists($archivo_de_usuarios)) {
      $archivo = fopen($archivo_de_usuarios, 'r');

      while (!feof($archivo)) {
        $linea = fgets($archivo);
        if (!empty(trim($linea))) {
          $datos_usuario = explode("|", trim($linea));

          // Verificar si encontramos al usuario
          if (
            count($datos_usuario) >= 7 &&
            trim($datos_usuario[1]) === $email &&
            (trim($datos_usuario[6]) == 1 || trim($datos_usuario[6]) == 2)
          ) {
            $datos_usuario_encontrado = $datos_usuario;
            break;
          }
        }
      }
      fclose($archivo);
    }

    // Actualizar la contraseña directamente en el archivo
    $password_actualizado = false;
    $fecha_actual = date('Y-m-d H:i:s');

    // Verificar que el archivo tiene permisos de escritura
    if (!file_exists($archivo_de_usuarios) || !is_writable($archivo_de_usuarios)) {
      $mensaje = 'Error de permisos en el archivo de usuarios. Contacte al administrador.';
      $tipo_mensaje = 'danger';
      header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
      exit;
    }

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
    $usuarios_actualizados = [];

    foreach ($lineas as $linea) {
      if (!empty(trim($linea))) {
        $datos_usuario = explode("|", trim($linea));

        // Verificar si este es el usuario que estamos buscando
        if (
          count($datos_usuario) >= 7 &&
          trim($datos_usuario[1]) === $email &&
          (trim($datos_usuario[6]) == 1 || trim($datos_usuario[6]) == 2)
        ) {
          // Actualizar la contraseña y el status
          $datos_usuario[2] = password_hash($password, PASSWORD_BCRYPT);
          $datos_usuario[5] = $fecha_actual;
          $datos_usuario[6] = 1; // Status activo

          // Asegurarnos de que el array tenga 8 elementos para el reseteo de intentos
          if (count($datos_usuario) < 8) {
            $datos_usuario[7] = 0; // Añadir el campo si no existe
          } else {
            $datos_usuario[7] = 0; // Sobrescribir si ya existe
            $datos_usuario = array_slice($datos_usuario, 0, 8);
          }

          // Añadir la línea modificada
          $usuarios_actualizados[] = rtrim(implode("|", $datos_usuario)) . "\n";
          $password_actualizado = true;
        } else {
          // Añadir la línea sin modificar
          $usuarios_actualizados[] = rtrim($linea) . "\n";
        }
      }
    }

    // Abrir el archivo para escritura
    $archivo = fopen($archivo_de_usuarios, 'w');
    if ($archivo === false) {
      $mensaje = 'Error al abrir el archivo para escritura. Inténtelo más tarde.';
      $tipo_mensaje = 'danger';
      header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
      exit;
    }

    // Escribir todas las líneas sin agregar línea adicional al final
    $ultimo_indice = count($usuarios_actualizados) - 1;
    for ($i = 0; $i <= $ultimo_indice; $i++) {
      fwrite($archivo, $usuarios_actualizados[$i]);
    }
    fclose($archivo);

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
  }

  // Si llegamos aquí, hubo algún error
  header("Location: ./recuperar.php?mensaje=" . urlencode($mensaje) . "&tipo=" . urlencode($tipo_mensaje));
  exit;
} else {
  // Si no hay acción de cambio, redirigir al login
  header("Location: ./login.php");
  exit;
}
