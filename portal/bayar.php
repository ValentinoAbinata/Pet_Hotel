<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
$u = current_user();

$rid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

// Cek apakah reservasi ini milik customer yang login
$cek = $mysqli->query("SELECT r.*, h.nama_hewan 
                       FROM reservasi r 
                       JOIN hewan h ON r.hewan_id = h.hewan_id 
                       WHERE r.reservasi_id = $rid AND r.customer_id = " . $u['user_id']);

if (!$cek || $cek->num_rows == 0) {
    flash('Reservasi tidak ditemukan.');
    header('Location: reservasi.php');
    exit;
}
$r = $cek->fetch_assoc();

// Hitung total biaya (jika ada layanan tambahan)
$total_tagihan = 0;
// 1. Hitung durasi hari
$d1 = new DateTime($r['tanggal_checkin']);
$d2 = new DateTime($r['tanggal_checkout']);
$diff = $d1->diff($d2);
$durasi = max(1, $diff->days); // Minimal 1 hari

// 2. Ambil harga layanan (Room + Services)
$qLayanan = $mysqli->query("SELECT sum(harga_saat_reservasi) as total FROM reservasi_layanan WHERE reservasi_id = $rid");
$dataLayanan = $qLayanan->fetch_assoc();
$harga_per_hari = (int)$dataLayanan['total'];

$total_tagihan = $harga_per_hari * $durasi;


// PROSES UPLOAD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = $_POST['metode_pembayaran'];
    
    // Handle File Upload
    $file_path = null;
    if (!empty($_FILES['bukti']['name'])) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        
        if (in_array(strtolower($ext), $allowed)) {
            $target_dir = __DIR__ . '/../uploads/bukti/';
            if (!is_dir($target_dir)) @mkdir($target_dir, 0777, true);
            
            $filename = 'pay_' . $rid . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target_dir . $filename)) {
                $file_path = 'uploads/bukti/' . $filename;
            } else {
                $errors[] = 'Gagal upload gambar.';
            }
        } else {
            $errors[] = 'Format file harus JPG, PNG, atau PDF.';
        }
    } else {
        $errors[] = 'Wajib upload bukti pembayaran.';
    }

    if (empty($errors)) {
        // Simpan ke tabel pembayaran (Status awal: Pending/Menunggu Konfirmasi Admin)
        // Gunakan ON DUPLICATE KEY UPDATE agar kalau upload ulang, data lama terupdate
        $sql = "INSERT INTO pembayaran (reservasi_id, total_biaya, metode_pembayaran, status_pembayaran, bukti_bayar, tanggal_transaksi)
                VALUES ($rid, $total_tagihan, '$metode', 'Pending', '$file_path', NOW())
                ON DUPLICATE KEY UPDATE 
                metode_pembayaran='$metode', bukti_bayar='$file_path', tanggal_transaksi=NOW(), status_pembayaran='Pending'";

        if ($mysqli->query($sql)) {
            flash('Bukti pembayaran berhasil dikirim. Mohon tunggu konfirmasi admin.');
            header('Location: reservasi.php');
            exit;
        } else {
            $errors[] = 'Database error: ' . $mysqli->error;
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <div class="card shadow" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Konfirmasi Pembayaran #<?= $rid ?></h5>
        </div>
        <div class="card-body">
            
            <?php foreach($errors as $e) echo "<div class='alert alert-danger'>$e</div>"; ?>

            <div class="alert alert-info">
                <strong>Total Tagihan: Rp<?= number_format($total_tagihan) ?></strong><br>
                Silakan transfer ke rekening berikut:<br>
                🏦 <strong>BCA: 123-456-7890</strong> (a.n Pet Hotel)<br>
                🏦 <strong>Mandiri: 987-654-3210</strong> (a.n Pet Hotel)
            </div>

            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Metode Transfer</label>
                    <select name="metode_pembayaran" class="form-select">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="QRIS">QRIS</option>
                        <option value="E-Wallet">E-Wallet (OVO/GoPay)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Bukti Transfer</label>
                    <input type="file" name="bukti" class="form-control" required accept="image/*,.pdf">
                    <small class="text-muted">Format: JPG, PNG, PDF. Max 2MB.</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">📤 Kirim Bukti Pembayaran</button>
                    <a href="reservasi.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>