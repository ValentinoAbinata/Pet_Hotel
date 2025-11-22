<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $mysqli->real_escape_string($_POST['email']);
  $pass = $_POST['password'];

  $r = $mysqli->query("SELECT * FROM users WHERE email = '{$email}' LIMIT 1");
  if ($r && $r->num_rows) {
    $u = $r->fetch_assoc();
    if (password_verify($pass, $u['password_hash'])) {
      $_SESSION['user'] = [
        'user_id' => $u['user_id'],
        'nama_lengkap' => $u['nama_lengkap'],
        'email' => $u['email'],
        'peran' => $u['peran']
      ];
      header('Location: /Pet_Hotel/pages/dashboard.php');
      exit;
    }
  }
  flash('Login gagal.');
  header('Location: login.php');
  exit;
}

include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="/PET_HOTEL/style/login.css">

<div class="login-container">
    <div class="login-card">
        <div class="login-card-header">
            <h1 class="login-card-title">Masuk ke Akun Anda</h1>
        </div>
        <div class="login-card-body">
            <?php
            $msg = get_flash();
            if ($msg)
                echo '<div class="alert alert-danger">' . htmlspecialchars($msg) . '</div>';
            ?>
            <form method="post">
                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required placeholder="Masukkan email Anda">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" required placeholder="Masukkan password Anda">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Masuk</button>
                    <a class="btn btn-link" href="register.php">Belum punya akun? Daftar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>