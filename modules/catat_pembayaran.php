<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
require_role(['admin']); // Hanya admin yang boleh akses

$reservasi_id = isset($_GET['reservasi_id']) ? (int)$_GET['reservasi_id'] : 0;
$total_dari_url = isset($_GET['total']) ? (int)$_GET['total'] : 0;
$errors = [];

if ($reservasi_id == 0) {
    flash('Reservasi ID tidak valid.');
    header('Location: reservasi.php');
    exit;
}

// --- PROSES SIMPAN DATA (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rid = (int)$_POST['reservasi_id'];
    $total_biaya = (int)$_POST['total_biaya'];
    $metode = $mysqli->real_escape_string($_POST['metode_pembayaran']);
    $status = $mysqli->real_escape_string($_POST['status_pembayaran']);

    // Query Insert atau Update jika data sudah ada
    $sql = "INSERT INTO pembayaran (reservasi_id, total_biaya, metode_pembayaran, status_pembayaran, tanggal_transaksi)
            VALUES ($rid, $total_biaya, '$metode', '$status', NOW())
            ON DUPLICATE KEY UPDATE
              total_biaya = $total_biaya,
              metode_pembayaran = '$metode',
              status_pembayaran = '$status',
              tanggal_transaksi = NOW()";

    if ($mysqli->query($sql)) {
        // === LOGIKA NOTIFIKASI OTOMATIS ===
        // 1. Cari tahu siapa pemilik reservasi ini
        $qCust = $mysqli->query("SELECT customer_id FROM reservasi WHERE reservasi_id = $rid");
        if ($qCust && $qCust->num_rows > 0) {
            $custData = $qCust->fetch_assoc();
            $target_user = $custData['customer_id'];
            
            // 2. Siapkan pesan notifikasi
            $judul = "Update Pembayaran";
            $pesan = "Status pembayaran untuk Reservasi #$rid telah diperbarui menjadi: $status.";
            $url   = "/Pet_Hotel/portal/reservasi.php";

            // 3. Kirim notifikasi (Pastikan fungsi tersedia)
            if (function_exists('send_notification')) {
                send_notification($target_user, $judul, $pesan, $url);
            }
        }
        
        // Update juga status di tabel reservasi jika sudah Lunas (Paid)
        if ($status === 'Paid') {
            $mysqli->query("UPDATE reservasi SET status_reservasi = 'Confirmed' WHERE reservasi_id = $rid AND status_reservasi = 'Pending'");
        }
        // ===================================

        flash('Status pembayaran berhasil disimpan.');
        header('Location: reservasi.php');
        exit;
    } else {
        $errors[] = 'Gagal menyimpan pembayaran: ' . $mysqli->error;
    }
}

// --- AMBIL DATA UNTUK DITAMPILKAN ---

// 1. Ambil detail reservasi (Nama Hewan, Pemilik)
$res = $mysqli->query("SELECT r.*, h.nama_hewan, u.nama_lengkap 
                      FROM reservasi r 
                      LEFT JOIN hewan h ON r.hewan_id = h.hewan_id 
                      LEFT JOIN users u ON r.customer_id = u.user_id
                      WHERE r.reservasi_id = $reservasi_id LIMIT 1");

if (!$res || $res->num_rows == 0) {
    flash('Reservasi tidak ditemukan.');
    header('Location: reservasi.php');
    exit;
}
$data_reservasi = $res->fetch_assoc();

// 2. Ambil data pembayaran jika sudah ada (termasuk Bukti Foto)
$data_pembayaran = [
    'total_biaya' => $total_dari_url, 
    'metode_pembayaran' => '', 
    'status_pembayaran' => 'Pending',
    'bukti_bayar' => null
];

$resPay = $mysqli->query("SELECT * FROM pembayaran WHERE reservasi_id = $reservasi_id LIMIT 1");
if ($resPay && $resPay->num_rows > 0) {
    $data_pembayaran = $resPay->fetch_assoc();
}

include __DIR__ . '/../includes/header.php';
$msg = get_flash(); 
if ($msg) echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e) echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';
?>

