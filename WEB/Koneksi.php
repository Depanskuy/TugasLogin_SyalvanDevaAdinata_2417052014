<?php

$host     = 'localhost';
$dbname   = 'db_login';
$username = 'root';       
$password = '';     

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('<div style="font-family:monospace;background:#fee;padding:20px;border:2px solid red;">
        <strong>Koneksi Database Gagal!</strong><br>
        Pesan: ' . htmlspecialchars($e->getMessage()) . '<br><br>
        Pastikan:<br>
        1. MySQL sudah berjalan (XAMPP/Laragon/dll)<br>
        2. Database <strong>db_login</strong> sudah dibuat<br>
        3. Username &amp; password di koneksi.php sudah benar
    </div>');
}