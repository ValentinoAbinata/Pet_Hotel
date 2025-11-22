<?php
require_once __DIR__ . '/auth.php';
$u = current_user();
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>GreenPaws</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Pet_Hotel/style/header.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= $u ? '/Pet_Hotel/pages/dashboard.php' : '/Pet_Hotel/index.php' ?>">GreenPaws</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navcollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navcollapse">
        <ul class="navbar-nav ms-auto">

          <?php if (!$u): // === JIKA TIDAK LOGIN (PENGUNJUNG PUBLIK) === ?>

            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/pages/layanan_publik.php">Lihat Layanan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/pages/login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/pages/register.php">Register</a>
            </li>

          <?php elseif ($u['peran'] === 'customer'): // === JIKA LOGIN SEBAGAI CUSTOMER === ?>

            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/pages/dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/portal/hewan.php">Hewan Saya</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/portal/reservasi.php">Reservasi Saya</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/portal/profil.php">Profil Saya</a>
            </li>
            <li class_="nav-item">
              <a class="nav-link" href="/Pet_Hotel/pages/logout.php">Logout
                (<?= htmlspecialchars(explode(' ', $u['nama_lengkap'])[0]) ?>)</a>
            </li>

          <?php else: // === JIKA LOGIN SEBAGAI ADMIN / DOKTER === ?>

            <li class="nav-item">
              <a class="nav-link" href="/Pet_Hotel/pages/logout.php">Logout
                (<?= htmlspecialchars($u['nama_lengkap']) ?>)</a>
            </li>

          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>

  <?php // Logika untuk Sidebar Admin/Dokter
  if ($u && ($u['peran'] === 'admin' || $u['peran'] === 'dokter')): ?>
    <aside class="sidebar">
      <h6>Menu</h6>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/dashboard.php">Dashboard</a></li>
        <?php if ($u['peran'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/hewan.php">Hewan</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/monitoring.php">Monitoring Harian</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/reservasi.php">Reservasi</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/pelanggan.php">Pelanggan</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/layanan.php">Layanan</a></li>
          <li class="nav-item"><a class_="nav-link" href="/Pet_Hotel/modules/pembayaran.php">Pembayaran</a></li>
          <li class="nav-item"><a class_="nav-link" href="/Pet_Hotel/modules/catat_pembayaran.php">Catat Pembayaran</a></li>
        <?php endif; ?>
        <?php if ($u['peran'] === 'dokter'): ?>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/rekam_medis.php">Rekam Medis</a></li>
        <?php endif; ?>
      </ul>
    </aside>
    <main class="content">
    <?php else: // Pengunjung publik ATAU customer (tanpa sidebar) ?>
      <main class="container mt-4">
      <?php endif; ?>

      <?php
      // Tampilkan flash message
      $msg = get_flash();
      if ($msg):
        ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($msg) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>