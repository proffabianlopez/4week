<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Codigo</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h1>Validar Codigo</h1>
        <form action="../processes/process_validate_code.php" method="POST" class="login-form">
            <div class="input-group">
                <input type="text" name="validateCode" placeholder="Código de Validación" required>
            </div>
            <button type="submit" class="btn-primary">Validar</button>
        </form>
        <div class="links">
            <a href="register.php">Volver a formulario de registro</a>
        </div>
    </div>
</body>
</html>