<?php
require_once '../app/config/database.php';
$db=(new Database())->connect();
$r=$_GET['id'];
$q=$db->prepare("SELECT id,judul,kategori FROM books WHERE rack_code=?");
$q->execute([$r]);
echo "<ul>";
foreach($q as $b) echo "<li>{$b['judul']} ({$b['kategori']})</li>";
echo "</ul>";
