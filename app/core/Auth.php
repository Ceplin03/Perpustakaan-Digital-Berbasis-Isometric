<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

class Auth {

  public static function check() {
    return isset($_SESSION['user']);
  }

  public static function user() {
    return $_SESSION['user'] ?? null;
  }

  public static function logout() {
    session_destroy();
    header("Location: /perpustakaan/public/login.php");
    exit;
  }
}
