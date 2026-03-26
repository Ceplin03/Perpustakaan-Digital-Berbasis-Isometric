<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

if (!Auth::check()) exit;

$user = Auth::user();
$db = (new Database())->connect();

$stmt = $db->prepare("
  SELECT b.judul, br.borrow_date, br.due_date, br.return_date
  FROM borrows br
  JOIN books b ON b.id = br.book_id
  WHERE br.user_id = ?
  ORDER BY br.borrow_date DESC
");
$stmt->execute([$user['id']]);

echo "<ul>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $status = $row['return_date']
    ? 'Dikembalikan'
    : 'Dipinjam';

  echo "<li>
    <b>{$row['judul']}</b><br>
    Pinjam: {$row['borrow_date']}<br>
    Jatuh Tempo: {$row['due_date']}<br>
    Status: {$status}
  </li><hr>";
}
echo "</ul>";
