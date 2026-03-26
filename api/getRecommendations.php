<?php
require_once '../app/config/database.php';
session_start();

$db = (new Database())->connect();

$stmt = $db->query("
  SELECT b.id, b.judul, b.kategori
  FROM books b
  JOIN borrows br ON br.book_id = b.id
  GROUP BY b.id
  ORDER BY COUNT(br.id) DESC
  LIMIT 5
");

$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<ul class='book-list'>";
foreach ($books as $b) {
  echo "
    <li onclick=\"openBookDetail({$b['id']})\">
      <strong>{$b['judul']}</strong><br>
      <small>{$b['kategori']}</small>
    </li>
  ";
}
echo "</ul>";
