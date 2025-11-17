<?php
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
  if ($sub === 'update') {
    $uid = (int) $_POST['user_id'];
    $nama = $mysqli->real_escape_string($_POST['nama_lengkap']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $telp = $mysqli->real_escape_string($_POST['no_telepon']);
    $alamat = $mysqli->real_escape_string($_POST['alamat']);
    $sql = "UPDATE users SET nama_lengkap='$nama', email='$email', no_telepon='$telp', alamat='$alamat' WHERE user_id = $uid AND peran='customer'";
    if ($mysqli->query($sql)) {
      flash('Perubahan disimpan.');
      header('Location: pelanggan.php?action=list');
      exit;
    } else
      $errors[] = 'Gagal: ' . $mysqli->error;
  }
}

if ($action === 'delete' && $id) {
  $mysqli->query("DELETE FROM users WHERE user_id = $id AND peran='customer'");
  flash('Pelanggan dihapus.');
  header('Location: pelanggan.php?action=list');
  exit;
}

include __DIR__ . '/../includes/header.php';
$msg = get_flash();
if ($msg)
  echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e)
  echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';

if ($action === 'list'):
  $q = $mysqli->query("SELECT user_id,nama_lengkap,email,no_telepon,alamat,created_at FROM users WHERE peran='customer' ORDER BY user_id DESC");
  ?>
  <div class="d-flex justify-content-between mb-3">
    <h3>Pelanggan</h3>
    <a class="btn btn-sm btn-success" href="/pet-hotel-admin/pages/register.php">Tambah Pelanggan</a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Telp</th>
        <th>Alamat</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($r = $q->fetch_assoc()): ?>
        <tr>
          <td><?= $r['user_id'] ?></td>
          <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
          <td><?= htmlspecialchars($r['email']) ?></td>
          <td><?= htmlspecialchars($r['no_telepon']) ?></td>
          <td><?= htmlspecialchars($r['alamat']) ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="pelanggan.php?action=edit&id=<?= $r['user_id'] ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="pelanggan.php?action=delete&id=<?= $r['user_id'] ?>"
              onclick="return confirm('Hapus pelanggan?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php
elseif ($action === 'edit' && $id):
  $res = $mysqli->query("SELECT * FROM users WHERE user_id = $id AND peran='customer' LIMIT 1");
  if (!$res || $res->num_rows == 0) {
    flash('Pelanggan tidak ditemukan.');
    header('Location: pelanggan.php?action=list');
    exit;
  }
  $data = $res->fetch_assoc();
  ?>
  <h3>Edit Pelanggan</h3>
  <form method="post">
    <input type="hidden" name="__action" value="update">
    <input type="hidden" name="user_id" value="<?= (int) $data['user_id'] ?>">
    <div class="mb-3"><label class="form-label">Nama lengkap</label><input name="nama_lengkap" class="form-control"
        value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required></div>
    <div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" class="form-control"
        value="<?= htmlspecialchars($data['email']) ?>" required></div>
    <div class="mb-3"><label class="form-label">No telepon</label><input name="no_telepon" class="form-control"
        value="<?= htmlspecialchars($data['no_telepon']) ?>"></div>
    <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat"
        class="form-control"><?= htmlspecialchars($data['alamat']) ?></textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="pelanggan.php?action=list">Batal</a>
  </form>
  <?php
else:
  echo '<div class="alert alert-warning">Aksi tidak dikenal.</div>';
endif;

include __DIR__ . '/../includes/footer.php';
