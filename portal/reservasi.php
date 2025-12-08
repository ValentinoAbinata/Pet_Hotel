<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
$u = current_user();

// Pastikan yang akses cuma customer
if ($u['peran'] !== 'customer') {
    flash('Akses ditolak.');
    header('Location: /Pet_Hotel/pages/dashboard.php');
    exit;
}
$customer_id = (int) $u['user_id'];

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];

// --- LOGIKA SIMPAN RESERVASI BARU ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sub = $_POST['__action'] ?? '';
    if ($sub === 'create') {
        $hewan_id = (int) $_POST['hewan_id'];
        $checkin = $mysqli->real_escape_string($_POST['tanggal_checkin']);
        $checkout = $mysqli->real_escape_string($_POST['tanggal_checkout']);
        $status = 'Pending'; 

        // Validasi: Pastikan hewan ini milik customer yang login
        $resHewan = $mysqli->query("SELECT hewan_id FROM hewan WHERE hewan_id = $hewan_id AND customer_id = $customer_id");
        if ($resHewan->num_rows == 0) {
            $errors[] = 'Hewan yang dipilih tidak valid.';
        }

        if (empty($errors)) {
            $sql = "INSERT INTO reservasi (customer_id,hewan_id,tanggal_checkin,tanggal_checkout,status_reservasi) 
                    VALUES ($customer_id,$hewan_id,'$checkin','$checkout','$status')";

            if ($mysqli->query($sql)) {
                $reservasi_id = $mysqli->insert_id;

                // Tambahkan layanan yang dipilih
                if (!empty($_POST['layanan']) && is_array($_POST['layanan'])) {
                    foreach ($_POST['layanan'] as $lid) {
                        $lid = (int) $lid;
                        $r = $mysqli->query("SELECT harga FROM layanan WHERE layanan_id = $lid LIMIT 1");
                        $harga = 0;
                        if ($r && $r->num_rows)
                            $harga = (int) $r->fetch_assoc()['harga'];

                        $mysqli->query("INSERT INTO reservasi_layanan (reservasi_id,layanan_id,harga_saat_reservasi) 
                                        VALUES ($reservasi_id,$lid,$harga)");
                    }
                }
                flash('Reservasi Anda telah dibuat dan sedang menunggu konfirmasi admin.');
                header('Location: reservasi.php?action=list');
                exit;
            } else
                $errors[] = 'Gagal membuat reservasi: ' . $mysqli->error;
        }
    }
}

// Ambil data untuk form dropdown
$hewanRes = $mysqli->query("SELECT hewan_id,nama_hewan FROM hewan WHERE customer_id = $customer_id ORDER BY nama_hewan");
$layananRes = $mysqli->query("SELECT * FROM layanan ORDER BY layanan_id");

include __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="/PET_HOTEL/style/reservasi.css">

