<?php
require_once __DIR__ . '/auth.php';
// Pastikan functions dimuat untuk notifikasi
require_once __DIR__ . '/../includes/functions.php'; 

$u = current_user();

// --- LOGIKA DATA NOTIFIKASI ---
$unread = 0;
$notif_list = null;

if ($u) {
    if (function_exists('count_unread_notif')) {
        $unread = count_unread_notif($u['user_id']);
        $notif_list = get_my_notif($u['user_id']);
    }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>GreenPaws</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/Pet_Hotel/style/header.css">
  <style>
      /* --- PERBAIKAN FOOTER --- */
      body {
        min-height: 100vh;      /* Tinggi minimal 100% layar */
        display: flex;          /* Mode Flexbox */
        flex-direction: column; /* Susunan vertikal (atas ke bawah) */
      }
      main {
        flex: 1;                /* Konten utama akan "memelar" mengisi ruang kosong */
      }
      /* ------------------------ */

      /* Styling Notifikasi */
      .dropdown-menu-notif { width: 320px; max-height: 400px; overflow-y: auto; }
      .dropdown-item { white-space: normal; }
      .notif-unread { background-color: #f0f8ff; font-weight: bold; }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= $u ? '/Pet_Hotel/pages/dashboard.php' : '/Pet_Hotel/index.php' ?>">GreenPaws</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navcollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navcollapse">
        <ul class="navbar-nav ms-auto align-items-center">

          <?php if (!$u): // === BELUM LOGIN === ?>

            <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/layanan_publik.php">Lihat Layanan</a></li>
            <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/register.php">Register</a></li>

          <?php else: // === SUDAH LOGIN === ?>

            <li class="nav-item dropdown me-3">
              <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown">
                🔔 
                <?php if($unread > 0): ?>
                  <span class="badge rounded-pill bg-danger position-absolute" style="font-size: 0.6rem; top: 8px; right: 5px;">
                    <?= $unread ?>
                  </span>
                <?php endif; ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow dropdown-menu-notif">
                <li><h6 class="dropdown-header">Notifikasi Anda</h6></li>
                <?php if ($notif_list && $notif_list->num_rows > 0): ?>
                    <?php while($n = $notif_list->fetch_assoc()): ?>
                      <?php $bg = $n['status_baca'] == 0 ? 'notif-unread' : ''; ?>
                      <li class="<?= $bg ?>">
                        <a class="dropdown-item" href="<?= htmlspecialchars($n['link_url']) ?>">
                          <div class="d-flex justify-content-between">
                            <strong><?= htmlspecialchars($n['judul'] ?? 'Info') ?></strong>
                            <small class="text-muted" style="font-size:0.7rem"><?= date('d/m H:i', strtotime($n['created_at'])) ?></small>
                          </div>
                          <small class="text-secondary"><?= htmlspecialchars($n['pesan']) ?></small>
                        </a>
                      </li>
                      <li><hr class="dropdown-divider m-0"></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li><div class="dropdown-item text-center text-muted py-3">Tidak ada notifikasi</div></li>
                <?php endif; ?>
              </ul>
            </li>

            <?php if ($u['peran'] === 'customer'): ?>
                <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/portal/hewan.php">Hewan Saya</a></li>
                <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/portal/reservasi.php">Reservasi Saya</a></li>
                <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/portal/profil.php">Profil Saya</a></li>
            
            <?php elseif ($u['peran'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/Pet_Hotel/modules/staff.php">👨‍💼 Kelola Staf</a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
              <a class="nav-link text-warning" href="/Pet_Hotel/pages/logout.php">Logout 
                (<?= htmlspecialchars(explode(' ', $u['nama_lengkap'])[0]) ?>)</a>
            </li>

          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>

  <?php // SIDEBAR ADMIN/DOKTER
  if ($u && ($u['peran'] === 'admin' || $u['peran'] === 'dokter')): ?>
    <aside class="sidebar">
      <div class="p-3"><h6>Menu Utama</h6></div>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/pages/dashboard.php">Dashboard</a></li>
        
        <?php if ($u['peran'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/hewan.php">Hewan</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/staf.php">Kelola Staf</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/monitoring.php">Monitoring Harian</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/reservasi.php">Reservasi</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/pelanggan.php">Pelanggan</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/layanan.php">Layanan</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/pembayaran.php">Pembayaran</a></li>
        <?php endif; ?>

        <?php if ($u['peran'] === 'dokter'): ?>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/monitoring.php">Monitoring Harian</a></li>
          <li class="nav-item"><a class="nav-link" href="/Pet_Hotel/modules/rekam_medis.php">Rekam Medis</a></li>
        <?php endif; ?>
      </ul>
    </aside>
    <main class="content">
    
  <?php else: // CUSTOMER ?>
    <main class="container mt-5 pt-4">
  <?php endif; ?>

  <?php
  $msg = get_flash();
  if ($msg):
  ?>
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
      <?= htmlspecialchars($msg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>