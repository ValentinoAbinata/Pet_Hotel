<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
include __DIR__ . '/../includes/header.php';
$u = current_user();
?>

<link rel="stylesheet" href="/../PET_HOTEL/style/dashboard.css">

<div class="dashboard-container">
    <div class="welcome-section fade-in">
      <div class="head">
        <h2>Halo, <?= htmlspecialchars($u['nama_lengkap']) ?>! <span class="role-badge"><?= htmlspecialchars($u['peran']) ?></span></h2>
        <p>Selamat datang di sistem Pet Hotel - Tempat terbaik untuk hewan peliharaan Anda!</p>
      </div>
    </div>

    <?php if ($u['peran'] === 'admin'): ?>
        <div class="row slide-up">
            <div class="col-md-4">
                <div class="card interactive-hover">
                    <div class="card-body">
                        <h6 class="card-title">🐕 Hewan</h6>
                        <p class="card-text">Kelola data semua hewan peliharaan.</p>
                        <a href="/Pet_Hotel/modules/hewan.php" class="btn btn-primary">
                            Kelola hewan
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card interactive-hover">
                    <div class="card-body">
                        <h6 class="card-title">📅 Reservasi</h6>
                        <p class="card-text">Kelola semua reservasi pelanggan.</p>
                        <a href="/Pet_Hotel/modules/reservasi.php" class="btn btn-primary">
                            Kelola reservasi
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card interactive-hover">
                    <div class="card-body">
                        <h6 class="card-title">📊 Monitoring</h6>
                        <p class="card-text">Input laporan harian hewan.</p>
                        <a href="/Pet_Hotel/modules/monitoring.php" class="btn btn-primary">
                            Monitoring harian
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($u['peran'] === 'customer'):
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
        
        <h5 class="fade-in">📊 Ringkasan Akun Anda</h5>
        <div class="row slide-up">
            <div class="col-md-4">
                <div class="card text-bg-primary interactive-hover">
                    <div class="card-body text-center">
                        <h2 class="card-title"><?= $total_hewan ?></h2>
                        <p class="card-text">Total Hewan Terdaftar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-warning interactive-hover">
                    <div class="card-body text-center">
                        <h2 class="card-title"><?= $stats['Pending'] ?></h2>
                        <p class="card-text">Reservasi Menunggu Konfirmasi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success interactive-hover">
                    <div class="card-body text-center">
                        <h2 class="card-title"><?= $stats['Confirmed'] ?></h2>
                        <p class="card-text">Reservasi Aktif / Terkonfirmasi</p>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fade-in">🚀 Pintasan Cepat</h5>
        <div class="row slide-up">
            <div class="col-md-4">
                <div class="card interactive-hover">
                    <div class="card-body">
                        <h6 class="card-title">➕ Buat Reservasi Baru</h6>
                        <p class="card-text">Pesan tempat untuk hewan peliharaan Anda.</p>
                        <a href="/Pet_Hotel/portal/reservasi.php?action=create" class="btn btn-primary">
                            Buat Reservasi
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card interactive-hover">
                    <div class="card-body">
                        <h6 class="card-title">🐾 Hewan Saya</h6>
                        <p class="card-text">Kelola data hewan peliharaan Anda.</p>
                        <a href="/Pet_Hotel/portal/hewan.php" class="btn btn-primary">
                            Kelola Hewan
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card interactive-hover">
                    <div class="card-body">
                        <h6 class="card-title">👤 Profil Saya</h6>
                        <p class="card-text">Perbarui data kontak & alamat Anda.</p>
                        <a href="/Pet_Hotel/portal/profil.php" class="btn btn-primary">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fade-in">📋 5 Reservasi Terbaru</h5>
        <?php
        $q = $mysqli->query("SELECT r.*, h.nama_hewan 
                            FROM reservasi r 
                            LEFT JOIN hewan h ON r.hewan_id = h.hewan_id 
                            WHERE r.customer_id = $customer_id 
                            ORDER BY r.reservasi_id DESC LIMIT 5");
        
        if ($q->num_rows > 0): ?>
            <div class="slide-up reservasi-table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hewan</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($r = $q->fetch_assoc()): 
                            $status_class = 'status-' . strtolower(str_replace(' ', '-', $r['status_reservasi']));
                        ?>
                            <tr class="interactive-hover">
                                <td data-label="ID"><strong>#<?= $r['reservasi_id'] ?></strong></td>
                                <td data-label="Hewan"><?= htmlspecialchars($r['nama_hewan']) ?></td>
                                <td data-label="Check-in"><?= date('d/m/Y', strtotime($r['tanggal_checkin'])) ?></td>
                                <td data-label="Check-out"><?= date('d/m/Y', strtotime($r['tanggal_checkout'])) ?></td>
                                <td data-label="Status">
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= htmlspecialchars($r['status_reservasi']) ?>
                                    </span>
                                </td>
                                <td data-label="Aksi">
                                    <a href="/Pet_Hotel/portal/monitoring.php?reservasi_id=<?= $r['reservasi_id'] ?>" 
                                       class="btn btn-info btn-sm">
                                        📊 Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card slide-up">
                <div class="card-body text-center no-data">
                    <div class="icon">📋</div>
                    <h6>Belum ada reservasi</h6>
                    <p class="text-muted">Mulai buat reservasi pertama Anda</p>
                    <a href="/Pet_Hotel/portal/reservasi.php?action=create" class="btn btn-success">
                        Buat Reservasi Pertama
                    </a>  
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($u['peran'] === 'dokter'): ?>
        <div class="card slide-up">
            <div class="card-body text-center">
                <h5 class="card-title">👨‍⚕️ Panel Dokter</h5>
                <p class="card-text">Selamat datang di sistem Pet Hotel. Anda dapat mengakses menu yang relevan melalui sidebar.</p>
                <div class="mt-3">
                    <a href="/Pet_Hotel/modules/monitoring.php" class="btn btn-primary me-2">Monitoring Hewan</a>
                    <a href="/Pet_Hotel/modules/laporan.php" class="btn btn-info">Laporan Medis</a>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>