<?php
require_once '../app/config/database.php';
$db=(new Database())->connect();
$q=$db->query("SELECT b.judul,COUNT(*) c FROM borrows br JOIN books b ON b.id=br.book_id GROUP BY b.id ORDER BY c DESC LIMIT 5");
foreach($q as $b) echo "<p>{$b['judul']} 🔥</p>";
