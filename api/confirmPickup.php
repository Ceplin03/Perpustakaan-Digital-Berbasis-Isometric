<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

if (!Auth::check() || Auth::user()['role'] !== 'admin') {
    die('FORBIDDEN');
}

$db = (new Database())->connect();
$borrowId = $_POST['borrow_id'];
$bookId   = $_POST['book_id'];

$db->beginTransaction();

$db->prepare("
UPDATE borrows SET status='taken', pickup_at=NOW()
WHERE id=?
")->execute([$borrowId]);

$db->prepare("
UPDATE books SET status='borrowed'
WHERE id=?
")->execute([$bookId]);

$db->commit();

header("Location: /perpustakaan/public/admin/dashboard.php");
