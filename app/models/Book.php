<?php
require_once __DIR__ . '/../config/database.php';

class Book {
  private $db;

  public function __construct() {
    $this->db = (new Database())->connect();
  }

  public function all() {
    return $this->db
      ->query("SELECT * FROM books ORDER BY id DESC")
      ->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($judul, $kategori, $rack, $deskripsi) {
    $stmt = $this->db->prepare(
      "INSERT INTO books (judul, kategori, rack_code, deskripsi)
       VALUES (?, ?, ?, ?)"
    );
    return $stmt->execute([$judul, $kategori, $rack, $deskripsi]);
  }

  public function find($id) {
    $stmt = $this->db->prepare(
      "SELECT * FROM books WHERE id = ?"
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function delete($id) {
    $stmt = $this->db->prepare(
      "DELETE FROM books WHERE id = ?"
    );
    return $stmt->execute([$id]);
  }
}
