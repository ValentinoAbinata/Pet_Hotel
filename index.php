<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// --- FITUR AUTO-CREATE ADMIN (Hanya jalan jika belum ada admin) ---
$cekAdmin = $mysqli->query("SELECT user_id FROM users WHERE peran = 'admin' LIMIT 1");
if ($cekAdmin && $cekAdmin->num_rows == 0) {
    // Setting Akun Admin Bawaan
    $nama_default  = "Super Admin";
    $email_default = "admin@pethotel.com";
    $pass_default  = "admin123"; // Password bawaan
    
    $hash = password_hash($pass_default, PASSWORD_DEFAULT);
    
    $sqlSeed = "INSERT INTO users (nama_lengkap, email, password_hash, peran) 
                VALUES ('$nama_default', '$email_default', '$hash', 'admin')";
    
    if ($mysqli->query($sqlSeed)) {
        // // Tampilkan pesan sukses sementara di layar
        // echo '<div class="alert alert-success text-center mb-0" style="z-index:9999; position:relative;">
        //         <strong>Sistem Baru Terdeteksi!</strong><br>
        //         Akun Admin Default Berhasil Dibuat.<br>
        //         Email: <b>'. $email_default .'</b> | Password: <b>'. $pass_default .'</b>
        //       </div>';
    }
}
// --- AKHIR FITUR ---

include __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="/PET_HOTEL/style/index.css">

<div class="container text-center mt-5 p-5 bg-light rounded shadow-sm">
    <h1 class="display-4">Selamat Datang di Pet Hotel</h1>
    <p class="lead">Layanan penitipan dan perawatan hewan peliharaan profesional.</p>
    <hr>
    <p>Silakan lihat layanan yang kami tawarkan atau login untuk mulai membuat reservasi hewan kesayangan Anda.</p>
    <a href="/Pet_Hotel/pages/layanan_publik.php" class="btn btn-primary btn-lg m-2">Lihat Layanan Kami</a>
    <a href="/Pet_Hotel/pages/login.php" class="btn btn-success btn-lg m-2">Login atau Register</a>
</div>

<?php
// Sertakan footer
include __DIR__ . '/includes/footer.php';
?>