<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2>¿Olvidaste tu contraseña?</h2>
    <p>Ingresá tu correo electrónico y recibirás un código para reestablecerla.</p>
    <form action="includes/recuperar_password.php" method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-warning">Enviar solicitud</button>
    </form>
    <hr>
    <a href="index.php">Volver al Login</a>
  </div>
</body>
</html>
