<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
$u = current_user();
$customer_id = (int) $u['user_id'];

if ($u['peran'] !== 'customer') {
    flash('Akses ditolak.');
    header('Location: /Pet_Hotel/pages/dashboard.php');
    exit;
}

$errors = [];

// Handle update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $mysqli->real_escape_string($_POST['nama_lengkap']);
    $telp = $mysqli->real_escape_string($_POST['no_telepon']);
    $alamat = $mysqli->real_escape_string($_POST['alamat']);

    if (empty($nama)) {
        $errors[] = 'Nama lengkap tidak boleh kosong.';
    }

    if (empty($errors)) {
        $sql = "UPDATE users SET nama_lengkap='$nama', no_telepon='$telp', alamat='$alamat' 
                WHERE user_id = $customer_id AND peran='customer'";

        if ($mysqli->query($sql)) {
            flash('Profil Anda berhasil diperbarui.');
            // Kita refresh data session agar nama di header juga update
            $res = $mysqli->query("SELECT * FROM users WHERE user_id = $customer_id");
            $u_updated = $res->fetch_assoc();
            $_SESSION['user'] = [
                'user_id' => $u_updated['user_id'],
                'nama_lengkap' => $u_updated['nama_lengkap'],
                'email' => $u_updated['email'],
                'peran' => $u_updated['peran']
            ];
            header('Location: profil.php');
            exit;
        } else {
            $errors[] = 'Gagal memperbarui profil: ' . $mysqli->error;
        }
    }
}

// Ambil data terbaru untuk ditampilkan di form
$dataRes = $mysqli->query("SELECT nama_lengkap, email, no_telepon, alamat 
                          FROM users WHERE user_id = $customer_id");
$data = $dataRes->fetch_assoc();


include __DIR__ . '/../includes/header.php';
$msg = get_flash();
if ($msg)
    echo '<div class="alert alert-success">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e)
    echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';
?>
<link rel="stylesheet" href="/PET_HOTEL/style/profil.css">

<div class="profil-container">
    <!-- Ganti struktur HTML yang ada -->
    <div class="profil-header">
        <h3>Profil Saya</h3>
        <p>Kelola informasi kontak dan data diri Anda.</p>
    </div>
    
    <div class="profil-card">
        <div class="profil-card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email (Tidak bisa diubah)</label>
                    <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>"
                        disabled readonly>
                    <small>Email digunakan untuk login dan tidak dapat diubah.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input name="no_telepon" class="form-control" value="<?= htmlspecialchars($data['no_telepon']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control"
                        rows="3"><?= htmlspecialchars($data['alamat']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
?>