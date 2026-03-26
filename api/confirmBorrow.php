<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

header('Content-Type: application/json');

if (!Auth::check() || Auth::user()['role'] !== 'admin') {
  echo json_encode(['status'=>'error','message'=>'Akses ditolak']);
  exit;
}

$borrowId = $_POST['borrow_id'] ?? 0;
$bookId   = $_POST['book_id'] ?? 0;

$db = (new Database())->connect();

// Pastikan status masih reserved
$stmt = $db->prepare("SELECT status FROM borrows WHERE id=?");
$stmt->execute([$borrowId]);
if ($stmt->fetchColumn() !== 'reserved') {
  echo json_encode(['status'=>'error','message'=>'Status tidak valid']);
  exit;
}

// Update borrows
$stmt = $db->prepare("
  UPDATE borrows SET status='borrowed'
  WHERE id=?
");
$stmt->execute([$borrowId]);

// Update buku
$stmt = $db->prepare("
  UPDATE books SET status='borrowed'
  WHERE id=?
");
$stmt->execute([$bookId]);

echo json_encode(['status'=>'success','message'=>'Buku berhasil diserahkan']);
