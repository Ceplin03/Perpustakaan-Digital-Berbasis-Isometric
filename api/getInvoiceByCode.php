<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

header('Content-Type: application/json');

if (!Auth::check() || Auth::user()['role'] !== 'admin') {
  http_response_code(403);
  echo json_encode(['status'=>'error','message'=>'Akses ditolak']);
  exit;
}

$code = $_GET['code'] ?? '';

$db = (new Database())->connect();

$stmt = $db->prepare("
  SELECT br.id AS borrow_id, br.status, br.borrow_date, br.due_date,
         u.nama, u.id AS user_id,
         b.id AS book_id, b.judul
  FROM borrows br
  JOIN users u ON u.id = br.user_id
  JOIN books b ON b.id = br.book_id
  WHERE br.invoice_code = ?
");
$stmt->execute([$code]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
  echo json_encode(['status'=>'error','message'=>'Invoice tidak ditemukan']);
  exit;
}

echo json_encode(['status'=>'success','data'=>$data]);
