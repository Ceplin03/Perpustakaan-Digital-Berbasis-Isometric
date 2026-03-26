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

// Ambil data pinjaman
$stmt = $db->prepare("
  SELECT due_date FROM borrows WHERE id=?
");
$stmt->execute([$borrowId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
  echo json_encode(['status'=>'error','message'=>'Data tidak ditemukan']);
  exit;
}

$today = new DateTime();
$due   = new DateTime($data['due_date']);

$lateDays = 0;
$status   = 'returned';

if ($today > $due) {
  $lateDays = $due->diff($today)->days;
}

// Update borrows
$stmt = $db->prepare("
  UPDATE borrows SET
    return_date = CURDATE(),
    late_days = ?,
    status = ?
  WHERE id = ?
");
$stmt->execute([$lateDays, $status, $borrowId]);

// Update status buku
$stmt = $db->prepare("
  UPDATE books SET status='available'
  WHERE id = ?
");
$stmt->execute([$bookId]);

echo json_encode([
  'status'=>'success',
  'late_days'=>$lateDays
]);
