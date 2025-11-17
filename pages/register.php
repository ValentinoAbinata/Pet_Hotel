<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $mysqli->real_escape_string($_POST['nama_lengkap']);
  $email = $mysqli->real_escape_string($_POST['email']);
  $pass = $_POST['password'];
  $peran = $mysqli->real_escape_string($_POST['peran'] ?? 'customer');

  if (empty($nama) || empty($email) || empty($pass)) {
    flash('Lengkapi semua field.');
    header('Location: register.php');
    exit;
  }

  $r = $mysqli->query("SELECT user_id FROM users WHERE email = '{$email}'");
  if ($r && $r->num_rows) {
    flash('Email sudah terdaftar.');
    header('Location: register.php');
    exit;
  }

  $hash = password_hash($pass, PASSWORD_DEFAULT);
  $mysqli->query("INSERT INTO users (nama_lengkap,email,password_hash,peran) VALUES ('{$nama}','{$email}','{$hash}','{$peran}')");
  flash('Registrasi berhasil. Silakan login.');
  header('Location: login.php');
  exit;
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card mx-auto" style="max-width:480px">
  <div class="card-body">
    <h5 class="card-title">Register</h5>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nama lengkap</label>
        <input class="form-control" name="nama_lengkap" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Peran</label>
        <select class="form-select" name="peran">
          <option value="customer">Customer</option>
          <option value="admin">Admin</option>
          <option value="dokter">Dokter</option>
        </select>
      </div>
      <button class="btn btn-primary">Register</button>
      <a class="btn btn-link" href="login.php">Login</a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>