<?php
// pages/login.php
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
<div class="card mx-auto" style="max-width:420px">
  <div class="card-body">
    <h5 class="card-title">Login</h5>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">Login</button>
      <a class="btn btn-link" href="register.php">Register</a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
