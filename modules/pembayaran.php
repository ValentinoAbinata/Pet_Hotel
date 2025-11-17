<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
require_role(['admin']);

include __DIR__ . '/../includes/header.php';
$msg = get_flash();
if ($msg)
    echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';

$q = $mysqli->query("SELECT p.*, r.hewan_id, h.nama_hewan, u.nama_lengkap 
                    FROM pembayaran p 
                    LEFT JOIN reservasi r ON p.reservasi_id = r.reservasi_id 
                    LEFT JOIN hewan h ON r.hewan_id = h.hewan_id 
                    LEFT JOIN users u ON r.customer_id = u.user_id 
                    ORDER BY p.tanggal_transaksi DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Pembayaran</h3>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Reservasi</th>
            <th>Customer</th>
            <th>Total Biaya</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($r = $q->fetch_assoc()): ?>
            <tr>
                <td><?= $r['pembayaran_id'] ?></td>
                <td>#<?= $r['reservasi_id'] ?> (<?= htmlspecialchars($r['nama_hewan']) ?>)</td>
                <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                <td>Rp<?= number_format($r['total_biaya']) ?></td>
                <td><?= htmlspecialchars($r['metode_pembayaran']) ?></td>
                <td><?= htmlspecialchars($r['status_pembayaran']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($r['tanggal_transaksi'])) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
include __DIR__ . '/../includes/footer.php';
?>