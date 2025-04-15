<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow-lg">
      <div class="card-body">
        <h3 class="card-title">¡Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?>!</h3>
        <p class="card-text">Has iniciado sesión correctamente.</p>
        <hr>
        <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><strong>Última actividad:</strong> <?= htmlspecialchars($usuario['ultima_actividad']) ?></p>
        <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
      </div>
    </div>
  </div>
</body>
</html>
