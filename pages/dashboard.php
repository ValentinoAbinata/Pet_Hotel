<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
include __DIR__ . '/../includes/header.php';
$u = current_user();
?>

<h2>Halo, <?= htmlspecialchars($u['nama_lengkap']) ?>!</h2>
<p>Anda login sebagai: <strong><?= htmlspecialchars($u['peran']) ?></strong></p>
<hr>

<?php
if ($u['peran'] === 'admin'):
  ?>
  <div class="row">
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-title">Hewan</h6>
          <p class="card-text">Kelola data semua hewan peliharaan.</p>
          <a href="/Pet_Hotel/modules/hewan.php" class="btn btn-sm btn-primary">Kelola hewan</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-title">Reservasi</h6>
          <p class="card-text">Kelola semua reservasi pelanggan.</p>
          <a href="/Pet_Hotel/modules/reservasi.php" class="btn btn-sm btn-primary">Kelola reservasi</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-title">Monitoring</h6>
          <p class="card-text">Input laporan harian hewan.</p>
          <a href="/Pet_Hotel/modules/monitoring.php" class="btn btn-sm btn-primary">Monitoring harian</a>
        </div>
      </div>
    </div>
  </div>

<?php
elseif ($u['peran'] === 'customer'):
  $customer_id = (int) $u['user_id'];

  $resHewan = $mysqli->query("SELECT COUNT(*) as total FROM hewan WHERE customer_id = $customer_id");
  $total_hewan = $resHewan->fetch_assoc()['total'];

  $stats = ['Pending' => 0, 'Confirmed' => 0, 'Completed' => 0];
  $resStats = $mysqli->query("SELECT status_reservasi, COUNT(*) as total 
                              FROM reservasi 
                              WHERE customer_id = $customer_id 
                              GROUP BY status_reservasi");
  while ($r = $resStats->fetch_assoc()) {
    if (isset($stats[$r['status_reservasi']])) {
      $stats[$r['status_reservasi']] = $r['total'];
    }
  }
  ?>
  <h5>Ringkasan Akun Anda</h5>
  <div class="row">
    <div class="col-md-4">
      <div class="card text-bg-primary mb-3">
        <div class="card-body">
          <h5 class="card-title"><?= $total_hewan ?></h5>
          <p class="card-text">Total Hewan Terdaftar</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-warning mb-3">
        <div class="card-body">
          <h5 class="card-title"><?= $stats['Pending'] ?></h5>
          <p class="card-text">Reservasi Menunggu Konfirmasi</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-success mb-3">
        <div class="card-body">
          <h5 class="card-title"><?= $stats['Confirmed'] ?></h5>
          <p class="card-text">Reservasi Aktif / Terkonfirmasi</p>
        </div>
      </div>
    </div>
  </div>

  <h5>Pintasan</h5>
  <div class="row">
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-title">Buat Reservasi Baru</h6>
          <p class="card-text">Pesan tempat untuk hewan peliharaan Anda.</p>
          <a href="/Pet_Hotel/portal/reservasi.php?action=create" class="btn btn-sm btn-success">Buat Reservasi</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-title">Hewan Saya</h6>
          <p class="card-text">Kelola data hewan peliharaan Anda.</p>
          <a href="/Pet_Hotel/portal/hewan.php" class="btn btn-sm btn-primary">Kelola Hewan</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h6 class="card-title">Profil Saya</h6>
          <p class="card-text">Perbarui data kontak & alamat Anda.</p>
          <a href="/Pet_Hotel/portal/profil.php" class="btn btn-sm btn-primary">Edit Profil</a>
        </div>
      </div>
    </div>
  </div>

  <h5>5 Reservasi Terbaru</h5>
  <?php
  $q = $mysqli->query("SELECT r.*, h.nama_hewan 
                      FROM reservasi r 
                      LEFT JOIN hewan h ON r.hewan_id = h.hewan_id 
                      WHERE r.customer_id = $customer_id 
                      ORDER BY r.reservasi_id DESC LIMIT 5");
  ?>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Hewan</th>
        <th>Check-in</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($r = $q->fetch_assoc()): ?>
        <tr>
          <td><?= $r['reservasi_id'] ?></td>
          <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($r['tanggal_checkin'])) ?></td>
          <td><?= htmlspecialchars($r['status_reservasi']) ?></td>
          <td><a href="/Pet_Hotel/portal/monitoring.php?reservasi_id=<?= $r['reservasi_id'] ?>"
              class="btn btn-sm btn-info">Lihat Laporan</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

<?php
elseif ($u['peran'] === 'dokter'):
  ?>
  <p>Selamat datang di sistem Pet Hotel. Anda dapat mengakses menu yang relevan melalui sidebar.</p>

<?php endif; ?>


<?php include __DIR__ . '/../includes/footer.php'; ?>