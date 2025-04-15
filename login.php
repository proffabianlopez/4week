<?php
$mensaje = '';
$tipo_mensaje = '';
if (isset($_GET['password_changed']) && $_GET['password_changed'] == '1') {
  $mensaje = 'La contraseña se ha cambiado correctamente. Ya puedes iniciar sesión con tu nueva contraseña.';
  $tipo_mensaje = 'success';
}

if (isset($_GET['account_activated']) && $_GET['account_activated'] == '1') {
  $mensaje = 'La cuenta se ha activado correctamente.';
  $tipo_mensaje = 'success';
}

$error_login = '';
if (isset($_GET['error']) && $_GET['error'] == '1') {
  $error_login = "Usuario o contraseña incorrectos";
}

$email = isset($_GET['email']) ? $_GET['email'] : '';
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
  <title>Login</title>
</head>

<body>
  <div class="d-flex vh-100 align-items-center justify-content-center">
    <div class="login-box text-center">
      <img src="./img/inicio-de-sesion-de-usuario.png" alt="Logo" class="logo" />
      <h4 class="mb-4">Iniciar sesión</h4>
      <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo_mensaje; ?> mb-4">
          <?php echo $mensaje; ?>
        </div>
      <?php endif; ?>
      <form method="POST" action="./procesar_login.php">
        <div class="mb-3">
          <input
            type="email"
            class="form-control form-control-lg"
            id="email"
            name="email"
            placeholder="Correo electrónico"
            autocomplete="off"
            value="<?php echo htmlspecialchars($email); ?>"
            required />
        </div>
        <div class="mb-3">
          <input
            type="password"
            class="form-control form-control-lg"
            id="password"
            name="password"
            placeholder="Contraseña"
            autocomplete="off"
            required />
        </div>
        <div class="form-check text-start mb-3">
          <input
            class="form-check-input"
            type="checkbox"
            id="remember"
            name="remember" />
          <label class="form-check-label" for="remember">Recordar contraseña</label>
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">
          Ingresar
        </button>
      </form>
      <?php if (!empty($error_login)): ?>
        <div id="login_error" class="alert alert-danger mb-3">
          <?php echo $error_login; ?>
        </div>
      <?php endif; ?>
      <p class="link mb-2">
        <a href="./recuperar.php">
          ¿Olvidaste tu contraseña?
        </a>
      </p>
      <p class="link">
        ¿No tenés cuenta? <a href="./register.html">Registrate</a>
      </p>
    </div>
  </div>
</body>

</html>