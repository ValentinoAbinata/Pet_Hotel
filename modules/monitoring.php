<?php

// Source - https://stackoverflow.com/a
// Posted by Fancy John, modified by community. See post 'Timeline' for change history
// Retrieved 2025-12-06, License - CC BY-SA 4.0

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
require_role(['admin', 'dokter']);  

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];

// PERBAIKAN: Izinkan Admin DAN Dokter akses create
if ($action === 'create' && !in_array(current_user()['peran'], ['admin', 'dokter'])) {
    flash('Anda tidak memiliki akses untuk menambah monitoring.');
    header('Location: monitoring.php?action=list');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sub = $_POST['__action'] ?? '';
  
  // PERBAIKAN: Izinkan Admin DAN Dokter akses create
  if ($sub === 'create' && !in_array(current_user()['peran'], ['admin', 'dokter'])) {
      flash('Anda tidak memiliki akses untuk menambah monitoring.');
      header('Location: monitoring.php?action=list');
      exit;
  }
  
  if ($sub === 'create') {
    $reservasi_id = (int) $_POST['reservasi_id'];
    $staf_id = (int) current_user()['user_id'];
    $tanggal = $mysqli->real_escape_string($_POST['tanggal_monitoring']);
    $target_makanan = max(0, min(3, (int) $_POST['target_makanan']));
    $aktual_makanan = max(0, min(3, (int) $_POST['aktual_makanan']));
    $aktual_mandi = isset($_POST['aktual_mandi']) ? (int) $_POST['aktual_mandi'] : 0;
    $catatan_kesehatan = $mysqli->real_escape_string($_POST['catatan_kesehatan']);
    $catatan_aktivitas = $mysqli->real_escape_string($_POST['catatan_aktivitas']);

    // upload
    $file_path = null;
    if (!empty($_FILES['media']['name'])) {
      $f = $_FILES['media'];
      $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'video/mp4'];
      if ($f['error'] === 0 && in_array($f['type'], $allowed)) {
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        
        // PERBAIKAN: Buat folder otomatis jika belum ada (Mengatasi error No such file)
        $target_dir = __DIR__ . '/../uploads/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $fn = 'uploads/monitoring_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        
        // Simpan file
        if (!move_uploaded_file($f['tmp_name'], __DIR__ . '/../' . $fn)) {
          $errors[] = 'Gagal menyimpan file.';
        } else
          $file_path = $mysqli->real_escape_string($fn);
      } else
        $errors[] = 'Tipe file tidak diperbolehkan atau error upload.';
    }
    
    if (empty($errors)) {
      // 1. Siapkan Query SQL
      if ($file_path) {
        $sql = "INSERT INTO monitoring_harian (reservasi_id,staf_id,tanggal_monitoring,foto_video_url,target_makanan,aktual_makanan,aktual_mandi,catatan_kesehatan,catatan_aktivitas)
                    VALUES ($reservasi_id,$staf_id,'$tanggal','$file_path',$target_makanan,$aktual_makanan,$aktual_mandi,'$catatan_kesehatan','$catatan_aktivitas')";
      } else {
        $sql = "INSERT INTO monitoring_harian (reservasi_id,staf_id,tanggal_monitoring,target_makanan,aktual_makanan,aktual_mandi,catatan_kesehatan,catatan_aktivitas)
                    VALUES ($reservasi_id,$staf_id,'$tanggal',$target_makanan,$aktual_makanan,$aktual_mandi,'$catatan_kesehatan','$catatan_aktivitas')";
      }

      // 2. Eksekusi Query Dulu
      if ($mysqli->query($sql)) {
          
        // --- LOGIKA NOTIFIKASI ---
        // PERBAIKAN: Menggunakan r.customer_id agar tidak ambiguous dan error
        $qRes = $mysqli->query("SELECT r.customer_id, h.nama_hewan 
                                FROM reservasi r 
                                JOIN hewan h ON r.hewan_id = h.hewan_id 
                                WHERE r.reservasi_id = $reservasi_id");
        
        if ($qRes && $qRes->num_rows > 0) {
            $dt = $qRes->fetch_assoc();
            $judul = "Laporan Harian: " . $dt['nama_hewan'];
            $pesan = "Laporan aktivitas terbaru " . $dt['nama_hewan'] . " sudah tersedia. Cek sekarang!";
            $url   = "/Pet_Hotel/portal/monitoring.php?reservasi_id=" . $reservasi_id;
            
            // Panggil fungsi global
            if (function_exists('send_notification')) {
                send_notification($dt['customer_id'], $judul, $pesan, $url);
            }
        }
        // --- SELESAI NOTIFIKASI ---

        flash('Monitoring disimpan.');
        header('Location: monitoring.php?action=list');
        exit;

      } else {
        $errors[] = 'Gagal menyimpan: ' . $mysqli->error;
      }
    }
  } elseif ($sub === 'update') {
    $mid = (int) $_POST['monitoring_id'];
    $reservasi_id = (int) $_POST['reservasi_id'];
    $tanggal = $mysqli->real_escape_string($_POST['tanggal_monitoring']);
    $target_makanan = max(0, min(3, (int) $_POST['target_makanan']));
    $aktual_makanan = max(0, min(3, (int) $_POST['aktual_makanan']));
    $aktual_mandi = isset($_POST['aktual_mandi']) ? (int) $_POST['aktual_mandi'] : 0;
    $catatan_kesehatan = $mysqli->real_escape_string($_POST['catatan_kesehatan']);
    $catatan_aktivitas = $mysqli->real_escape_string($_POST['catatan_aktivitas']);

    $cur = $mysqli->query("SELECT foto_video_url FROM monitoring_harian WHERE monitoring_id = $mid LIMIT 1");
    $curf = $cur && $cur->num_rows ? $cur->fetch_assoc()['foto_video_url'] : null;
    $file_path = $curf;

    if (!empty($_FILES['media']['name'])) {
      $f = $_FILES['media'];
      $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'video/mp4'];
      if ($f['error'] === 0 && in_array($f['type'], $allowed)) {
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        
        // PERBAIKAN: Buat folder otomatis di update juga
        $target_dir = __DIR__ . '/../uploads/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $fn = 'uploads/monitoring_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        
        if (move_uploaded_file($f['tmp_name'], __DIR__ . '/../' . $fn)) {
          if ($curf && file_exists(__DIR__ . '/../' . $curf))
            @unlink(__DIR__ . '/../' . $curf);
          $file_path = $mysqli->real_escape_string($fn);
        } else
          $errors[] = 'Gagal menyimpan file.';
      } else
        $errors[] = 'Tipe file tidak diperbolehkan atau error upload.';
    }

    if (empty($errors)) {
      $fp_sql = $file_path ? "'$file_path'" : "NULL";
      $sql = "UPDATE monitoring_harian SET reservasi_id=$reservasi_id,tanggal_monitoring='$tanggal',foto_video_url=$fp_sql,target_makanan=$target_makanan,aktual_makanan=$aktual_makanan,aktual_mandi=$aktual_mandi,catatan_kesehatan='$catatan_kesehatan',catatan_aktivitas='$catatan_aktivitas' WHERE monitoring_id = $mid";
      if ($mysqli->query($sql)) {
        flash('Perubahan disimpan.');
        header('Location: monitoring.php?action=list');
        exit;
      } else
        $errors[] = 'Gagal menyimpan: ' . $mysqli->error;
    }
  }
}

