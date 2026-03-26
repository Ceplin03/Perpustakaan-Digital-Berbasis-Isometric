<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

header('Content-Type: application/json');

if (!Auth::check()) {
    echo json_encode(['status'=>'error','message'=>'Silakan login terlebih dahulu']);
    exit;
}

$user = Auth::user();
$db = (new Database())->connect();
$userId = $user['id'];
$bookId = $_POST['book_id'] ?? 0;

/* 1️⃣ CEK JUMLAH PINJAMAN AKTIF */
$stmt = $db->prepare("
  SELECT COUNT(*) FROM borrows
  WHERE user_id = ? AND return_date IS NULL
");
$stmt->execute([$userId]);
$activeBorrows = $stmt->fetchColumn();

if ($activeBorrows >= 2) {
    echo json_encode([
      'status'=>'warning',
      'message'=>'Maksimal peminjaman adalah 2 buku'
    ]);
    exit;
}

/* 2️⃣ CEK PINJAMAN TELAT */
$stmt = $db->prepare("
  SELECT COUNT(*) FROM borrows
  WHERE user_id = ? AND return_date IS NULL AND due_date < CURDATE()
");
$stmt->execute([$userId]);

if ($stmt->fetchColumn() > 0) {
    echo json_encode([
      'status'=>'warning',
      'message'=>'Kamu memiliki buku yang terlambat dikembalikan'
    ]);
    exit;
}

/* 3️⃣ CEK STATUS BUKU */
$stmt = $db->prepare("
  SELECT status FROM books WHERE id = ?
");
$stmt->execute([$bookId]);
$bookStatus = $stmt->fetchColumn();

if ($bookStatus !== 'available') {
    echo json_encode([
      'status'=>'error',
      'message'=>'Buku sedang dipinjam orang lain'
    ]);
    exit;
}

/* 4️⃣ SIMPAN PEMINJAMAN */
$borrowDate = date('Y-m-d');
$dueDate = date('Y-m-d', strtotime('+7 days'));

$stmt = $db->prepare("
  INSERT INTO borrows (user_id, book_id, borrow_date, due_date)
  VALUES (?, ?, ?, ?)
");
$stmt->execute([$userId, $bookId, $borrowDate, $dueDate]);

/* 5️⃣ UPDATE STATUS BUKU */
$stmt = $db->prepare("
  UPDATE books SET status='borrowed' WHERE id=?
");
$stmt->execute([$bookId]);

echo json_encode([
  'status'=>'success',
  'message'=>'Buku berhasil dipinjam',
  'due_date'=>$dueDate
]);
