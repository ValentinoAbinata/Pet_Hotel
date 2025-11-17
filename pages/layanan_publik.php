<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/header.php';

$q = $mysqli->query("SELECT * FROM layanan ORDER BY harga ASC");
?>

<h3>Daftar Layanan Kami</h3>
<p>Berikut adalah layanan yang kami tawarkan untuk hewan kesayangan Anda.</p>

<div class="row">
    <?php while ($r = $q->fetch_assoc()): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($r['nama_layanan']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-success">Rp<?= number_format($r['harga']) ?></h6>
                    <p class="card-text"><?= htmlspecialchars($r['deskripsi']) ?></p>
                    <small class="text-muted mt-auto">Ruang: <?= htmlspecialchars($r['nama_ruang']) ?></small>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<hr>
<p>Untuk memesan layanan di atas, silakan <a href="login.php">login</a> dan buat reservasi.</p>

<?php include __DIR__ . '/../includes/footer.php'; ?>