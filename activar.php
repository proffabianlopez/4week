<?php
require_once __DIR__ . '/includes/funciones.php';

$email = $_GET['email'] ?? '';
$codigo = $_GET['codigo'] ?? '';

if (empty($email) || empty($codigo)) {
    echo "Faltan parámetros para activar la cuenta.";
    escribir_log("ACTIVACIÓN fallida - Parámetros incompletos");
    exit;
}

$usuarios = leer_usuarios();
$encontrado = false;

foreach ($usuarios as &$usuario) {
    if ($usuario[0] === $email && $usuario[4] == $codigo && $usuario[7] == 0) {
        $usuario[7] = 1; 
        $usuario[6] = date('Y-m-d H:i:s'); 
        $encontrado = true;
        break;
    }
}

if ($encontrado) {
    guardar_usuarios($usuarios);
    escribir_log("ACTIVACIÓN exitosa - $email");
    echo "<h2>Cuenta activada correctamente</h2>";
    echo "<p>Ahora podés iniciar sesión con tu email y contraseña.</p>";
    echo "<a href='index.php'>Ir al login</a>";
} else {
    escribir_log("ACTIVACIÓN fallida - $email - Código: $codigo");
    echo "<h2>Error al activar la cuenta</h2>";
    echo "<p>El email o código no coinciden, o la cuenta ya fue activada.</p>";
    echo "<a href='index.php'>Volver al inicio</a>";
}
?>
