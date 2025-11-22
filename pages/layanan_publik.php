<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
include __DIR__ . '/../includes/header.php';

$q = $mysqli->query("SELECT * FROM layanan ORDER BY harga ASC");
?>
<link rel="stylesheet" href="/PET_HOTEL/style/layanan_publik.css">

<div class="layanan-container">
    <div class="layanan-header">
        <h3>Daftar Layanan Kami</h3>
        <p>Berikut adalah layanan yang kami tawarkan untuk hewan kesayangan Anda.</p>
    </div>
    
    <div class="layanan-grid">
        <?php while ($r = $q->fetch_assoc()): ?>
            <div class="layanan-card">
                <div class="layanan-card-body">
                    <h4 class="layanan-title"><?= htmlspecialchars($r['nama_layanan']) ?></h4>
                    <span class="layanan-price">Rp<?= number_format($r['harga'], 0, ',', '.') ?></span>
                    <p class="layanan-description"><?= htmlspecialchars($r['deskripsi']) ?></p>
                    <div class="layanan-meta">Tempat : <?= htmlspecialchars($r['nama_ruang']) ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    
    <div class="layanan-footer">
        <p>Untuk memesan layanan di atas, silakan <a href="login.php">login</a> dan buat reservasi.</p>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>