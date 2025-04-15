<?php
$mensaje = '';
$tipo_mensaje = '';
$codigo_valido = false;
$email = '';
$code = '';

// Verificar si hay una sesión activa o una cookie que identifique al usuario
$client_ip = $_SERVER['REMOTE_ADDR'];

// Leer el archivo de usuarios para encontrar al usuario activo
$archivo_de_usuarios = './archivos/Usuarios.dat';
if (file_exists($archivo_de_usuarios)) {
  $archivo = fopen($archivo_de_usuarios, 'r');
  while (!feof($archivo)) {
    $linea = fgets($archivo);
    if (!empty(trim($linea))) {
      $datos_usuario = explode("|", $linea);
      // Buscar un usuario con estado de recuperación de contraseña pendiente
      if (
        count($datos_usuario) >= 7 &&
        (trim($datos_usuario[6]) == 1 || trim($datos_usuario[6]) == 2) &&
        (isset($datos_usuario[5]) && trim($datos_usuario[5]) == $client_ip)
      ) {
        $email = trim($datos_usuario[1]);
        $code = trim($datos_usuario[3]);
        $codigo_valido = true;
        break;
      }
    }
  }
  fclose($archivo);
}

if (!$codigo_valido) {
  $usuario_recuperacion = null;
  $timestamp_mas_reciente = 0;

  $archivo = fopen($archivo_de_usuarios, 'r');
  while (!feof($archivo)) {
    $linea = fgets($archivo);
    if (!empty(trim($linea))) {
      $datos_usuario = explode("|", $linea);
      // Verificar si hay algún usuario en proceso de recuperación
      if (
        count($datos_usuario) >= 7 &&
        (trim($datos_usuario[6]) == 1 || trim($datos_usuario[6]) == 2)
      ) {
        $timestamp = isset($datos_usuario[4]) ? intval(trim($datos_usuario[4])) : 0;
        if ($timestamp > $timestamp_mas_reciente) {
          $timestamp_mas_reciente = $timestamp;
          $usuario_recuperacion = $datos_usuario;
        }
      }
    }
  }
  fclose($archivo);

  if ($usuario_recuperacion !== null) {
    $email = trim($usuario_recuperacion[1]);
    $code = trim($usuario_recuperacion[3]);
    $codigo_valido = true;
  }
}

if (!$codigo_valido) {
  $mensaje = 'No se pudo identificar una solicitud de recuperación de contraseña válida.';
  $tipo_mensaje = 'danger';
}

if (isset($_GET['mensaje']) && isset($_GET['tipo'])) {
  $mensaje = $_GET['mensaje'];
  $tipo_mensaje = $_GET['tipo'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"
    rel="stylesheet" />
  <link type="text/css" href="./css/index.css" rel="stylesheet" />
  <title>Cambiar Contraseña</title>
</head>

<body>
  <div class="d-flex vh-100 align-items-center justify-content-center">
    <div class="login-box text-center">
      <img src="./img/inicio-de-sesion-de-usuario.png" alt="Logo" class="logo" />
      <h4 class="mb-4">Cambiar Contraseña</h4>
      <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo_mensaje; ?> mb-4">
          <?php echo $mensaje; ?>
        </div>
      <?php endif; ?>
      <?php if ($codigo_valido): ?>
        <form method="POST" action="./procesar_recuperar.php">
          <input type="hidden" name="action" value="cambiar" />
          <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>" />
          <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>" />
          <div class="mb-3">
            <input
              type="password"
              class="form-control form-control-lg"
              id="password"
              name="password"
              placeholder="Nueva contraseña"
              required />
          </div>
          <div class="mb-3">
            <input
              type="password"
              class="form-control form-control-lg"
              id="confirm_password"
              name="confirm_password"
              placeholder="Confirmar contraseña"
              required />
          </div>
          <button type="submit" class="btn btn-primary w-100 mb-3">
            Cambiar Contraseña
          </button>
        </form>
      <?php else: ?>
        <p class="mb-4">
          <?php if (empty($mensaje)): ?>
            El enlace no es válido o ha expirado. Por favor, solicite un nuevo enlace para restablecer su contraseña.
          <?php endif; ?>
        </p>
      <?php endif; ?>
      <p class="link mt-3">
        <a href="./login.php">Volver al login</a>
      </p>
    </div>
  </div>
</body>

</html>