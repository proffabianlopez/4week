<?php
// C:\xampp\htdocs\4week\includes\funciones.php

// Lee la configuración del sistema desde Config.dat
function leer_config() {
    $ruta = __DIR__ . '/../datos/Config.dat';
    if (!file_exists($ruta)) {
        return false;
    }
    $linea = file_get_contents($ruta);
    list($correo, $token, $max_intentos) = explode('|', trim($linea));
    return [
        'correo' => $correo,
        'token' => $token,
        'max_intentos' => (int)$max_intentos
    ];
}

function escribir_log($mensaje) {
    $fecha = date('[Y-m-d H:i:s]');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $ruta = __DIR__ . '/../datos/Log.dat';
    file_put_contents($ruta, "$fecha - $mensaje - IP: $ip" . PHP_EOL, FILE_APPEND);
}

function leer_usuarios() {
    $ruta = __DIR__ . '/../datos/Usuarios.dat';
    if (!file_exists($ruta)) {
        return [];
    }
    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lista = [];
    foreach ($lineas as $linea) {
        $datos = explode('|', $linea);
        $lista[] = $datos;
    }
    return $lista;
}

function guardar_usuarios($lista) {
    $ruta = __DIR__ . '/../datos/Usuarios.dat';
    $lineas = [];
    foreach ($lista as $usuario) {
        $lineas[] = implode('|', $usuario);
    }
    file_put_contents($ruta, implode(PHP_EOL, $lineas) . PHP_EOL);
}
?>
