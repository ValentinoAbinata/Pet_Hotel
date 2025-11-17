<?php
// modules/rekam_medis.php - single-file Rekam Medis (dokter only)
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
require_role(['dokter']);

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sub = $_POST['__action'] ?? '';
    if ($sub === 'create') {
        $hewan_id = (int)$_POST['hewan_id'];
        $dokter_id = (int)current_user()['user_id'];
        $tanggal = $mysqli->real_escape_string($_POST['tanggal_pemeriksaan']);
        $diagnosa = $mysqli->real_escape_string($_POST['diagnosa']);
        $tindakan = $mysqli->real_escape_string($_POST['tindakan_medis']);
        $catatan = $mysqli->real_escape_string($_POST['catatan_dokter']);
        $sql = "INSERT INTO rekam_medis (hewan_id,dokter_id,tanggal_pemeriksaan,diagnosa,tindakan_medis,catatan_dokter) VALUES ($hewan_id,$dokter_id,'$tanggal','".$diagnosa."','".$tindakan."','".$catatan."')";
        if ($mysqli->query($sql)) { flash('Rekam medis tersimpan.'); header('Location: rekam_medis.php?action=list'); exit; }
        else $errors[] = 'Gagal: '.$mysqli->error;
    } elseif ($sub === 'update') {
        $rid = (int)$_POST['rekam_medis_id'];
        $hewan_id = (int)$_POST['hewan_id'];
        $tanggal = $mysqli->real_escape_string($_POST['tanggal_pemeriksaan']);
        $diagnosa = $mysqli->real_escape_string($_POST['diagnosa']);
        $tindakan = $mysqli->real_escape_string($_POST['tindakan_medis']);
        $catatan = $mysqli->real_escape_string($_POST['catatan_dokter']);
        $sql = "UPDATE rekam_medis SET hewan_id=$hewan_id,tanggal_pemeriksaan='$tanggal',diagnosa='".$diagnosa."',tindakan_medis='".$tindakan."',catatan_dokter='".$catatan."' WHERE rekam_medis_id = $rid";
        if ($mysqli->query($sql)) { flash('Perubahan disimpan.'); header('Location: rekam_medis.php?action=list'); exit; }
        else $errors[] = 'Gagal: '.$mysqli->error;
    }
}

if ($action === 'delete' && $id) {
    $mysqli->query("DELETE FROM rekam_medis WHERE rekam_medis_id = $id");
    flash('Rekam medis dihapus.');
    header('Location: rekam_medis.php?action=list');
    exit;
}

$hewanRes = $mysqli->query("SELECT hewan_id,nama_hewan FROM hewan ORDER BY nama_hewan");

include __DIR__ . '/../includes/header.php';
$msg = get_flash(); if ($msg) echo '<div class="alert alert-info">'.htmlspecialchars($msg).'</div>';
foreach ($errors as $e) echo '<div class="alert alert-danger">'.htmlspecialchars($e).'</div>';

if ($action === 'list'):
    $q = $mysqli->query("SELECT rm.*, h.nama_hewan, u.nama_lengkap as dokter_nama FROM rekam_medis rm LEFT JOIN hewan h ON rm.hewan_id = h.hewan_id LEFT JOIN users u ON rm.dokter_id = u.user_id ORDER BY rm.tanggal_pemeriksaan DESC");
    ?>
    <div class="d-flex justify-content-between mb-3">
      <h3>Rekam Medis</h3>
      <a class="btn btn-success" href="rekam_medis.php?action=create">Tambah Rekam Medis</a>
    </div>
    <table class="table">
      <thead><tr><th>ID</th><th>Hewan</th><th>Tanggal</th><th>Diagnosa</th><th>Tindakan</th><th>Dokter</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php while ($r = $q->fetch_assoc()): ?>
        <tr>
          <td><?= $r['rekam_medis_id'] ?></td>
          <td><?= htmlspecialchars($r['nama_hewan']) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($r['tanggal_pemeriksaan'])) ?></td>
          <td><?= htmlspecialchars($r['diagnosa']) ?></td>
          <td><?= htmlspecialchars($r['tindakan_medis']) ?></td>
          <td><?= htmlspecialchars($r['dokter_nama']) ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="rekam_medis.php?action=edit&id=<?= $r['rekam_medis_id'] ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="rekam_medis.php?action=delete&id=<?= $r['rekam_medis_id'] ?>" onclick="return confirm('Hapus rekam medis?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
<?php
elseif ($action === 'create'):
?>
  <h3>Tambah Rekam Medis</h3>
  <form method="post">
    <input type="hidden" name="__action" value="create">
    <div class="mb-3"><label class="form-label">Hewan</label><select name="hewan_id" class="form-select" required><?php $hewanRes->data_seek(0); while($h=$hewanRes->fetch_assoc()): ?><option value="<?= $h['hewan_id'] ?>"><?= htmlspecialchars($h['nama_hewan']) ?></option><?php endwhile; ?></select></div>
    <div class="mb-3"><label class="form-label">Tanggal pemeriksaan</label><input type="datetime-local" name="tanggal_pemeriksaan" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" required></div>
    <div class="mb-3"><label class="form-label">Diagnosa</label><textarea name="diagnosa" class="form-control"></textarea></div>
    <div class="mb-3"><label class="form-label">Tindakan medis</label><textarea name="tindakan_medis" class="form-control"></textarea></div>
    <div class="mb-3"><label class="form-label">Catatan dokter</label><textarea name="catatan_dokter" class="form-control"></textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="rekam_medis.php?action=list">Batal</a>
  </form>
<?php
elseif ($action === 'edit' && $id):
    $res = $mysqli->query("SELECT * FROM rekam_medis WHERE rekam_medis_id = $id LIMIT 1");
    if (!$res || $res->num_rows==0) { flash('Rekam medis tidak ditemukan.'); header('Location: rekam_medis.php?action=list'); exit; }
    $data = $res->fetch_assoc();
?>
  <h3>Edit Rekam Medis</h3>
  <form method="post">
    <input type="hidden" name="__action" value="update">
    <input type="hidden" name="rekam_medis_id" value="<?= (int)$data['rekam_medis_id'] ?>">
    <div class="mb-3"><label class="form-label">Hewan</label><select name="hewan_id" class="form-select" required><?php $hewanRes->data_seek(0); while($h=$hewanRes->fetch_assoc()): ?><option value="<?= $h['hewan_id'] ?>" <?= $h['hewan_id']==$data['hewan_id']?'selected':'' ?>><?= htmlspecialchars($h['nama_hewan']) ?></option><?php endwhile; ?></select></div>
    <div class="mb-3"><label class="form-label">Tanggal pemeriksaan</label><input type="datetime-local" name="tanggal_pemeriksaan" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_pemeriksaan'])) ?>" required></div>
    <div class="mb-3"><label class="form-label">Diagnosa</label><textarea name="diagnosa" class="form-control"><?= htmlspecialchars($data['diagnosa']) ?></textarea></div>
    <div class="mb-3"><label class="form-label">Tindakan medis</label><textarea name="tindakan_medis" class="form-control"><?= htmlspecialchars($data['tindakan_medis']) ?></textarea></div>
    <div class="mb-3"><label class="form-label">Catatan dokter</label><textarea name="catatan_dokter" class="form-control"><?= htmlspecialchars($data['catatan_dokter']) ?></textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="rekam_medis.php?action=list">Batal</a>
  </form>
<?php
else:
  echo '<div class="alert alert-warning">Aksi tidak dikenal.</div>';
endif;

include __DIR__ . '/../includes/footer.php';
