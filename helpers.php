<?php
function registrar_log($usuario, $accion)
{
  $archivo_de_registro = './archivos/log.dat';
  $fecha_hora = date('d/m/Y H:i:s');
  $entrada_de_registro = $fecha_hora . '|' . $usuario . '|' . $accion . PHP_EOL;

  $archivo = fopen($archivo_de_registro, 'a+') or die("No se pudo abrir el archivo de logs");
  fwrite($archivo, $entrada_de_registro);
  fclose($archivo);
}

function cripto_1($len)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
  if ($len > 0 && $len <= 36) {
    return substr(str_shuffle($permitted_chars), 0, $len);
  } else {
    return substr(str_shuffle($permitted_chars), 0, 8);
  }
}

function cripto_2($len)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  if ($len > 0 && $len <= 62) {
    return substr(str_shuffle($permitted_chars), 0, $len);
  } else {
    return substr(str_shuffle($permitted_chars), 0, 8);
  }
}

function cripto_3($len)
{
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $input_length = strlen($permitted_chars);
  $random_string = '';
  for ($i = 0; $i < $len; $i++) {
    $random_character = substr(str_shuffle($permitted_chars), 0, 1);
    $random_string .= $random_character;
  }
  return $random_string;
}

function cripto_4($len)
{
  return bin2hex(random_bytes($len));
}

function cripto_5($len)
{
  return substr(md5(time()), 0, $len);
}

function cripto_6($len)
{
  return substr(sha1(time()), 0, $len);
}
