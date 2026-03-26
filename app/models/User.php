<?php
require_once __DIR__ . '/../config/database.php';

class User {
  private $db;

  public function __construct() {
    $this->db = (new Database())->connect();
  }

  public function create($data){
  $stmt = $this->db->prepare("
    INSERT INTO users (id, nama, email, password, role)
    VALUES (?, ?, ?, ?, ?)
  ");
  return $stmt->execute([
    $data['id'],
    $data['nama'],
    $data['email'],
    $data['password'],
    $data['role']
  ]);
}

public function findById($id){
  $stmt = $this->db->prepare("SELECT * FROM users WHERE id=?");
  $stmt->execute([$id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);

}


  public function findByEmail($email) {
    $stmt = $this->db->prepare(
      "SELECT * FROM users WHERE email = ?"
    );
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updatePasswordByEmail($email, $hashedPassword)
{
    $stmt = $this->db->prepare(
        "UPDATE users SET password = ? WHERE email = ?"
    );
    return $stmt->execute([$hashedPassword, $email]);
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
