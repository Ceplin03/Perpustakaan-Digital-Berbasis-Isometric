<?php
require_once '../app/config/database.php';
$db=(new Database())->connect();
$q=$db->query("SELECT b.judul FROM borrows br JOIN books b ON b.id=br.book_id WHERE return_date IS NULL LIMIT 5");
foreach($q as $b) echo "<p>{$b['judul']}</p>";
