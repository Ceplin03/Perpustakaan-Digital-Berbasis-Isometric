<?php
require_once '../app/config/database.php';
$db=(new Database())->connect();
$q=$db->query("SELECT b.judul FROM books b LEFT JOIN borrows br ON b.id=br.book_id GROUP BY b.id HAVING COUNT(br.id)<1");
foreach($q as $b) echo "<p>{$b['judul']}</p>";
