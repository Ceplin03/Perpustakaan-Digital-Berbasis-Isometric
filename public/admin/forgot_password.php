<?php
require_once '../app/controllers/AuthController.php';

$auth = new AuthController();
$auth->forgotPassword();

$success = $success ?? '';
$error   = $error ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $error = 'Email dan password wajib diisi.';
    } else {
        
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = 'Email tidak ditemukan.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $upd = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
            $upd->execute([$hash, $email]);

            $success = 'Password berhasil direset. Silakan login kembali.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lupa Password | Smart Library</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
body{
  background:#f8fafc;
  font-family:Inter,sans-serif;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
  margin:0;
}
.card{
  background:#fff;
  padding:40px;
  width:100%;
  max-width:420px;
  border-radius:16px;
  box-shadow:0 10px 25px rgba(0,0,0,.1);
}
h2{text-align:center;margin-bottom:8px}
p{text-align:center;color:#64748b;font-size:.9rem}
input{
  width:100%;
  padding:12px;
  border-radius:8px;
  border:1px solid #e2e8f0;
  margin-bottom:16px;
}
button{
  width:100%;
  padding:12px;
  background:#2563eb;
  color:#fff;
  border:none;
  border-radius:8px;
  font-weight:600;
  cursor:pointer;
}
.success{
  background:#ecfeff;
  color:#065f46;
  padding:12px;
  border-radius:8px;
  margin-bottom:16px;
  text-align:center;
}
.error{
  background:#fef2f2;
  color:#b91c1c;
  padding:12px;
  border-radius:8px;
  margin-bottom:16px;
  text-align:center;
}
.password-wrapper{
  position:relative;
}
.toggle-password{
  position:absolute;
  right:12px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
}
a{
  display:block;
  text-align:center;
  margin-top:16px;
  color:#2563eb;
  text-decoration:none;
}
</style>
</head>

<body>

<div class="card">
    <h2>🔑 Reset Password</h2>
    <p>Masukkan email dan password baru</p>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
        <a href="login.php">← Kembali ke Login</a>
    <?php else: ?>

        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email terdaftar" required>

            <div class="password-wrapper">
                <input type="password" id="newpass" name="password" placeholder="Password baru" required>
                <span class="toggle-password" onclick="toggle()">👁️</span>
            </div>

            <button type="submit">Reset Password</button>
        </form>

        <a href="login.php">← Kembali ke Login</a>

    <?php endif; ?>
</div>

<script>
function toggle(){
  const p=document.getElementById('newpass');
  p.type=p.type==='password'?'text':'password';
}
</script>

</body>
</html>
