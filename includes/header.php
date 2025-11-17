<?php
require_once __DIR__ . '/auth.php';
$u = current_user();
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pet Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 56px;
    }

    .sidebar {
      min-width: 200px;
      max-width: 220px;
      height: 100vh;
      position: fixed;
      top: 56px;
      left: 0;
      padding: 1rem;
      background: #f8f9fa;
    }

    .content {
      margin-left: 240px;
      padding: 1.25rem;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="/Pet_Hotel/pages/dashboard.php">Pet Hotel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navcollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navcollapse">
        <?php if ($u && ($u['peran'] === 'customer')): ?>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#"
                data-bs-toggle="dropdown"><?= htmlspecialchars($u['nama_lengkap']) ?></a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/Pet_Hotel/pages/dashboard.php">Dashboard</a></li>
                <li><a class="dropdown-item" href="/Pet_Hotel/pages/logout.php">Logout</a></li>
              </ul>
            </li>
          </ul>
        <?php else: ?>
          <ul class="navbar-nav ms-auto">
            <?php if ($u): ?>
              <li class="nav-item">
                <a class="nav-link" href="/Pet_Hotel/pages/logout.php">Logout</a>
              </li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/login.php">Login</a></li>
            <?php endif; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <?php if ($u && ($u['peran'] === 'admin' || $u['peran'] === 'dokter')): ?>
    <aside class="sidebar">
      <h6>Menu</h6>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/dashboard.php">Dashboard</a></li>

        <?php
        if ($u['peran'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/hewan.php">Hewan</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/monitoring.php">Monitoring Harian</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/reservasi.php">Reservasi</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/pelanggan.php">Pelanggan</a></li>
        <?php endif; ?>

        <?php
        if ($u['peran'] === 'dokter'): ?>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/rekam_medis.php">Rekam Medis</a></li>
        <?php endif; ?>
      </ul>
    </aside>
    <main class="content">
    <?php else: ?>
      <main class="container mt-4">
      <?php endif; ?>

      <?php
      $msg = get_flash();
      if ($msg):
        ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>