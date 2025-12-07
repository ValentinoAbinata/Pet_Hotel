<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $mysqli->real_escape_string($_POST['nama_lengkap']);
  $email = $mysqli->real_escape_string($_POST['email']);
  $pass = $_POST['password'];
  
  // FIX: Paksa peran otomatis menjadi 'customer'
  // Ini mencegah user publik mendaftar sebagai admin atau dokter
  $peran = 'customer';

  if (empty($nama) || empty($email) || empty($pass)) {
    flash('Lengkapi semua field.');
    header('Location: register.php');
    exit;
  }

  // Cek apakah email sudah ada
  $r = $mysqli->query("SELECT user_id FROM users WHERE email = '{$email}'");
  if ($r && $r->num_rows) {
    flash('Email sudah terdaftar.');
    header('Location: register.php');
    exit;
  }

  // Simpan ke database
  $hash = password_hash($pass, PASSWORD_DEFAULT);
  $mysqli->query("INSERT INTO users (nama_lengkap,email,password_hash,peran) VALUES ('{$nama}','{$email}','{$hash}','{$peran}')");
  
  flash('Registrasi berhasil. Silakan login.');
  header('Location: login.php');
  exit;
}

include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="/PET_HOTEL/style/register.css">

<div class="register-container">
    <div class="register-card">
        <div class="register-card-header">
            <h1 class="register-card-title">Daftar Akun Baru</h1>
        </div>
        <div class="register-card-body">
            <form method="post">
                <div class="form-group mb-3">
                    <label class="form-label">Nama lengkap</label>
                    <input class="form-control" name="nama_lengkap" required placeholder="Masukkan nama lengkap Anda">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required placeholder="contoh@email.com">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required placeholder="Buat password yang kuat">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                    <a class="btn btn-link" href="login.php">Sudah punya akun? Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>