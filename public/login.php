<?php
require_once '../app/controllers/AuthController.php';

$auth = new AuthController();
$auth->login();

$loginError   = $GLOBALS['login_error'] ?? null;
$loginSuccess = $GLOBALS['login_success'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Smart Library</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg: #05070a;
            --surface: #0c0f16;
            --border: rgba(255, 255, 255, 0.08);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --font-main: 'Poppins', sans-serif;
        }

        [data-theme="light"] {
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: rgba(0, 0, 0, 0.08);
            --text-main: #1e293b;
            --text-dim: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; transition: background 0.3s, color 0.3s; }

        body {
            background-color: var(--bg);
            background-image: radial-gradient(circle at 50% -20%, #1e293b 0%, var(--bg) 80%);
            font-family: var(--font-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-main);
        }

        .theme-switch {
            position: fixed; top: 20px; right: 20px;
            background: var(--surface); border: 1px solid var(--border);
            padding: 10px; border-radius: 50%; cursor: pointer;
            width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;
            color: var(--text-main); z-index: 1000;
        }

        .card {
            background: var(--surface);
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header { text-align: center; margin-bottom: 35px; }
        .header h2 { 
            font-size: 1.75rem; font-weight: 700;
            background: linear-gradient(to right, var(--text-main), var(--text-dim));
            background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .header p { color: var(--text-dim); font-size: 0.9rem; margin-top: 10px; }

        .input-group { margin-bottom: 24px; }
        label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 8px; color: var(--text-dim); }

        input {
            width: 100%; padding: 14px 16px; border-radius: 12px;
            border: 1px solid var(--border); background-color: rgba(128, 128, 128, 0.05);
            font-size: 0.95rem; color: var(--text-main); outline: none; font-family: var(--font-main);
        }

        input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); }

        .password-wrapper { position: relative; }
        .toggle-password {
            position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: var(--text-dim); opacity: 0.7;
        }

        button {
            width: 100%; padding: 14px; background: var(--primary); color: #fff;
            border: none; border-radius: 12px; font-size: 1rem; font-weight: 600;
            cursor: pointer; margin-top: 12px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        button:hover { background: var(--primary-hover); transform: translateY(-2px); }

        .footer { text-align: center; margin-top: 30px; font-size: 0.85rem; color: var(--text-dim); }
        .footer a { color: var(--primary); text-decoration: none; font-weight: 600; }

        .swal2-popup { font-family: var(--font-main) !important; border-radius: 20px !important; }
    </style>
</head>
<body data-theme="dark">

<button class="theme-switch" onclick="toggleTheme()">
    <i class="fas fa-moon" id="theme-icon"></i>
</button>

<div class="card">
    <div class="header">
        <h2>📚 Smart | Library</h2>
        <p>Masuk ke portal digital</p>
    </div>

    <form method="POST" id="loginForm">
        <div class="input-group">
            <label>Alamat Email</label>
            <input type="email" name="email" placeholder="nama@email.com" required>
        </div>
        
        <div class="input-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <span class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eye-icon"></i>
                </span>
            </div>
        </div>

        <button type="submit">MASUK</button>
    </form>

    <div class="footer">
        <a href="forgot_password.php">Lupa akses masuk?</a><br>
        Belum terdaftar? <a href="register.php">Buat akun baru</a>
    </div>
</div>

<script>

    // Toggle tema terang/gelap
    function toggleTheme() {
        const body = document.body;
        const icon = document.getElementById('theme-icon');
        if (body.getAttribute('data-theme') === 'dark') {
            body.setAttribute('data-theme', 'light');
            icon.classList.replace('fa-moon', 'fa-sun');
        } else {
            body.setAttribute('data-theme', 'dark');
            icon.classList.replace('fa-sun', 'fa-moon');
        }
    }

    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eye-icon");
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.innerHTML = '<path d="M9.88 9.88L2 12s3-7 10-7a12.39 12.39 0 0 1 4.63 1.24M23 12s-3 7-10 7a12.41 12.41 0 0 1-4.63-1.24M1 1l22 22"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>';
        } else {
            passwordInput.type = "password";
            eyeIcon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    // Eksekusi SweetAlert
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($loginError): ?>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: '<?= addslashes($loginError) ?>',
            confirmButtonColor: '#3b82f6'
        });
        <?php endif; ?>

        <?php if ($loginSuccess): ?>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil',
            text: '<?= addslashes($loginSuccess) ?>',
            confirmButtonColor: '#3b82f6',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.href = '/perpustakaan/public/library-room/';
        });
        <?php endif; ?>
    });
</script>

</body>
</html>