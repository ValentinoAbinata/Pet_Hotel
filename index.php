<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>

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