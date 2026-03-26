<?php
require_once '../app/core/Auth.php';

if (Auth::check()) {
  header("Location: /perpustakaan/public/library-room/");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Smart Library</title>
<style>
body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg, #020617, #0f172a);
  color: #fff;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.container {
  text-align: center;
  max-width: 480px;
}

h1 {
  font-size: 42px;
  margin-bottom: 16px;
}

p {
  opacity: .85;
  margin-bottom: 32px;
}

.actions a {
  display: inline-block;
  padding: 14px 28px;
  margin: 0 8px;
  border-radius: 10px;
  background: #fff;
  color: #000;
  text-decoration: none;
  font-weight: bold;
}

.actions a.secondary {
  background: transparent;
  border: 2px solid #fff;
  color: #fff;
}
</style>
</head>
<body>

<div class="container">
  <h1>📚 Smart Library</h1>
  <p>Perpustakaan digital interaktif berbasis visual isometric</p>

  <div class="actions">
    <a href="login.php">Login</a>
    <a href="register.php" class="secondary">Register</a>
  </div>
</div>

</body>
</html>
