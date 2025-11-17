<?php
// modules/reservasi.php - single-file CRUD reservasi + reservasi_layanan handling
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
require_role(['admin']);

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sub = $_POST['__action'] ?? '';
    if ($sub === 'create') {
        $customer_id = (int)$_POST['customer_id'];
        $hewan_id = (int)$_POST['hewan_id'];
        $checkin = $mysqli->real_escape_string($_POST['tanggal_checkin']);
        $checkout = $mysqli->real_escape_string($_POST['tanggal_checkout']);
        $status = $mysqli->real_escape_string($_POST['status_reservasi'] ?? 'Pending');

        $sql = "INSERT INTO reservasi (customer_id,hewan_id,tanggal_checkin,tanggal_checkout,status_reservasi) VALUES ($customer_id,$hewan_id,'$checkin','$checkout','$status')";
        if ($mysqli->query($sql)) {
            $reservasi_id = $mysqli->insert_id;
            if (!empty($_POST['layanan']) && is_array($_POST['layanan'])) {
                foreach ($_POST['layanan'] as $lid) {
                    $lid = (int)$lid;
                    $r = $mysqli->query("SELECT harga FROM layanan WHERE layanan_id = $lid LIMIT 1");
                    $harga = 0;
                    if ($r && $r->num_rows) $harga = (int)$r->fetch_assoc()['harga'];
                    $mysqli->query("INSERT INTO reservasi_layanan (reservasi_id,layanan_id,harga_saat_reservasi) VALUES ($reservasi_id,$lid,$harga)");
                }
            }
            flash('Reservasi dibuat.');
            header('Location: reservasi.php?action=list');
            exit;
        } else $errors[] = 'Gagal: '.$mysqli->error;
    } elseif ($sub === 'update') {
        $rid = (int)$_POST['reservasi_id'];
        $customer_id = (int)$_POST['customer_id'];
        $hewan_id = (int)$_POST['hewan_id'];
        $checkin = $mysqli->real_escape_string($_POST['tanggal_checkin']);
        $checkout = $mysqli->real_escape_string($_POST['tanggal_checkout']);
        $status = $mysqli->real_escape_string($_POST['status_reservasi'] ?? 'Pending');
        $sql = "UPDATE reservasi SET customer_id=$customer_id,hewan_id=$hewan_id,tanggal_checkin='$checkin',tanggal_checkout='$checkout',status_reservasi='$status' WHERE reservasi_id = $rid";
        if ($mysqli->query($sql)) {
            $mysqli->query("DELETE FROM reservasi_layanan WHERE reservasi_id = $rid");
            if (!empty($_POST['layanan']) && is_array($_POST['layanan'])) {
                foreach ($_POST['layanan'] as $lid) {
                    $lid = (int)$lid;
                    $r = $mysqli->query("SELECT harga FROM layanan WHERE layanan_id = $lid LIMIT 1");
                    $harga = 0;
                    if ($r && $r->num_rows) $harga = (int)$r->fetch_assoc()['harga'];
                    $mysqli->query("INSERT INTO reservasi_layanan (reservasi_id,layanan_id,harga_saat_reservasi) VALUES ($rid,$lid,$harga)");
                }
            }
            flash('Perubahan disimpan.');
            header('Location: reservasi.php?action=list');
            exit;
        } else $errors[] = 'Gagal: '.$mysqli->error;
    }
}

if ($action === 'delete' && $id) {
    $mysqli->query("DELETE FROM reservasi_layanan WHERE reservasi_id = $id");
    $mysqli->query("DELETE FROM pembayaran WHERE reservasi_id = $id");
    $mysqli->query("DELETE FROM reservasi WHERE reservasi_id = $id");
    flash('Reservasi dihapus.');
    header('Location: reservasi.php?action=list');
    exit;
}

$hewanRes = $mysqli->query("SELECT h.hewan_id,h.nama_hewan,u.nama_lengkap as pemilik FROM hewan h LEFT JOIN users u ON h.customer_id = u.user_id ORDER BY h.nama_hewan");
$customerRes = $mysqli->query("SELECT user_id,nama_lengkap FROM users WHERE peran='customer' ORDER BY nama_lengkap");
$layananRes = $mysqli->query("SELECT * FROM layanan ORDER BY layanan_id");

include __DIR__ . '/../includes/header.php';
$msg = get_flash(); if ($msg) echo '<div class="alert alert-info">'.htmlspecialchars($msg).'</div>';
foreach ($errors as $e) echo '<div class="alert alert-danger">'.htmlspecialchars($e).'</div>';

