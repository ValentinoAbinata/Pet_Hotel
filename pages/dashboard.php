<?php
// pages/dashboard.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
include __DIR__ . '/../includes/header.php';
$u = current_user();
?>
<h2>Halo, <?= htmlspecialchars($u['nama_lengkap']) ?></h2>
<p>Anda sebagai: <strong><?= htmlspecialchars($u['peran']) ?></strong></p>

<div class="row">
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-body">
        <h6 class="card-title">Hewan</h6>
        <a href="/Pet_Hotel/modules/hewan.php" class="btn btn-sm btn-primary">Kelola hewan</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-body">
        <h6 class="card-title">Reservasi</h6>
        <a href="/Pet_Hotel/modules/reservasi.php" class="btn btn-sm btn-primary">Kelola reservasi</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-body">
        <h6 class="card-title">Monitoring</h6>
        <a href="/Pet_Hotel/modules/monitoring.php" class="btn btn-sm btn-primary">Monitoring harian</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>