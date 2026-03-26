<?php
require_once '../config/database.php';

$stmt = $db->query("
    SELECT b.id, b.judul, b.penulis, b.cover, COUNT(br.id) AS total
    FROM books b
    JOIN borrowings br ON br.book_id = b.id
    GROUP BY b.id
    ORDER BY total DESC
    LIMIT 12
");

echo '<div class="book-grid">';
while ($row = $stmt->fetch()) {
    echo "
    <div class='book-card' onclick='openBookDetail({$row['id']})'>
        <div class='book-cover-wrapper'>
            <img src='/perpustakaan/public/uploads/{$row['cover']}'>
        </div>
        <div class='book-info'>
            <h4>{$row['judul']}</h4>
            <p>{$row['total']}× dipinjam</p>
        </div>
    </div>
    ";
}
echo '</div>';
