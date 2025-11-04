<?php
// db.php
declare(strict_types=1);

error_reporting(0); // matikan display error untuk demo; gunakan setting lebih aman di production
ini_set('display_errors', '0');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'uts_web');
define('DB_USER', 'root');
define('DB_PASS', ''); // sesuaikan jika ada password

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Pada environment produksi jangan tampilkan error detail
    http_response_code(500);
    echo "Koneksi database gagal.";
    exit;
}