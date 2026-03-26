<?php
require_once '../app/config/database.php';
$db=(new Database())->connect();
$q=$db->query("SELECT judul FROM books ORDER BY created_at DESC LIMIT 5");
foreach($q as $b) echo "<p>{$b['judul']}</p>";