<h3>Catat Pembayaran untuk Reservasi #<?= $reservasi_id ?></h3>
<div class="row mb-3">
    <div class="col-md-6">
        <p>
          <strong>Customer:</strong> <?= htmlspecialchars($data_reservasi['nama_lengkap']) ?><br>
          <strong>Hewan:</strong> <?= htmlspecialchars($data_reservasi['nama_hewan']) ?><br>
          <strong>Total Tagihan Awal:</strong> Rp<?= number_format($total_dari_url) ?>
        </p>
    </div>
</div>

<div class="card shadow">
  <div class="card-body">
  
    <?php if (!empty($data_pembayaran['bukti_bayar'])): ?>
        <div class="alert alert-warning">
            <strong>📸 Bukti Pembayaran dari Customer:</strong><br>
            <div class="mt-2">
                <a href="/Pet_Hotel/<?= htmlspecialchars($data_pembayaran['bukti_bayar']) ?>" target="_blank">
                    <img src="/Pet_Hotel/<?= htmlspecialchars($data_pembayaran['bukti_bayar']) ?>" 
                         style="max-width: 100%; height: auto; max-height: 300px; border: 1px solid #ccc;" class="img-fluid rounded">
                </a>
            </div>
            <div class="mt-1">
                <small class="text-muted">Klik gambar untuk memperbesar.</small>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary d-flex align-items-center">
            <span class="me-2">ℹ️</span> 
            <div>Belum ada bukti pembayaran yang diupload oleh customer.</div>
        </div>
    <?php endif; ?>
    <hr>

    <form method="post">
      <input type="hidden" name="reservasi_id" value="<?= $reservasi_id ?>">
      
      <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Total Biaya (Rp)</label>
            <input type="number" name="total_biaya" class="form-control" 
                   value="<?= (int)$data_pembayaran['total_biaya'] ?>" required>
            <small class="text-muted">Pastikan nominal sesuai dengan mutasi bank.</small>
          </div>
          
          <div class="col-md-6 mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-select" required>
              <option value="Transfer Bank" <?= $data_pembayaran['metode_pembayaran'] == 'Transfer Bank' ? 'selected' : '' ?>>Transfer Bank</option>
              <option value="Cash" <?= $data_pembayaran['metode_pembayaran'] == 'Cash' ? 'selected' : '' ?>>Cash (Tunai)</option>
              <option value="QRIS" <?= $data_pembayaran['metode_pembayaran'] == 'QRIS' ? 'selected' : '' ?>>QRIS</option>
              <option value="E-Wallet" <?= $data_pembayaran['metode_pembayaran'] == 'E-Wallet' ? 'selected' : '' ?>>E-Wallet</option>
            </select>
          </div>
      </div>

      <div class="mb-4">
        <label class="form-label">Status Pembayaran</label>
        <select name="status_pembayaran" class="form-select bg-light border-primary" required>
          <option value="Pending" <?= $data_pembayaran['status_pembayaran'] == 'Pending' ? 'selected' : '' ?>>⏳ Pending (Belum Lunas)</option>
          <option value="Paid" <?= $data_pembayaran['status_pembayaran'] == 'Paid' ? 'selected' : '' ?>>✅ Paid (Lunas)</option>
          <option value="Failed" <?= $data_pembayaran['status_pembayaran'] == 'Failed' ? 'selected' : '' ?>>❌ Failed (Gagal/Ditolak)</option>
        </select>
        <div class="form-text">Jika diubah ke <strong>Paid</strong>, status reservasi akan otomatis menjadi Confirmed.</div>
      </div>
      
      <div class="d-flex justify-content-between">
          <a class="btn btn-secondary" href="reservasi.php">Kembali</a>
          <button type="submit" class="btn btn-primary px-4">💾 Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
?>