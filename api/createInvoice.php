<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

header('Content-Type: application/json');

if (!Auth::check()) {
  echo json_encode(['status'=>'error','message'=>'Login required']);
  exit;
}

$db = (new Database())->connect();
$user = Auth::user();

$bookId = $_POST['book_id'] ?? 0;
$days   = (int)($_POST['days'] ?? 1);
$days   = max(1, min(7, $days)); // clamp 1-7

// Cek buku tersedia
$stmt = $db->prepare("SELECT status FROM books WHERE id=?");
$stmt->execute([$bookId]);
if ($stmt->fetchColumn() !== 'available') {
  echo json_encode(['status'=>'error','message'=>'Buku tidak tersedia']);
  exit;
}

// Generate invoice code
$invoiceCode = 'INV-' . date('Ymd') . '-' . rand(1000,9999);

$borrowDate = date('Y-m-d');
$dueDate    = date('Y-m-d', strtotime("+$days days"));

// Simpan reservasi
$stmt = $db->prepare("
  INSERT INTO borrows (user_id, book_id, borrow_date, due_date, status, invoice_code)
  VALUES (?, ?, ?, ?, 'reserved', ?)
");
$stmt->execute([$user['id'], $bookId, $borrowDate, $dueDate, $invoiceCode]);

$borrowId = $db->lastInsertId();

// Ambil data buku
$stmt = $db->prepare("SELECT judul FROM books WHERE id=?");
$stmt->execute([$bookId]);
$judul = $stmt->fetchColumn();

echo json_encode([
  'status' => 'success',
  'invoice' => [
    'code' => $invoiceCode,
    'borrow_id' => $borrowId,
    'nama' => $user['nama'],
    'user_id' => $user['id'],
    'judul' => $judul,
    'borrow_date' => $borrowDate,
    'due_date' => $dueDate,
    'status' => 'Menunggu pengambilan'
  ]
]);
