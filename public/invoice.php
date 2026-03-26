<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

if (!Auth::check() || Auth::user()['role'] !== 'admin') {
    die('AKSES DITOLAK');
}

$code = $_GET['code'] ?? '';
$db = (new Database())->connect();

$stmt = $db->prepare("
SELECT b.*, u.nama, br.id AS borrow_id, br.status
FROM borrows br
JOIN books b ON br.book_id=b.id
JOIN users u ON br.user_id=u.id
WHERE br.invoice_code=?
");
$stmt->execute([$code]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$data){
    die('INVOICE TIDAK VALID');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Verifikasi Invoice</title>
<style>
body{background:#020617;color:#fff;font-family:Inter;padding:40px}
.card{max-width:520px;margin:auto;background:#0f172a;padding:32px;border-radius:20px}
button{padding:14px;width:100%;border:none;border-radius:12px;font-weight:700}
</style>
</head>
<body>

<div class="card">
<h2>VERIFIKASI PEMINJAMAN</h2>
<p><b>Nama:</b> <?= htmlspecialchars($data['nama']) ?></p>
<p><b>Buku:</b> <?= htmlspecialchars($data['judul']) ?></p>
<p><b>Status:</b> <?= strtoupper($data['status']) ?></p>

<?php if($data['status']==='pending'): ?>
<form method="post" action="/perpustakaan/api/confirmPickup.php">
<input type="hidden" name="borrow_id" value="<?= $data['borrow_id'] ?>">
<input type="hidden" name="book_id" value="<?= $data['id'] ?>">
<button style="background:#22c55e">✅ SERAHKAN BUKU</button>
</form>
<?php else: ?>
<p style="color:#facc15">Buku sudah diserahkan</p>
<?php endif; ?>
</div>

</body>
</html>
