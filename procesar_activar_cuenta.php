<?php
require_once './helpers.php';

if (isset($_POST['codigo'])) {
  $validar_codigo = (isset($_POST['codigo'])) ? $_POST['codigo'] : '';
  $archivo_de_usuarios = './archivos/Usuarios.dat';
  $archivo = fopen($archivo_de_usuarios, 'r');
  $actualizar_lineas = [];
  $validacion = false;

  while ($linea = fgets($archivo)) {
    $datos_usuario = explode('|', $linea);

    if (trim($datos_usuario[3]) == $validar_codigo) {
      $email = $datos_usuario[1];
      $datos_usuario[4] = date("Y-m-d H:i:s");
      $datos_usuario[5] = date("Y-m-d H:i:s");
      $datos_usuario[6] = 1;
      $linea = implode('|', $datos_usuario);
      $validacion = true;
    }
    $actualizar_lineas[] = $linea;
  }
  fclose($archivo);

  $archivo = fopen($archivo_de_usuarios, 'w');
  foreach ($actualizar_lineas as $actualizar_linea) {
    fwrite($archivo, $actualizar_linea);
  }
  fclose($archivo);

  if (!$validacion) {
    registrar_log($email, "VALIDATE_ACCOUNT_FAIL");
    header('Location: no_validado.php?account_activated=0');
    exit();
  } elseif ($validacion) {
    registrar_log($email, "VALIDATE_ACCOUNT_OK");
    header('Location: login.php?account_activated=1');
    exit();
  }
} else {
  header("Location: ./no_validado.php");
  exit;
}