if ($action === 'delete' && $id) {
  $r = $mysqli->query("SELECT foto_video_url FROM monitoring_harian WHERE monitoring_id = $id LIMIT 1");
  if ($r && $r->num_rows) {
    $d = $r->fetch_assoc();
    if ($d['foto_video_url'] && file_exists(__DIR__ . '/../' . $d['foto_video_url']))
      @unlink(__DIR__ . '/../' . $d['foto_video_url']);
  }
  $mysqli->query("DELETE FROM monitoring_harian WHERE monitoring_id = $id");
  flash('Record monitoring dihapus.');
  header('Location: monitoring.php?action=list');
  exit;
}

// Data untuk dropdown (Diambil yang Confirmed/Completed)
$resvRes = $mysqli->query("SELECT r.reservasi_id, h.nama_hewan, u.nama_lengkap as customer_name 
                          FROM reservasi r 
                          LEFT JOIN hewan h ON r.hewan_id = h.hewan_id 
                          LEFT JOIN users u ON r.customer_id = u.user_id 
                          WHERE r.status_reservasi IN ('Confirmed', 'Completed')
                          ORDER BY r.reservasi_id DESC");

include __DIR__ . '/../includes/header.php';
$msg = get_flash();
if ($msg)
  echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e)
  echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';

