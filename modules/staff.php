<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// HANYA ADMIN YANG BOLEH AKSES
require_login();
require_role(['admin']);

$action = $_GET['action'] ?? 'list';
$errors = [];

// PROSES TAMBAH STAF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $nama = $mysqli->real_escape_string($_POST['nama_lengkap']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $pass = $_POST['password'];
    $peran = $mysqli->real_escape_string($_POST['peran']); // Admin bisa milih peran

    // Validasi sederhana
    if (empty($nama) || empty($email) || empty($pass)) {
        $errors[] = "Semua field wajib diisi.";
    } else {
        // Cek email kembar
        $cek = $mysqli->query("SELECT user_id FROM users WHERE email = '$email'");
        if ($cek->num_rows > 0) {
            $errors[] = "Email sudah digunakan.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (nama_lengkap, email, password_hash, peran) VALUES ('$nama', '$email', '$hash', '$peran')";
            if ($mysqli->query($sql)) {
                flash("Staf baru ($peran) berhasil ditambahkan.");
                header('Location: staf.php');
                exit;
            } else {
                $errors[] = "Gagal: " . $mysqli->error;
            }
        }
    }
}

// Hapus Staf
if ($action === 'delete' && isset($_GET['id'])) {
    $uid = (int)$_GET['id'];
    // Cegah admin menghapus dirinya sendiri
    if ($uid == current_user()['user_id']) {
        flash("Anda tidak bisa menghapus akun sendiri.");
    } else {
        $mysqli->query("DELETE FROM users WHERE user_id = $uid AND peran != 'customer'");
        flash("Data staf dihapus.");
    }
    header('Location: staf.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Kelola Staf & Dokter</h3>
        <?php if($action === 'list'): ?>
            <a href="staf.php?action=create" class="btn btn-primary">➕ Tambah Staf Baru</a>
        <?php endif; ?>
    </div>

    <?php 
    // Tampilkan Error jika ada
    foreach($errors as $e) echo "<div class='alert alert-danger'>$e</div>"; 
    
    if ($action === 'create'): 
    ?>
        <div class="card col-md-6">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email Login</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password Awal</label>
                        <input type="text" name="password" class="form-control" required placeholder="Misal: staf123">
                    </div>
                    <div class="mb-3">
                        <label>Peran</label>
                        <select name="peran" class="form-select">
                            <option value="dokter">Dokter</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Staf</button>
                    <a href="staf.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>

    <?php else: // LIST VIEW ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ambil user yang BUKAN customer
                $q = $mysqli->query("SELECT * FROM users WHERE peran != 'customer' ORDER BY peran, nama_lengkap");
                while($r = $q->fetch_assoc()): 
                ?>
                <tr>
                    <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><span class="badge bg-info"><?= strtoupper($r['peran']) ?></span></td>
                    <td>
                        <?php if($r['user_id'] != current_user()['user_id']): ?>
                        <a href="staf.php?action=delete&id=<?= $r['user_id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin hapus staf ini?')">Hapus</a>
                        <?php else: ?>
                            <span class="text-muted"><small>Akun Anda</small></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>