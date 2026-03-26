<?php
require_once '../app/config/database.php';

$db = (new Database())->connect();

$stmt = $db->query("
  SELECT rack_code, COUNT(br.id) AS total
  FROM books b
  LEFT JOIN borrows br ON br.book_id = b.id
  GROUP BY rack_code
");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
