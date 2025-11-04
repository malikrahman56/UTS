<?php
// index.php
declare(strict_types=1);
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pendaftaran Kelas Khusus — Login / Registrasi</title>

  <!-- Bootstrap 5 (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- HTMX 1.9.x (CDN) -->
  <script src="https://unpkg.com/htmx.org@1.9.3"></script>

  <style>
    body { background:#f8f9fa; }
    .card { max-width: 900px; margin: 40px auto; }
  </style>
</head>
<body>
  <div class="card shadow-sm">
    <div class="card-body row g-0">
      <div class="col-md-5 border-end p-4">
        <h5>Login</h5>
        <form id="login-form" hx-post="process_login.php" hx-target="#login-feedback" hx-swap="innerHTML">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required maxlength="50">
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <button class="btn btn-primary">Login</button>
        </form>
        <div id="login-feedback" class="mt-3"></div>
      </div>

      <div class="col-md-7 p-4">
        <h5>Registrasi Akun</h5>

        <!-- Form registrasi dikirim via HTMX; swap outerHTML sehingga form diganti pesan sukses -->
        <form id="register-form" hx-post="process_register.php" hx-target="#register-form" hx-swap="outerHTML">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required maxlength="50">
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input name="namalengkap" class="form-control" required maxlength="100">
          </div>
          <button class="btn btn-success">Buat Akun</button>
        </form>

      </div>
    </div>
  </div>

  <!-- Bootstrap JS (opsional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>