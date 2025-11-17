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

<div class="mb-3">
  <h3>Laporan Harian: <?= $nama_hewan ?></h3>
  <p>Menampilkan laporan untuk Reservasi #<?= $reservasi_id ?></p>
  <a href="reservasi.php">&laquo; Kembali ke Daftar Reservasi</a>
</div>

<?php if ($q->num_rows == 0): ?>
  <div class="alert alert-warning">
    Belum ada laporan monitoring yang di-input oleh staf untuk reservasi ini.
  </div>
<?php else: ?>
  <?php while ($r = $q->fetch_assoc()): ?>
    <div class="card mb-3">
      <div class="card-header">
        <strong>Laporan Tanggal: <?= date('d F Y', strtotime($r['tanggal_monitoring'])) ?></strong>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <?php if ($r['foto_video_url']): ?>
              <?php $url = htmlspecialchars($r['foto_video_url']); ?>
              <?php // Cek ekstensi file untuk menentukan apakah itu video atau gambar ?>
              <?php if (in_array(pathinfo($url, PATHINFO_EXTENSION), ['mp4', 'webm'])): ?>
                <video src="/Pet_Hotel/<?= $url ?>" class="img-fluid" controls>Video tidak didukung.</video>
              <?php else: ?>
                <a href="/Pet_Hotel/<?= $url ?>" target="_blank">
                  <img src="/Pet_Hotel/<?= $url ?>" class="img-fluid" alt="Foto Monitoring">
                </a>
              <?php endif; ?>
            <?php else: ?>
              <p class="text-muted">(Tidak ada foto/video)</p>
            <?php endif; ?>
          </div>
          <div class="col-md-8">
            <dl class="row">
              <dt class="col-sm-3">Makan</dt>
              <dd class="col-sm-9"><?= (int)$r['aktual_makanan'] ?> / <?= (int)$r['target_makanan'] ?> porsi</dd>

              <dt class="col-sm-3">Mandi</dt>
              <dd class="col-sm-9"><?= (int)$r['aktual_mandi'] ? 'Ya' : 'Tidak' ?></dd>

              <dt class="col-sm-3">Catatan Kesehatan</dt>
              <dd class="col-sm-9"><?= htmlspecialchars($r['catatan_kesehatan']) ?: '-' ?></dd>

              <dt class="col-sm-3">Catatan Aktivitas</dt>
              <dd class="col-sm-9"><?= htmlspecialchars($r['catatan_aktivitas']) ?: '-' ?></dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
<?php endif; ?>

<?php
include __DIR__ . '/../includes/footer.php';
?>