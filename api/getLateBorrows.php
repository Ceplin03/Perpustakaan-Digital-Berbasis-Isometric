<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

if (!Auth::check() || Auth::user()['role'] !== 'admin') exit;

$db = (new Database())->connect();

$stmt = $db->query("
  SELECT u.nama, b.judul, br.due_date
  FROM borrows br
  JOIN users u ON u.id = br.user_id
  JOIN books b ON b.id = br.book_id
  WHERE br.return_date IS NULL
  AND br.due_date < CURDATE()
");

echo "<h3>Buku Terlambat</h3>";
echo "<ul>";
foreach ($stmt as $row) {
  echo "<li>
    <b>{$row['nama']}</b> – {$row['judul']}<br>
    Jatuh tempo: {$row['due_date']}
  </li><hr>";
}
echo "</ul>";
