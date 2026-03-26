<?php
require_once __DIR__ . '/../models/Book.php';

class BookController {
  private $book;

  public function __construct() {
    $this->book = new Book();
  }

  public function index() {
    return $this->book->all();
  }

  public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->book->create(
        $_POST['judul'],
        $_POST['kategori'],
        $_POST['rack_code'],
        $_POST['deskripsi']
      );
      header("Location: books.php");
      exit;
    }
  }

  public function delete($id) {
    $this->book->delete($id);
    header("Location: books.php");
    exit;
  }
}
