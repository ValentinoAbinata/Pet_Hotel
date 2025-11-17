<?php
include_once 'includes/functions.php';
if (!isLoggedIn()) {
    redirect('login.php');
}

$role = $_SESSION['peran'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pet Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php if ($role === 'admin' || $role === 'dokter'): ?>
        <!-- Sidebar untuk Admin & Dokter -->
        <div class="container-fluid">
            <div class="row">
                <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                    <div class="position-sticky pt-3">
                        <h5 class="sidebar-heading px-3 mt-4 mb-1 text-muted">
                            Menu <?= ucfirst($role) ?>
                        </h5>
                        <ul class="nav flex-column">
                            <?php if ($role === 'admin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link active" href="modules/hewan/">Data Hewan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="modules/monitoring/">Monitoring Harian</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="modules/reservasi/">Reservasi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="modules/pelanggan/">Data Pelanggan</a>
                                </li>
                            <?php elseif ($role === 'dokter'): ?>
                                <li class="nav-item">
                                    <a class="nav-link active" href="modules/rekam_medis/">Rekam Medis</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </nav>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard <?= ucfirst($role) ?></h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <span class="me-2">Halo, <?= $_SESSION['nama_lengkap'] ?></span>
                            <a href="logout.php" class="btn btn-sm btn-outline-secondary">Logout</a>
                        </div>
                    </div>
                    <p>Selamat datang di sistem Pet Hotel</p>
                </main>
            </div>
        </div>
    <?php else: ?>
        <!-- Navbar untuk Customer -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="#">Pet Hotel</a>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text me-3">Halo, <?= $_SESSION['nama_lengkap'] ?></span>
                    <a href="logout.php" class="btn btn-sm btn-light">Logout</a>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <h2>Dashboard Customer</h2>
            <p>Fitur customer akan dikembangkan kemudian</p>
        </div>
    <?php endif; ?>
</body>
</html>