if ($action === 'list'):
  $q = $mysqli->query("SELECT m.*, u.nama_lengkap as staf_name, r.hewan_id, r.reservasi_id as rid_display 
                      FROM monitoring_harian m 
                      LEFT JOIN users u ON m.staf_id = u.user_id 
                      LEFT JOIN reservasi r ON m.reservasi_id = r.reservasi_id 
                      ORDER BY m.tanggal_monitoring DESC");
  ?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Monitoring Harian</h3>
    <?php if (in_array(current_user()['peran'], ['admin', 'dokter'])): ?>
      <a class="btn btn-success" href="monitoring.php?action=create">Tambah Monitoring</a>
    <?php endif; ?>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Reservasi</th>
        <th>Tanggal</th>
        <th>Media</th>
        <th>Makanan</th>
        <th>Mandi</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($r = $q->fetch_assoc()): ?>
        <tr>
          <td><?= $r['monitoring_id'] ?></td>
          <td>#<?= $r['rid_display'] ?></td>
          <td><?= date('d/m/Y', strtotime($r['tanggal_monitoring'])) ?></td>
          <td>
            <?= $r['foto_video_url'] ? '<a href="/Pet_Hotel/' . htmlspecialchars($r['foto_video_url']) . '" target="_blank">Lihat</a>' : '' ?>
          </td>
          <td><?= (int) $r['target_makanan'] . ' / ' . (int) $r['aktual_makanan'] ?></td>
          <td><?= (int) $r['aktual_mandi'] ? 'Ya' : 'Tidak' ?></td>
          <td>
            <a class="btn btn-sm btn-primary" href="monitoring.php?action=edit&id=<?= $r['monitoring_id'] ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="monitoring.php?action=delete&id=<?= $r['monitoring_id'] ?>"
              onclick="return confirm('Hapus record?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php
elseif ($action === 'create'):
  ?>
  <h3>Tambah Monitoring Harian</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="__action" value="create">
    <div class="mb-3">
      <label class="form-label">Reservasi (Hewan Check-in)</label>
      <select name="reservasi_id" class="form-select" required>
        <?php 
        if ($resvRes->num_rows > 0) {
            $resvRes->data_seek(0);
            while ($r = $resvRes->fetch_assoc()): 
        ?>
          <option value="<?= $r['reservasi_id'] ?>">
            <?= htmlspecialchars('#'.$r['reservasi_id'] . ' — ' . $r['nama_hewan'] . ' (' . $r['customer_name'] . ')') ?>
          </option>
        <?php 
            endwhile; 
        } else {
            echo "<option value=''>Tidak ada hewan yang check-in (Confirmed)</option>";
        }
        ?>
      </select>
    </div>
    <div class="mb-3"><label class="form-label">Tanggal Monitoring</label><input type="date" name="tanggal_monitoring"
        class="form-control" value="<?= date('Y-m-d') ?>" required></div>
    <div class="mb-3"><label class="form-label">Foto/Video (jpg/png/mp4)</label><input type="file" name="media"
        class="form-control" accept="image/*,video/*"></div>
    <div class="mb-3 row">
      <div class="col"><label class="form-label">Target makanan (0..3)</label><input type="number" name="target_makanan"
          min="0" max="3" class="form-control" value="3" required></div>
      <div class="col"><label class="form-label">Aktual makanan (0..3)</label><input type="number" name="aktual_makanan"
          min="0" max="3" class="form-control" value="3" required></div>
      <div class="col"><label class="form-label">Mandi?</label><select name="aktual_mandi" class="form-select">
          <option value="1">1 (Ya)</option>
          <option value="0" selected>0 (Tidak)</option>
        </select></div>
    </div>
    <div class="mb-3"><label class="form-label">Catatan Kesehatan</label><textarea name="catatan_kesehatan"
        class="form-control"></textarea></div>
    <div class="mb-3"><label class="form-label">Catatan Aktivitas</label><textarea name="catatan_aktivitas"
        class="form-control"></textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="monitoring.php?action=list">Batal</a>
  </form>
  <?php
elseif ($action === 'edit' && $id):
  $res = $mysqli->query("SELECT * FROM monitoring_harian WHERE monitoring_id = $id LIMIT 1");
  if (!$res || $res->num_rows == 0) {
    flash('Record tidak ditemukan.');
    header('Location: monitoring.php?action=list');
    exit;
  }
  $data = $res->fetch_assoc();
  ?>
  <h3>Edit Monitoring</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="__action" value="update">
    <input type="hidden" name="monitoring_id" value="<?= (int) $data['monitoring_id'] ?>">
    <div class="mb-3">
      <label class="form-label">Reservasi</label>
      <select name="reservasi_id" class="form-select" required>
        <?php $resvRes->data_seek(0);
        while ($r = $resvRes->fetch_assoc()): ?>
          <option value="<?= $r['reservasi_id'] ?>" <?= $r['reservasi_id'] == $data['reservasi_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars('#'.$r['reservasi_id'] . ' — ' . $r['nama_hewan'] . ' (' . $r['customer_name'] . ')') ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3"><label class="form-label">Tanggal Monitoring</label><input type="date" name="tanggal_monitoring"
        class="form-control" value="<?= date('Y-m-d', strtotime($data['tanggal_monitoring'])) ?>" required></div>
    <div class="mb-3">
      <label class="form-label">Foto/Video (jpg/png/mp4)</label>
      <?php if ($data['foto_video_url']): ?>
        <p>File saat ini: <a href="/Pet_Hotel/<?= htmlspecialchars($data['foto_video_url']) ?>"
            target="_blank">Lihat</a></p><?php endif; ?>
      <input type="file" name="media" class="form-control" accept="image/*,video/*">
    </div>
    <div class="mb-3 row">
      <div class="col"><label class="form-label">Target makanan (0..3)</label><input type="number" name="target_makanan"
          min="0" max="3" class="form-control" value="<?= (int) $data['target_makanan'] ?>" required></div>
      <div class="col"><label class="form-label">Aktual makanan (0..3)</label><input type="number" name="aktual_makanan"
          min="0" max="3" class="form-control" value="<?= (int) $data['aktual_makanan'] ?>" required></div>
      <div class="col"><label class="form-label">Mandi?</label><select name="aktual_mandi" class="form-select">
          <option value="1" <?= $data['aktual_mandi'] ? 'selected' : '' ?>>1 (Ya)</option>
          <option value="0" <?= !$data['aktual_mandi'] ? 'selected' : '' ?>>0 (Tidak)</option>
        </select></div>
    </div>
    <div class="mb-3"><label class="form-label">Catatan Kesehatan</label><textarea name="catatan_kesehatan"
        class="form-control"><?= htmlspecialchars($data['catatan_kesehatan']) ?></textarea></div>
    <div class="mb-3"><label class="form-label">Catatan Aktivitas</label><textarea name="catatan_aktivitas"
        class="form-control"><?= htmlspecialchars($data['catatan_aktivitas']) ?></textarea></div>
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-link" href="monitoring.php?action=list">Batal</a>
  </form>
  <?php
else:
  echo '<div class="alert alert-warning">Aksi tidak dikenal.</div>';
endif;

include __DIR__ . '/../includes/footer.php';
?>