<div class="reservasi-container">
    <?php
    $msg = get_flash();
    if ($msg) echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
    foreach ($errors as $e) echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';

    if ($action === 'list'):
        // Query Reservasi
        $q = $mysqli->query("SELECT r.*, h.nama_hewan, 
                            SUM(rl.harga_saat_reservasi) as harga_per_hari, 
                            p.status_pembayaran 
                            FROM reservasi r 
                            LEFT JOIN hewan h ON r.hewan_id = h.hewan_id 
                            LEFT JOIN reservasi_layanan rl ON r.reservasi_id = rl.reservasi_id 
                            LEFT JOIN pembayaran p ON r.reservasi_id = p.reservasi_id
                            WHERE r.customer_id = $customer_id 
                            GROUP BY r.reservasi_id 
                            ORDER BY r.reservasi_id DESC");
        ?>
        <div class="action-header">
            <h3>Reservasi Saya</h3>
            <a class="btn btn-success" href="reservasi.php?action=create">Buat Reservasi Baru</a>
        </div>
        
        <?php if ($q->num_rows > 0): ?>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hewan</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Status</th>
                                <th>Total Biaya</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($r = $q->fetch_assoc()): ?>
                                <?php
                                // --- PERBAIKAN HITUNG TOTAL BIAYA ---
                                // 1. Hitung durasi hari
                                $in = new DateTime($r['tanggal_checkin']);
                                $out = new DateTime($r['tanggal_checkout']);
                                $diff = $in->diff($out);
                                $durasi = max(1, $diff->days); // Minimal 1 hari
                                
                                // 2. Ambil harga per hari (dari query SUM)
                                $harga_dasar = (int)$r['harga_per_hari'];
                                
                                // 3. Kalikan
                                $total_final = $harga_dasar * $durasi;
                                // ------------------------------------
                                ?>
                                <tr>
                                    <td><?= $r['reservasi_id'] ?></td>
                                    <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($r['tanggal_checkin'])) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($r['tanggal_checkout'])) ?></td>
                                    <td>
                                        <?php
                                        $status = htmlspecialchars($r['status_reservasi']);
                                        $badge = 'bg-secondary';
                                        if ($status == 'Confirmed') $badge = 'bg-success';
                                        if ($status == 'Completed') $badge = 'bg-primary';
                                        if ($status == 'Cancelled') $badge = 'bg-danger';
                                        if ($status == 'Pending') $badge = 'bg-warning';
                                        echo "<span class=\"badge $badge\">$status</span>";
                                        ?>
                                    </td>
                                    
                                    <td class="price">
                                        <strong>Rp<?= number_format($total_final) ?></strong><br>
                                        <small class="text-muted" style="font-size: 0.75rem;">(<?= $durasi ?> Hari)</small>
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="monitoring.php?reservasi_id=<?= $r['reservasi_id'] ?>" class="btn btn-sm btn-info text-nowrap">
                                                📊 Laporan
                                            </a>

                                            <?php if ($r['status_reservasi'] === 'Pending'): ?>
                                                <?php if (empty($r['status_pembayaran'])): ?>
                                                    <a href="bayar.php?id=<?= $r['reservasi_id'] ?>" class="btn btn-sm btn-warning text-nowrap">
                                                        💸 Bayar
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-info text-dark text-nowrap">
                                                        ⏳ Cek Pembayaran
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="icon">📅</div>
                <h5>Belum ada reservasi</h5>
                <p class="text-muted">Mulai buat reservasi pertama Anda</p>
                <a href="reservasi.php?action=create" class="btn btn-success">Buat Reservasi Pertama</a>
            </div>
        <?php endif; ?>
        
    <?php elseif ($action === 'create'): ?>
        <div class="form-container">
            <h3>Buat Reservasi Baru</h3>
            <p>Silakan isi detail di bawah ini untuk memesan tempat.</p>
            
            <form method="post">
                <input type="hidden" name="__action" value="create">

                <div class="mb-4">
                    <label class="form-label">Hewan Peliharaan</label>
                    <select name="hewan_id" class="form-select" required>
                        <option value="">— Pilih hewan Anda —</option>
                        <?php $hewanRes->data_seek(0);
                        while ($h = $hewanRes->fetch_assoc()): ?>
                            <option value="<?= $h['hewan_id'] ?>"><?= htmlspecialchars($h['nama_hewan']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <a href="hewan.php?action=create" class="add-pet-link">
                        <span>➕</span>
                        Tambah data hewan dulu
                    </a>
                </div>

                <div class="mb-4 row">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Check-in</label>
                        <input type="datetime-local" name="tanggal_checkin" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Check-out</label>
                        <input type="datetime-local" name="tanggal_checkout" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Layanan Tambahan (Opsional)</label>
                    <div class="layanan-card">
                        <div class="layanan-card-header">
                            <strong>Pilih Layanan yang Diinginkan</strong>
                        </div>
                        <div class="layanan-card-body">
                            <div class="layanan-grid">
                                <?php $layananRes->data_seek(0);
                                while ($l = $layananRes->fetch_assoc()): ?>
                                    <div class="form-check-enhanced">
                                        <label class="form-check-label-enhanced">
                                            <div class="layanan-details">
                                                <span class="layanan-nama"><?= htmlspecialchars($l['nama_layanan']) ?></span>
                                                <span class="layanan-price">Rp<?= number_format($l['harga'], 0, ',', '.') ?></span>
                                            </div>
                                            <input class="form-check-input" type="checkbox" name="layanan[]" value="<?= $l['layanan_id'] ?>" id="layanan_<?= $l['layanan_id'] ?>">
                                        </label>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a class="btn btn-secondary" href="reservasi.php?action=list">
                        ✕ Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        📧 Kirim Permintaan Reservasi
                    </button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Aksi tidak dikenal.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>