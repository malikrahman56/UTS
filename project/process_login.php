<?php
// process_login.php
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

if ($username === '' || $password === '') {
    echo '<div class="alert alert-danger">Isi username dan password.</div>';
    exit;
}

$stmt = $pdo->prepare("SELECT id, password, namalengkap FROM users WHERE username = :username LIMIT 1");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, (string)$user['password'])) {
    echo '<div class="alert alert-danger">Username atau password salah.</div>';
    exit;
}

// Login berhasil
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['namalengkap'] = (string)$user['namalengkap'];

// Respon harus mengarahkan browser ke dashboard.php.
// Spec meminta: redirect via window.location.href (bukan HTMX internal).
// Kita kirimkan script kecil yang akan dieksekusi di browser.
echo '<div class="alert alert-success">Login berhasil. Mengalihkan...</div>';
echo '<script>window.location.href = "dashboard.php";</script>';