<?php $email = $_GET['email'] ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reestablecer Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2>Reestablecer contraseña</h2>
    <form action="includes/resetear_password.php" method="POST">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
      <div class="mb-3">
        <label>Código de verificación</label>
        <input type="text" name="codigo" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Nueva contraseña</label>
        <input type="password" name="password" class="form-control" required minlength="6">
      </div>
      <div class="mb-3">
        <label>Confirmar nueva contraseña</label>
        <input type="password" name="confirmar" class="form-control" required minlength="6">
      </div>
      <button type="submit" class="btn btn-success">Actualizar contraseña</button>
    </form>
  </div>
</body>
</html>