if ($action === 'list'):
    $q = $mysqli->query("SELECT r.*, u.nama_lengkap as customer_name, h.nama_hewan FROM reservasi r LEFT JOIN users u ON r.customer_id = u.user_id LEFT JOIN hewan h ON r.hewan_id = h.hewan_id ORDER BY r.reservasi_id DESC");
    ?>
    <div class="d-flex justify-content-between mb-3">
      <h3>Reservasi</h3>
      <a class="btn btn-success" href="reservasi.php?action=create">Tambah Reservasi</a>
    </div>
    <table class="table">
      <thead><tr><th>ID</th><th>Hewan</th><th>Customer</th><th>Check-in</th><th>Check-out</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php while ($r = $q->fetch_assoc()): ?>
        <tr>
          <td><?= $r['reservasi_id'] ?></td>
          <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
          <td><?= htmlspecialchars($r['customer_name']) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($r['tanggal_checkin'])) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($r['tanggal_checkout'])) ?></td>
          <td><?= htmlspecialchars($r['status_reservasi']) ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="reservasi.php?action=edit&id=<?= $r['reservasi_id'] ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="reservasi.php?action=delete&id=<?= $r['reservasi_id'] ?>" onclick="return confirm('Hapus reservasi?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
<?php
elseif ($action === 'create'):
?>
  <h3>Buat Reservasi</h3>
  <form method="post">
    <input type="hidden" name="__action" value="create">
    <div class="mb-3"><label class="form-label">Customer</label><select name="customer_id" class="form-select" required><?php $customerRes->data_seek(0); while($c=$customerRes->fetch_assoc()): ?><option value="<?= $c['user_id'] ?>"><?= htmlspecialchars($c['nama_lengkap']) ?></option><?php endwhile; ?></select></div>
    <div class="mb-3"><label class="form-label">Hewan</label><select name="hewan_id" class="form-select" required><?php $hewanRes->data_seek(0); while($h=$hewanRes->fetch_assoc()): ?><option value="<?= $h['hewan_id'] ?>"><?= htmlspecialchars($h['nama_hewan'].' — '.$h['pemilik']) ?></option><?php endwhile; ?></select></div>
    <div class="mb-3 row"><div class="col"><label class="form-label">Tanggal Check-in</label><input type="datetime-local" name="tanggal_checkin" class="form-control" required></div><div class="col"><label class="form-label">Tanggal Check-out</label><input type="datetime-local" name="tanggal_checkout" class="form-control" required></div></div>
    <div class="mb-3"><label class="form-label">Layanan (pilih satu atau lebih)</label><?php $layananRes->data_seek(0); while ($l = $layananRes->fetch_assoc()): ?><div class="form-check"><input class="form-check-input" type="checkbox" name="layanan[]" value="<?= $l['layanan_id'] ?>"><label class="form-check-label"><?= htmlspecialchars($l['nama_layanan'].' — Rp'.number_format($l['harga'])) ?></label></div><?php endwhile; ?></div>
    <div class="mb-3"><label class="form-label">Status</label><select name="status_reservasi" class="form-select"><option value="Pending">Pending</option><option value="Confirmed">Confirmed</option><option value="Completed">Completed</option><option value="Cancelled">Cancelled</option></select></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="reservasi.php?action=list">Batal</a>
  </form>
<?php
elseif ($action === 'edit' && $id):
    $res = $mysqli->query("SELECT * FROM reservasi WHERE reservasi_id = $id LIMIT 1");
    if (!$res || $res->num_rows==0) { flash('Reservasi tidak ditemukan.'); header('Location: reservasi.php?action=list'); exit; }
    $data = $res->fetch_assoc();
    $selected = [];
    $rl = $mysqli->query("SELECT layanan_id FROM reservasi_layanan WHERE reservasi_id = $id");
    while ($rr = $rl->fetch_assoc()) $selected[] = $rr['layanan_id'];
?>
  <h3>Edit Reservasi</h3>
  <form method="post">
    <input type="hidden" name="__action" value="update">
    <input type="hidden" name="reservasi_id" value="<?= (int)$data['reservasi_id'] ?>">
    <div class="mb-3"><label class="form-label">Customer</label><select name="customer_id" class="form-select" required><?php $customerRes->data_seek(0); while($c=$customerRes->fetch_assoc()): ?><option value="<?= $c['user_id'] ?>" <?= $c['user_id']==$data['customer_id']?'selected':'' ?>><?= htmlspecialchars($c['nama_lengkap']) ?></option><?php endwhile; ?></select></div>
    <div class="mb-3"><label class="form-label">Hewan</label><select name="hewan_id" class="form-select" required><?php $hewanRes->data_seek(0); while($h=$hewanRes->fetch_assoc()): ?><option value="<?= $h['hewan_id'] ?>" <?= $h['hewan_id']==$data['hewan_id']?'selected':'' ?>><?= htmlspecialchars($h['nama_hewan'].' — '.$h['pemilik']) ?></option><?php endwhile; ?></select></div>
    <div class="mb-3 row"><div class="col"><label class="form-label">Tanggal Check-in</label><input type="datetime-local" name="tanggal_checkin" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_checkin'])) ?>" required></div><div class="col"><label class="form-label">Tanggal Check-out</label><input type="datetime-local" name="tanggal_checkout" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_checkout'])) ?>" required></div></div>
    <div class="mb-3"><label class="form-label">Layanan</label><?php $layananRes->data_seek(0); while ($l = $layananRes->fetch_assoc()): ?><div class="form-check"><input class="form-check-input" type="checkbox" name="layanan[]" value="<?= $l['layanan_id'] ?>" <?= in_array($l['layanan_id'],$selected)?'checked':'' ?>><label class="form-check-label"><?= htmlspecialchars($l['nama_layanan'].' — Rp'.number_format($l['harga'])) ?></label></div><?php endwhile; ?></div>
    <div class="mb-3"><label class="form-label">Status</label><select name="status_reservasi" class="form-select"><option value="Pending" <?= $data['status_reservasi']=='Pending'?'selected':'' ?>>Pending</option><option value="Confirmed" <?= $data['status_reservasi']=='Confirmed'?'selected':'' ?>>Confirmed</option><option value="Completed" <?= $data['status_reservasi']=='Completed'?'selected':'' ?>>Completed</option><option value="Cancelled" <?= $data['status_reservasi']=='Cancelled'?'selected':'' ?>>Cancelled</option></select></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="reservasi.php?action=list">Batal</a>
  </form>
<?php
else:
  echo '<div class="alert alert-warning">Aksi tidak dikenal.</div>';
endif;

include __DIR__ . '/../includes/footer.php';
