<?php
require_once '../app/config/database.php';

$id = $_GET['id'] ?? 0;
$db = (new Database())->connect();

$stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "<p style='color:#fff'>Buku tidak ditemukan.</p>";
    exit;
}

$cover = !empty($book['cover'])
    ? $book['cover']
    : 'https://via.placeholder.com/400x600?text=No+Cover';
?>

<div class="detail-layout">
    <div>
        <img src="<?= $cover ?>" class="detail-cover">
        <button class="btn-request"
  onclick="borrowBook(<?= $book['id'] ?>,'<?= htmlspecialchars($book['judul']) ?>')">
  Pinjam Buku
</button>

    </div>

    <div>
        <div style="font-family:'Space Mono'; font-size:11px; color:#666; margin-bottom:15px;">
            FILE ID: <?= str_pad($book['id'], 3, '0', STR_PAD_LEFT) ?> |
            STATUS: AVAILABLE
        </div>

        <h2 style="font-size:2.2rem; margin-bottom:10px;">
            <?= htmlspecialchars($book['judul']) ?>
        </h2>

        <span style="
            background:#1f2937;
            padding:6px 12px;
            font-size:12px;
            border-radius:20px;
            display:inline-block;
            margin-bottom:20px;
        ">
            <?= htmlspecialchars($book['kategori']) ?>
        </span>

        <p style="line-height:1.8; color:#ccc; max-width:600px;">
            <?= nl2br(htmlspecialchars($book['deskripsi'])) ?>
        </p>
    </div>
</div>
