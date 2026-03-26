<?php
require_once '../app/controllers/AuthController.php';
$auth = new AuthController();

// Kita menggunakan output buffering agar header redirect dari controller 
// bisa kita cegah sebentar untuk menampilkan SweetAlert
ob_start();
$auth->register();
$output = ob_get_clean();

// Jika ada redirect dari controller, berarti register sukses
$is_success = false;
if (isset($output) && strpos($output, 'Location') !== false || (isset($_SERVER['HTTP_REFERER']) && $_SERVER['REQUEST_METHOD'] === 'POST' && !isset($GLOBALS['error']))) {
    // Note: Karena controller Anda menggunakan header(), kita tangani suksesnya di JS
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Smart Library</title>
    
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

        /* Theme Toggle Button */
        .theme-switch {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card {
            background: var(--surface);
            padding: 40px;
            width: 100%;
            max-width: 450px;
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

        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { 
            font-size: 1.75rem; font-weight: 700; 
            background: linear-gradient(to right, var(--text-main), var(--text-dim));
            background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .header p { color: var(--text-dim); font-size: 0.9rem; margin-top: 5px; }

        .input-group { margin-bottom: 18px; }
        label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 8px; color: var(--text-dim); }

        input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background-color: rgba(128, 128, 128, 0.05);
            font-size: 0.95rem;
            color: var(--text-main);
            outline: none;
            font-family: var(--font-main);
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
            cursor: pointer; margin-top: 15px; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        button:hover { background: var(--primary-hover); transform: translateY(-2px); }

        .footer { text-align: center; margin-top: 25px; font-size: 0.85rem; color: var(--text-dim); }
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
        <h2>📝 Daftar Akun</h2>
        <p>Bergabunglah dengan Smart Library</p>
    </div>

    <form method="POST" id="registerForm">
        <div class="input-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Budi Santoso" required>
        </div>

        <div class="input-group">
            <label>NIS (Nomor Induk Siswa)</label>
            <input type="number" name="id" placeholder="Contoh: 2223101" required>
        </div>

        <div class="input-group">
            <label>Alamat Email</label>
            <input type="email" name="email" placeholder="nama@email.com" required>
        </div>
        
        <div class="input-group">
            <label>Buat Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <span class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eye-icon"></i>
                </span>
            </div>
        </div>

        <button type="submit">DAFTAR SEKARANG</button>
    </form>

    <div class="footer">
        Sudah punya akun? <a href="login.php">Masuk di sini</a>
    </div>
</div>

    <script>
        // 1. Fungsi Toggle Dark/Light Mode
        function toggleTheme() {
            const body = document.body;
            const icon = document.getElementById('theme-icon');
            if (body.getAttribute('data-theme') === 'light') {
                body.setAttribute('data-theme', 'dark');
                icon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('theme', 'dark');
            } else {
                body.setAttribute('data-theme', 'light');
                icon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('theme', 'light');
            }
        }

        // Cek preferensi tema user di local storage
        if (localStorage.getItem('theme') === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
            document.getElementById('theme-icon').classList.replace('fa-moon', 'fa-sun');
        }

        // 2. Fungsi Toggle Show/Hide Password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
        
    </script>
    <?php if (isset($GLOBALS['register_error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Registrasi Gagal',
    text: '<?= htmlspecialchars($GLOBALS['register_error']) ?>',
    confirmButtonColor: '#4f46e5'
});
</script>
<?php endif; ?>

<?php if (isset($GLOBALS['register_success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil 🎉',
    text: '<?= htmlspecialchars($GLOBALS['register_success']) ?>',
    confirmButtonColor: '#4f46e5',
    timer: 2500,
    timerProgressBar: true,
    showConfirmButton: false
}).then(() => {
    window.location.href = 'login.php';
});
</script>
<?php endif; ?>

</body>
</html>