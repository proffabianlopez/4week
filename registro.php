<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Registro de nuevo usuario</h2>
    <form action="includes/procesar_registro.php" method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Apellido</label>
        <input type="text" name="apellido" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required minlength="6">
      </div>
      <div class="mb-3">
        <label>Confirmar contraseña</label>
        <input type="password" name="confirmar" class="form-control" required minlength="6">
      </div>
      <button type="submit" class="btn btn-success">Registrarse</button>
    </form>
    <hr>
    <a href="index.php">Volver al Login</a>
  </div>
</body>
</html>
