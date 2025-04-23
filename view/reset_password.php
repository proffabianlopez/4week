<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h1>Cambiar Contraseña</h1>
        <form action="../processes/process_reset_password.php?email='ivo@gmail.com'" method="POST" class="login-form">
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="newPassword" placeholder="Nueva contraseña" required>
            </div>
            <button type="submit" class="btn-primary">Cambiar contraseña</button>
        </form>
        <div class="links">
            <a href="login.php">Iniciar Sesión</a> | 
            <a href="register.php">Registrar</a>
        </div>
    </div>
</body>
</html>
