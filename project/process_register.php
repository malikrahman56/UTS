<?php
// process_register.php
declare(strict_types=1);
session_start();
require_once 'db.php';

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$namalengkap = trim((string)($_POST['namalengkap'] ?? ''));

// Validasi
$errors = [];
if ($username === '' || $password === '' || $namalengkap === '') {
    $errors[] = "Semua field wajib diisi.";
}
if (strlen($username) > 50) $errors[] = "Username terlalu panjang.";
if (strlen($namalengkap) > 100) $errors[] = "Nama lengkap terlalu panjang.";

if (!empty($errors)) {
    // Tampilkan pesan error (akan menggantikan form karena hx-swap="outerHTML")
    echo '<div class="alert alert-danger">';
    foreach ($errors as $err) {
        echo '<div>' . e($err) . '</div>';
    }
    echo '<div class="mt-2"><a href="index.php" class="btn btn-sm btn-secondary">Kembali</a></div>';
    echo '</div>';
    exit;
}

// Cek username unik
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
$stmt->execute([':username' => $username]);
if ($stmt->fetch()) {
    echo '<div class="alert alert-warning">Username sudah dipakai. Silakan pilih lain.</div>';
    exit;
}

// Insert
$hashed = password_hash($password, PASSWORD_DEFAULT);
$ins = $pdo->prepare("INSERT INTO users (username, password, namalengkap) VALUES (:username, :password, :namalengkap)");
$ins->execute([
    ':username' => $username,
    ':password' => $hashed,
    ':namalengkap' => $namalengkap,
]);

// Balikan HTMX: ganti form dengan pesan sukses
echo '<div class="alert alert-success">Akun berhasil dibuat, silakan <strong>login</strong>.</div>';