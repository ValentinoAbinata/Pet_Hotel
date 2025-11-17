<?php
// modules/hewan.php - Single-file CRUD hewan
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
require_role(['admin']);

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sub = $_POST['__action'] ?? '';
  if ($sub === 'create') {
    $cust = (int) $_POST['customer_id'];
    $nama = $mysqli->real_escape_string($_POST['nama_hewan']);
    $jenis = $mysqli->real_escape_string($_POST['jenis_hewan']);
    $ras = $mysqli->real_escape_string($_POST['ras']);
    $tgll = !empty($_POST['tanggal_lahir']) ? $mysqli->real_escape_string(date('Y-m-d', strtotime($_POST['tanggal_lahir']))) : null;
    $catatan = $mysqli->real_escape_string($_POST['catatan_pemilik']);
    $sql = "INSERT INTO hewan (customer_id,nama_hewan,jenis_hewan,ras,tanggal_lahir,catatan_pemilik) VALUES ($cust,'$nama','$jenis','$ras'," . ($tgll ? "'$tgll'" : "NULL") . ",'$catatan')";
    if ($mysqli->query($sql)) {
      flash('Hewan ditambahkan.');
      header('Location: hewan.php?action=list');
      exit;
    } else
      $errors[] = 'Gagal menyimpan: ' . $mysqli->error;
  } elseif ($sub === 'update') {
    $hid = (int) $_POST['hewan_id'];
    $cust = (int) $_POST['customer_id'];
    $nama = $mysqli->real_escape_string($_POST['nama_hewan']);
    $jenis = $mysqli->real_escape_string($_POST['jenis_hewan']);
    $ras = $mysqli->real_escape_string($_POST['ras']);
    $tgll = !empty($_POST['tanggal_lahir']) ? $mysqli->real_escape_string(date('Y-m-d', strtotime($_POST['tanggal_lahir']))) : null;
    $catatan = $mysqli->real_escape_string($_POST['catatan_pemilik']);
    $sql = "UPDATE hewan SET customer_id=$cust, nama_hewan='$nama', jenis_hewan='$jenis', ras='$ras', tanggal_lahir=" . ($tgll ? "'$tgll'" : "NULL") . ", catatan_pemilik='$catatan' WHERE hewan_id = $hid";
    if ($mysqli->query($sql)) {
      flash('Perubahan disimpan.');
      header('Location: hewan.php?action=list');
      exit;
    } else
      $errors[] = 'Gagal menyimpan: ' . $mysqli->error;
  }
}

if ($action === 'delete' && $id) {
  $mysqli->query("DELETE FROM hewan WHERE hewan_id = $id");
  flash('Hewan dihapus.');
  header('Location: hewan.php?action=list');
  exit;
}

$pelangganRes = $mysqli->query("SELECT user_id,nama_lengkap FROM users WHERE peran='customer' ORDER BY nama_lengkap");

include __DIR__ . '/../includes/header.php';
$msg = get_flash();
if ($msg)
  echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e)
  echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';

if ($action === 'list'):
  $q = $mysqli->query("SELECT h.*, u.nama_lengkap as pemilik FROM hewan h LEFT JOIN users u ON h.customer_id = u.user_id ORDER BY h.hewan_id DESC");
  ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Hewan</h3>
    <a class="btn btn-success" href="hewan.php?action=create">Tambah Hewan</a>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Jenis</th>
        <th>Ras</th>
        <th>Tgl Lahir</th>
        <th>Pemilik</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($r = $q->fetch_assoc()): ?>
        <tr>
          <td><?= $r['hewan_id'] ?></td>
          <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
          <td><?= htmlspecialchars($r['jenis_hewan']) ?></td>
          <td><?= htmlspecialchars($r['ras']) ?></td>
          <td><?= $r['tanggal_lahir'] ? date('d/m/Y', strtotime($r['tanggal_lahir'])) : '' ?></td>
          <td><?= htmlspecialchars($r['pemilik']) ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="hewan.php?action=edit&id=<?= $r['hewan_id'] ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="hewan.php?action=delete&id=<?= $r['hewan_id'] ?>"
              onclick="return confirm('Hapus hewan ini?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php
elseif ($action === 'create'):
  ?>
  <h3>Tambah Hewan</h3>
  <form method="post">
    <input type="hidden" name="__action" value="create">
    <div class="mb-3">
      <label class="form-label">Pemilik</label>
      <select name="customer_id" class="form-select" required>
        <?php $pelangganRes->data_seek(0);
        while ($p = $pelangganRes->fetch_assoc()): ?>
          <option value="<?= $p['user_id'] ?>"><?= htmlspecialchars($p['nama_lengkap']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3"><label class="form-label">Nama hewan</label><input name="nama_hewan" class="form-control" required>
    </div>
    <div class="mb-3"><label class="form-label">Jenis hewan</label><input name="jenis_hewan" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Ras</label><input name="ras" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Tanggal lahir</label><input name="tanggal_lahir" type="date"
        class="form-control"></div>
    <div class="mb-3"><label class="form-label">Catatan pemilik</label><textarea name="catatan_pemilik"
        class="form-control"></textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="hewan.php?action=list">Batal</a>
  </form>
  <?php
elseif ($action === 'edit' && $id):
  $res = $mysqli->query("SELECT * FROM hewan WHERE hewan_id = $id LIMIT 1");
  if (!$res || $res->num_rows == 0) {
    flash('Hewan tidak ditemukan.');
    header('Location: hewan.php?action=list');
    exit;
  }
  $data = $res->fetch_assoc();
  ?>
  <h3>Edit Hewan</h3>
  <form method="post">
    <input type="hidden" name="__action" value="update">
    <input type="hidden" name="hewan_id" value="<?= (int) $data['hewan_id'] ?>">
    <div class="mb-3">
      <label class="form-label">Pemilik</label>
      <select name="customer_id" class="form-select" required>
        <?php $pelangganRes->data_seek(0);
        while ($p = $pelangganRes->fetch_assoc()): ?>
          <option value="<?= $p['user_id'] ?>" <?= $p['user_id'] == $data['customer_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['nama_lengkap']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3"><label class="form-label">Nama hewan</label><input name="nama_hewan" class="form-control" required
        value="<?= htmlspecialchars($data['nama_hewan']) ?>"></div>
    <div class="mb-3"><label class="form-label">Jenis hewan</label><input name="jenis_hewan" class="form-control"
        value="<?= htmlspecialchars($data['jenis_hewan']) ?>"></div>
    <div class="mb-3"><label class="form-label">Ras</label><input name="ras" class="form-control"
        value="<?= htmlspecialchars($data['ras']) ?>"></div>
    <div class="mb-3"><label class="form-label">Tanggal lahir</label><input name="tanggal_lahir" type="date"
        class="form-control" value="<?= $data['tanggal_lahir'] ?>"></div>
    <div class="mb-3"><label class="form-label">Catatan pemilik</label><textarea name="catatan_pemilik"
        class="form-control"><?= htmlspecialchars($data['catatan_pemilik']) ?></textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="hewan.php?action=list">Batal</a>
  </form>
  <?php
else:
  echo '<div class="alert alert-warning">Aksi tidak dikenal.</div>';
endif;

include __DIR__ . '/../includes/footer.php';
