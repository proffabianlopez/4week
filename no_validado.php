<?php
$mensaje = '';
$tipo_mensaje = '';

if (isset($_GET['account_activated']) && $_GET['account_activated'] == '0') {
  $mensaje = 'La cuenta no se ha podido activar.';
  $tipo_mensaje = 'success';
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
  <title>Usuario Inactivo</title>
</head>

<body>
  <div class="d-flex vh-100 align-items-center justify-content-center">
    <div class="login-box text-center">
      <h4 class="mb-4">Activar cuenta</h4>
      <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?php echo $tipo_mensaje; ?> mb-4">
          <?php echo $mensaje; ?>
        </div>
      <?php endif; ?>
      <form method="POST" action="./procesar_activar_cuenta.php">
        <div class="mb-3">
          <input
            type="text"
            class="form-control form-control-lg"
            id="codigo"
            name="codigo"
            placeholder="Código de Validación"
            autocomplete="off"
            required />
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">
          Activar cuenta
        </button>
      </form>
      <div class="link">
        <a href="./login.php">Volver al login</a>
      </div>
    </div>
  </div>
</body>

</html>