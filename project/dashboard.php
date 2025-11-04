<?php
// dashboard.php
declare(strict_types=1);
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$userId = (int)$_SESSION['user_id'];
$namaLengkap = (string)($_SESSION['namalengkap'] ?? '');

// Ambil semua pendaftar (urut terbaru)
$stmt = $pdo->prepare("SELECT r.id, r.nim, r.nama_mk, r.registered_at, u.namalengkap as pendaftar_nama FROM registrations r JOIN users u ON r.user_id = u.id ORDER BY r.registered_at DESC");
$stmt->execute();
$registrations = $stmt->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard — Pendaftaran Kelas Khusus</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- HTMX -->
  <script src="https://unpkg.com/htmx.org@1.9.3"></script>
</head>
<body>
  <nav class="navbar navbar-expand bg-light mb-4">
    <div class="container">
      <span class="navbar-brand">Pendaftaran Kelas Khusus</span>
      <div class="ms-auto">
        <span class="me-3">Selamat datang, <strong><?= e($namaLengkap) ?></strong></span>
        <a class="btn btn-outline-secondary btn-sm" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row g-4">
      <div class="col-md-5">
        <div class="card p-3">
          <h6>Form Pendaftaran Kelas</h6>

          <!-- Form pendaftaran: hx-post ke process_registration.php; targert = #registration-form; swap outerHTML -->
          <form id="registration-form" hx-post="process_registration.php" hx-target="#registration-form" hx-swap="outerHTML">
            <div class="mb-3">
              <label class="form-label">NIM</label>
              <input name="nim" class="form-control" required maxlength="20">
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Mata Kuliah</label>
              <input name="nama_mk" class="form-control" required maxlength="100">
            </div>
            <button class="btn btn-primary">Daftar</button>
          </form>

        </div>
      </div>

      <div class="col-md-7">
        <div class="card p-3">
          <h6>Daftar Pendaftar</h6>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>NIM</th>
                  <th>Nama MK</th>
                  <th>Pendaftar</th>
                  <th>Waktu</th>
                </tr>
              </thead>
              <tbody id="registrations-table-body">
                <?php foreach ($registrations as $row): ?>
                  <tr id="reg-row-<?= (int)$row['id'] ?>">
                    <td><?= $row['id'] ?></td>
                    <td><?= ($row['nim']) ?></td>
                    <td><?= ($row['nama_mk']) ?></td>
                    <td><?= ($row['pendaftar_nama']) ?></td>
                    <td><?= ($row['registered_at']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (opsional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>