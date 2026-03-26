<?php
require_once '../config/database.php';

$stmt = $db->query("
    SELECT u.nama, COUNT(b.id) AS total
    FROM users u
    JOIN borrowings b ON b.user_id = u.id
    GROUP BY u.id
    ORDER BY total DESC
    LIMIT 10
");

echo '<ul style="list-style:none;padding:0">';
while ($row = $stmt->fetch()) {
    echo "
        <li style='margin-bottom:12px'>
            <strong>{$row['nama']}</strong><br>
            <small>Total Pinjam: {$row['total']} buku</small>
        </li>
    ";
}
echo '</ul>';
