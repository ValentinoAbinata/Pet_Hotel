<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
require_role(['admin']); // Hanya admin

$reservasi_id = isset($_GET['reservasi_id']) ? (int)$_GET['reservasi_id'] : 0;
$total_dari_url = isset($_GET['total']) ? (int)$_GET['total'] : 0;
$errors = [];

if ($reservasi_id == 0) {
    flash('Reservasi ID tidak valid.');
    header('Location: reservasi.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rid = (int)$_POST['reservasi_id'];
    $total_biaya = (int)$_POST['total_biaya'];
    $metode = $mysqli->real_escape_string($_POST['metode_pembayaran']);
    $status = $mysqli->real_escape_string($_POST['status_pembayaran']);

    $sql = "INSERT INTO pembayaran (reservasi_id, total_biaya, metode_pembayaran, status_pembayaran, tanggal_transaksi)
            VALUES ($rid, $total_biaya, '$metode', '$status', NOW())
            ON DUPLICATE KEY UPDATE
              total_biaya = $total_biaya,
              metode_pembayaran = '$metode',
              status_pembayaran = '$status',
              tanggal_transaksi = NOW()";

if ($mysqli->query($sql)) {
  // --- MULAI NOTIFIKASI ---
  // 1. Cari tahu siapa pemilik reservasi ini
  $qCust = $mysqli->query("SELECT customer_id FROM reservasi WHERE reservasi_id = $rid");
  if($qCust && $qCust->num_rows > 0) {
      $custData = $qCust->fetch_assoc();
      $target_user = $custData['customer_id'];
      
      // 2. Siapkan data notifikasi
      $judul = "Pembayaran " . $status; // Misal: Pembayaran Paid
      $pesan = "Pembayaran untuk reservasi #$rid telah diperbarui menjadi $status.";
      $url   = "/Pet_Hotel/portal/reservasi.php";

      // 3. Kirim
      send_notification($target_user, $judul, $pesan, $url);
  }
  // --- SELESAI NOTIFIKASI ---

  flash('Status pembayaran berhasil disimpan.');
  header('Location: reservasi.php');
  exit;
}
}

// Ambil data reservasi untuk info
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

// Cek apakah sudah ada data pembayaran sebelumnya
$data_pembayaran = ['total_biaya' => $total_dari_url, 'metode_pembayaran' => '', 'status_pembayaran' => 'Pending'];
$resPay = $mysqli->query("SELECT * FROM pembayaran WHERE reservasi_id = $reservasi_id LIMIT 1");
if ($resPay && $resPay->num_rows > 0) {
    $data_pembayaran = $resPay->fetch_assoc();
}

include __DIR__ . '/../includes/header.php';
$msg = get_flash(); if ($msg) echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e) echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';
?>

<h3>Catat Pembayaran untuk Reservasi #<?= $reservasi_id ?></h3>
<p>
  <strong>Customer:</strong> <?= htmlspecialchars($data_reservasi['nama_lengkap']) ?><br>
  <strong>Hewan:</strong> <?= htmlspecialchars($data_reservasi['nama_hewan']) ?><br>
  <strong>Total Tagihan Reservasi:</strong> Rp<?= number_format($total_dari_url) ?>
</p>

<div class="card">
  <div class="card-body">
    <form method="post">
      <input type="hidden" name="reservasi_id" value="<?= $reservasi_id ?>">
      
      <div class="mb-3">
        <label class="form-label">Total Biaya Dibayar</label>
        <input type="number" name="total_biaya" class_ ="form-control" value="<?= (int)$data_pembayaran['total_biaya'] ?>" required>
        <small>Total ini bisa berbeda dari tagihan reservasi jika ada biaya tambahan.</small>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Metode Pembayaran</label>
        <select name="metode_pembayaran" class="form-select" required>
          <option value="Cash" <?= $data_pembayaran['metode_pembayaran'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
          <option value="Transfer Bank" <?= $data_pembayaran['metode_pembayaran'] == 'Transfer Bank' ? 'selected' : '' ?>>Transfer Bank</option>
          <option value="Credit Card" <?= $data_pembayaran['metode_pembayaran'] == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
          <option value="QRIS" <?= $data_pembayaran['metode_pembayaran'] == 'QRIS' ? 'selected' : '' ?>>QRIS</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Status Pembayaran</label>
        <select name="status_pembayaran" class="form-select" required>
          <option value="Pending" <?= $data_pembayaran['status_pembayaran'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
          <option value="Paid" <?= $data_pembayaran['status_pembayaran'] == 'Paid' ? 'selected' : '' ?>>Paid (Lunas)</option>
          <option value="Failed" <?= $data_pembayaran['status_pembayaran'] == 'Failed' ? 'selected' : '' ?>>Failed</option>
        </select>
      </div>
      
      <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
      <a class="btn btn-link" href="reservasi.php">Batal</a>
    </form>
  </div>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
?>