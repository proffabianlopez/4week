<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['email']) || !isset($_SESSION['status']) || $_SESSION['status'] != 1) {
  header('Location: ./login.php');
  exit;
}
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$email_usuario = $_SESSION['email'];

// Función para obtener datos adicionales del usuario
function obtener_datos_adicionales($email)
{
  $archivo_de_usuarios = './archivos/Usuarios.dat';
  $datos = [];

  if (file_exists($archivo_de_usuarios)) {
    $archivo = fopen($archivo_de_usuarios, 'r');

    while (!feof($archivo)) {
      $linea = fgets($archivo);
      if (!empty(trim($linea))) {
        $datos_usuario = explode("|", $linea);

        if (count($datos_usuario) >= 7 && trim($datos_usuario[1]) === $email) {
          $datos['nombre'] = trim($datos_usuario[0]);
          $datos['email'] = trim($datos_usuario[1]);
          $datos['fecha_registro'] = trim($datos_usuario[4]);
          $datos['ultima_actividad'] = trim($datos_usuario[5]);
          break;
        }
      }
    }
    fclose($archivo);
  }

  return $datos;
}

// Obtener datos adicionales del usuario
$datos_usuario = obtener_datos_adicionales($email_usuario);

// Función para formatear fecha
function formatear_fecha($fecha_str)
{
  if (empty($fecha_str)) return "No disponible";
  $fecha = date_create($fecha_str);
  if ($fecha) {
    return date_format($fecha, 'd/m/Y H:i');
  }
  return $fecha_str;
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
  <title>Panel de Usuario</title>
</head>

<body>
  <div class="container user-panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Panel de Usuario</h2>
      <a href="./logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
    </div>

    <div class="welcome-banner">
      <h4>Bienvenido(a), <?php echo htmlspecialchars($nombre_usuario); ?>!</h4>
      <p>Email: <?php echo htmlspecialchars($email_usuario); ?></p>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Inicio</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Perfil</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Configuración</button>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="profile-section">
          <h4>Resumen de la cuenta</h4>
          <div class="row mt-4">
            <div class="col-md-6">
              <div class="card mb-3">
                <div class="card-header">Información de la cuenta</div>
                <div class="card-body">
                  <p><strong>Estado:</strong> Activo</p>
                  <p><strong>Última actividad:</strong> <?php echo formatear_fecha($datos_usuario['ultima_actividad'] ?? ''); ?></p>
                  <p><strong>Fecha de registro:</strong> <?php echo formatear_fecha($datos_usuario['fecha_registro'] ?? ''); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="profile-section">
          <h4>Información personal</h4>
          <form class="mt-4">
            <div class="mb-3 row">
              <label for="inputName" class="col-sm-2 col-form-label">Nombre</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="input_name" id="inputName" value="<?php echo htmlspecialchars($nombre_usuario); ?>" required />
              </div>
            </div>
            <div class="mb-3 row">
              <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" name="input_email" id="inputEmail" value="<?php echo htmlspecialchars($email_usuario); ?>" readonly />
              </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button class="btn btn-primary" type="button">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>

      <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <div class="profile-section">
          <h4>Configuración de la cuenta</h4>
          <div class="mt-4">
            <h5 class="mt-4">Cambiar contraseña</h5>
            <form class="mt-3">
              <div class="mb-3">
                <label for="currentPassword" class="form-label">Contraseña actual</label>
                <input type="password" class="form-control" name="current_password" id="currentPassword" required />
              </div>
              <div class="mb-3">
                <label for="newPassword" class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" name="new_password" id="newPassword" required />
              </div>
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirmar nueva contraseña</label>
                <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required />
              </div>
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" type="button">Cambiar contraseña</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>