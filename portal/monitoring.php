<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
$u = current_user();
$customer_id = (int)$u['user_id'];

$reservasi_id = isset($_GET['reservasi_id']) ? (int)$_GET['reservasi_id'] : 0;
if ($reservasi_id === 0) {
    flash('Reservasi tidak valid.');
    header('Location: /Pet_Hotel/portal/reservasi.php');
    exit;
}

$res = $mysqli->query("SELECT r.*, h.nama_hewan 
                      FROM reservasi r 
                      LEFT JOIN hewan h ON r.hewan_id = h.hewan_id 
                      WHERE r.reservasi_id = $reservasi_id AND r.customer_id = $customer_id 
                      LIMIT 1");

if ($res->num_rows == 0) {
    flash('Akses ditolak.');
    header('Location: /Pet_Hotel/portal/reservasi.php');
    exit;
}
$reservasi = $res->fetch_assoc();
$nama_hewan = htmlspecialchars($reservasi['nama_hewan']);

$q = $mysqli->query("SELECT * FROM monitoring_harian 
                    WHERE reservasi_id = $reservasi_id 
                    ORDER BY tanggal_monitoring DESC");

include __DIR__ . '/../includes/header.php';
$msg = get_flash(); if ($msg) echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
?>

<link rel="stylesheet" href="/PET_HOTEL/style/monitoring.css">

<div class="monitoring-container">
    <div class="monitoring-header">
        <h3>Laporan Harian: <?= $nama_hewan ?></h3>
        <p>Menampilkan laporan untuk Reservasi #<?= $reservasi_id ?></p>
        <a href="reservasi.php" class="back-link">
            <span>&laquo;</span> Kembali ke Daftar Reservasi
        </a>
    </div>

    <?php if ($q->num_rows == 0): ?>
        <div class="alert alert-warning">
            <div class="empty-state">
                <div class="icon">📊</div>
                <h5>Belum ada laporan monitoring</h5>
                <p>Staf belum menginput laporan monitoring untuk reservasi ini.</p>
            </div>
        </div>
    <?php else: ?>
        <?php while ($r = $q->fetch_assoc()): ?>
            <div class="monitoring-card">
                <div class="monitoring-card-header">
                    <strong>Laporan Tanggal: <?= date('d F Y', strtotime($r['tanggal_monitoring'])) ?></strong>
                </div>
                <div class="monitoring-card-body">
                    <div class="row">
                        <div class="col-lg-4 media-section">
                            <?php if ($r['foto_video_url']): ?>
                                <?php $url = htmlspecialchars($r['foto_video_url']); ?>
                                <?php if (in_array(pathinfo($url, PATHINFO_EXTENSION), ['mp4', 'webm'])): ?>
                                    <div class="media-container">
                                        <video src="/Pet_Hotel/<?= $url ?>" class="img-fluid" controls>
                                            Video tidak didukung.
                                        </video>
                                    </div>
                                <?php else: ?>
                                    <div class="media-container">
                                        <a href="/Pet_Hotel/<?= $url ?>" target="_blank">
                                            <img src="/Pet_Hotel/<?= $url ?>" class="img-fluid" alt="Foto Monitoring">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="media-placeholder">
                                    <span>📷</span>
                                    <p>Tidak ada foto/video</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-8">
                            <div class="details-list">
                                <dl class="row">
                                    <dt class="col-sm-4">Makan</dt>
                                    <dd class="col-sm-8">
                                        <div class="progress-indicator">
                                            <span class="progress-text">
                                                <?= (int)$r['aktual_makanan'] ?> / <?= (int)$r['target_makanan'] ?> porsi
                                            </span>
                                        </div>
                                    </dd>

                                    <dt class="col-sm-4">Mandi</dt>
                                    <dd class="col-sm-8">
                                        <span class="status-indicator <?= (int)$r['aktual_mandi'] ? 'status-yes' : 'status-no' ?>">
                                            <?= (int)$r['aktual_mandi'] ? 'Ya' : 'Tidak' ?>
                                        </span>
                                    </dd>

                                    <dt class="col-sm-4">Catatan Kesehatan</dt>
                                    <dd class="col-sm-8"><?= htmlspecialchars($r['catatan_kesehatan']) ?: '-' ?></dd>

                                    <dt class="col-sm-4">Catatan Aktivitas</dt>
                                    <dd class="col-sm-8"><?= htmlspecialchars($r['catatan_aktivitas']) ?: '-' ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
?>