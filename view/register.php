<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h1>Registrar</h1>
        <form action="../processes/process_register.php" method="POST" class="login-form">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Nombre Completo" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input value="@gmail.com" type="email" name="email" placeholder="Correo Electrónico" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <button type="submit" class="btn-primary">Registrar</button>
        </form>
        <div class="links">
            <a href="login.php">Iniciar Sesión</a>
        </div>
    </div>
</body>
</html>
