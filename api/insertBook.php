<?php
require_once '../app/core/Auth.php';
require_once '../app/config/database.php';

header('Content-Type: application/json');

if (!Auth::check() || Auth::user()['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode tidak valid'
    ]);
    exit;
}

$db = (new Database())->connect();

$judul     = $_POST['judul'] ?? '';
$kategori  = $_POST['kategori'] ?? '';
$rack      = $_POST['rack_code'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$cover     = $_POST['cover'] ?? '';

$stmt = $db->prepare("
    INSERT INTO books (judul, kategori, rack_code, deskripsi, cover)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $judul,
    $kategori,
    $rack,
    $deskripsi,
    $cover
]);

echo json_encode([
    'status' => 'success',
    'message' => 'Buku berhasil ditambahkan ke perpustakaan'
]);
