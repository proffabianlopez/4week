<?php
$mensaje = '';
$tipo_mensaje = '';

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
      <form method="POST" action="./procesar_recuperar.php">
        <input type="hidden" name="action" value="cambiar" />
        <div class="mb-3">
          <input
            type="email"
            class="form-control form-control-lg"
            id="email"
            name="email"
            placeholder="mimail@email.com"
            autocomplete="off"
            required />
        </div>
        <div class="mb-3">
          <input
            type="password"
            class="form-control form-control-lg"
            id="password"
            name="password"
            placeholder="Nueva contraseña"
            autocomplete="off"
            required />
        </div>
        <div class="mb-3">
          <input
            type="password"
            class="form-control form-control-lg"
            id="confirm_password"
            name="confirm_password"
            placeholder="Confirmar contraseña"
            autocomplete="off"
            required />
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">
          Cambiar Contraseña
        </button>
      </form>
      <p class="link mt-3">
        <a href="./login.php">Volver al login</a>
      </p>
    </div>
  </div>
</body>

</html>