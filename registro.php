<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5" style="max-width: 400px;">
        <h2 class="text-center mb-4">Bienvenido</h2>
        <h4 class="text-center mb-4">Registrate</h4>
        <form action="./email.php" method="POST">
            <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Apellido</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="d-grid mb-3">
                    <input type="submit" value="enviar" name="enviar" class="btn btn-primary">
                </div>
        </form>
    </div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_user = "../file/usuarios.dat";
    $user = $_POST["email"];
    $pass = $_POST["password"];
    $flag_user = 0;
    $usuario_encontrado = false;

    if (isset($_POST['iniciosesion'])) {
        if (!file_exists($data_user)) {
            echo "<p style='color:red;'>Archivo de usuarios no encontrado.</p>";
            exit;
        }

        $archivo = fopen($data_user, 'r') or die("No puedo abrir archivo de datos para lectura");
        fgets($archivo); // Saltar encabezado

        while (!feof($archivo)) {
            $linea = fgets($archivo);
            if (trim($linea) == "") continue; // Ignora líneas vacías

            $datos = explode("|", $linea);
            if (count($datos) < 5) continue; // Validar formato

            $users = trim($datos[0]);
            $passs = trim($datos[3]);
            $active = trim($datos[4]);

            if ($users === $user) {
                $usuario_encontrado = true;

                if ($pass === $passs) {
                    if ($active === "0") {
                        fclose($archivo);
                        header("Location: login.php?error=cuenta_inactiva");
                        exit;
                    } else {
                        fclose($archivo);
                        header("Location: principal.html");
                        exit;
                    }
                } else {
                    fclose($archivo);
                    header("Location: login.php?error=contrasena_incorrecta");
                    exit;
                }
            }
        }

        fclose($archivo);

        if (!$usuario_encontrado) {
            echo "<p style='color:red;'>Usuario no registrado.</p>";
        }
    }

    if (isset($_POST['registrarse'])) {
        header("Location: registro.php");
        exit;
    }
}
?>
</body>
</html>
