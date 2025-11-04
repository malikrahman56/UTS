<?php
// process_registration.php
declare(strict_types=1);
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo '<div class="alert alert-danger">Anda harus login.</div>';
    exit;
}

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

$userId = (int)$_SESSION['user_id'];
$nim = trim((string)($_POST['nim'] ?? ''));
$nama_mk = trim((string)($_POST['nama_mk'] ?? ''));

if ($nim === '' || $nama_mk === '') {
    echo '<div class="alert alert-danger">Semua field wajib diisi.</div>';
    exit;
}

if (strlen($nim) > 20 || strlen($nama_mk) > 100) {
    echo '<div class="alert alert-danger">Panjang input melebihi batas.</div>';
    exit;
}

// Insert ke DB
$ins = $pdo->prepare("INSERT INTO registrations (user_id, nim, nama_mk) VALUES (:user_id, :nim, :nama_mk)");
$ins->execute([
    ':user_id' => $userId,
    ':nim' => $nim,
    ':nama_mk' => $nama_mk,
]);

$lastId = (int)$pdo->lastInsertId();

// Kirimkan dua blok:
// 1) Pesan sukses untuk menggantikan form (karena dashboard form mempunyai hx-swap outerHTML).
// 2) Element OOB untuk ditambahkan ke tabel (#registrations-table-body).

// 1) Pesan sukses (mengganti form)
echo '<div class="alert alert-success">Pendaftaran berhasil!</div>';

// 2) OOB element: baris tabel baru (htmx akan melakukan out-of-band swap)
$namaLengkapUser = (string)($_SESSION['namalengkap'] ?? ''); // sudah tersedia di session
$registeredAt = date('Y-m-d H:i:s');

$rowHtml = '<tr id="reg-row-' . $lastId . '">' .
    '<td>' . $lastId . '</td>' .
    '<td>' . e($nim) . '</td>' .
    '<td>' . e($nama_mk) . '</td>' .
    '<td>' . e($namaLengkapUser) . '</td>' .
    '<td>' . e($registeredAt) . '</td>' .
    '</tr>';

// Wrapping div dengan hx-swap-oob instruksi
echo '<div hx-swap-oob="beforeend:#registrations-table-body">' . $rowHtml . '</div>';