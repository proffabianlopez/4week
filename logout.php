<?php
session_start();

session_unset();
session_destroy();

// Eliminar cookies de "recordar contraseña" si existen
if (isset($_COOKIE['remember_user'])) {
  setcookie("remember_user", "", time() - 3600, "/", "", true, true);
}
if (isset($_COOKIE['remember_token'])) {
  setcookie("remember_token", "", time() - 3600, "/", "", true, true);
}

header('Location: ./login.php');
exit;
