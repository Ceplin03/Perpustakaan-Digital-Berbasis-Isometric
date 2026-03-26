<?php
class DB
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=localhost;dbname=perpustakaan_db;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed");
        }
    }

    /**
     * Ambil instance koneksi database
     */
    public static function connect()
    {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance->conn;
    }
}
