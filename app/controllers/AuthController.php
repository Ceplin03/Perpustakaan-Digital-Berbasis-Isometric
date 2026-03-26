<?php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    /**
     * REGISTER SISWA
     * - NIS disimpan ke kolom users.id
     * - Role otomatis: siswa
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id       = trim($_POST['id'] ?? '');
        $nama     = trim($_POST['nama'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$id || !$nama || !$email || !$password) {
            $GLOBALS['register_error'] = 'Data tidak lengkap.';
            return;
        }

        $userModel = new User();

        if ($userModel->findById($id)) {
            $GLOBALS['register_error'] = 'NIS sudah terdaftar.';
            return;
        }

        if ($userModel->findByEmail($email)) {
            $GLOBALS['register_error'] = 'Email sudah digunakan.';
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userModel->create([
            'id'       => $id,
            'nama'     => $nama,
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => 'siswa'
        ]);

        $GLOBALS['register_success'] = 'Registrasi berhasil! Silakan login.';
    }

    /**
     * LOGIN
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        // Pastikan session dimulai di awal proses login
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $GLOBALS['login_error'] = 'Email dan password wajib diisi.';
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $GLOBALS['login_error'] = 'Email atau password salah.';
            return;
        }

        // Simpan ke session
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'nama'  => $user['nama'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        $GLOBALS['login_success'] = 'Selamat datang, ' . $user['nama'] . ' 👋';
    }



    /**
     * LOGOUT
     */
    public function logout()
    {
        session_start();
        session_destroy();

        header("Location: /perpustakaan/public/login.php");
        exit;
    }
    /**
 * LUPA / RESET PASSWORD (MANDIRI)
 */
public function forgotPassword()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $GLOBALS['error'] = 'Email dan password baru wajib diisi.';
        return;
    }

    $userModel = new User();
    $user = $userModel->findByEmail($email);

    if (!$user) {
        $GLOBALS['error'] = 'Email tidak ditemukan.';
        return;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $userModel->updatePasswordByEmail($email, $hashedPassword);

    $GLOBALS['success'] = 'Password berhasil direset. Silakan login kembali.';
}

}
