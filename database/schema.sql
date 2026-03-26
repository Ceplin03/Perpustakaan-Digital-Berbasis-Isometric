CREATE DATABASE perpustakaan_db;
USE perpustakaan_db;

-- USERS
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  role ENUM('siswa','admin') DEFAULT 'siswa',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- BOOKS (dipakai nanti)
CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(255),
  kategori VARCHAR(100),
  rack_code VARCHAR(10),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE books
ADD COLUMN deskripsi TEXT AFTER judul;

-- BORROW LOG
CREATE TABLE borrows (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  book_id INT,
  borrow_date DATE,
  return_date DATE,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (book_id) REFERENCES books(id)
);
