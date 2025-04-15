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
        <h1>Recuperar Contraseña</h1>
        <form action="../processes/process_recover_password.php" method="POST" class="login-form">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Correo Electrónico" required>
            </div>
            <button type="submit" class="btn-primary">Enviar</button>
        </form>
        <div class="links">
            <a href="login.php">Iniciar Sesión</a> | 
            <a href="register.php">Registrar</a>
        </div>
    </div>
</body>
</html>
