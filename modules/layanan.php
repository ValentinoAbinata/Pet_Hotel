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
    $nama_ruang = $mysqli->real_escape_string($_POST['nama_ruang']);
    $nama_layanan = $mysqli->real_escape_string($_POST['nama_layanan']);
    $deskripsi = $mysqli->real_escape_string($_POST['deskripsi']);
    $harga = (int) $_POST['harga'];

    if (empty($nama_layanan) || $harga < 0) {
        $errors[] = 'Nama layanan dan harga (minimal 0) wajib diisi.';
    }

    if (empty($errors)) {
        if ($sub === 'create') {
            $sql = "INSERT INTO layanan (nama_ruang, nama_layanan, deskripsi, harga) VALUES ('$nama_ruang', '$nama_layanan', '$deskripsi', $harga)";
            if ($mysqli->query($sql)) {
                flash('Layanan baru ditambahkan.');
                header('Location: layanan.php?action=list');
                exit;
            } else
                $errors[] = 'Gagal menyimpan: ' . $mysqli->error;
        } elseif ($sub === 'update') {
            $lid = (int) $_POST['layanan_id'];
            $sql = "UPDATE layanan SET nama_ruang='$nama_ruang', nama_layanan='$nama_layanan', deskripsi='$deskripsi', harga=$harga WHERE layanan_id = $lid";
            if ($mysqli->query($sql)) {
                flash('Perubahan layanan disimpan.');
                header('Location: layanan.php?action=list');
                exit;
            } else
                $errors[] = 'Gagal menyimpan: ' . $mysqli->error;
        }
    }
}

if ($action === 'delete' && $id) {
    // Periksa apakah layanan digunakan di reservasi_layanan
    $cek = $mysqli->query("SELECT COUNT(*) as cnt FROM reservasi_layanan WHERE layanan_id = $id");
    $cnt = $cek->fetch_assoc()['cnt'];
    if ($cnt > 0) {
        flash("Layanan tidak dapat dihapus karena sedang digunakan dalam $cnt reservasi.");
    } else {
        $mysqli->query("DELETE FROM layanan WHERE layanan_id = $id");
        flash('Layanan telah dihapus.');
    }
    header('Location: layanan.php?action=list');
    exit;
}

include __DIR__ . '/../includes/header.php';
$msg = get_flash();
if ($msg)
    echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e)
    echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';

if ($action === 'list'):
    $q = $mysqli->query("SELECT * FROM layanan ORDER BY layanan_id ASC");
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Layanan</h3>
        <a class="btn btn-success" href="layanan.php?action=create">Tambah Layanan</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ruang</th>
                <th>Nama Layanan</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($r = $q->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['layanan_id'] ?></td>
                    <td><?= htmlspecialchars($r['nama_ruang']) ?></td>
                    <td><?= htmlspecialchars($r['nama_layanan']) ?></td>
                    <td>Rp<?= number_format($r['harga']) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="layanan.php?action=edit&id=<?= $r['layanan_id'] ?>">Edit</a>
                        <a class="btn btn-sm btn-danger" href="layanan.php?action=delete&id=<?= $r['layanan_id'] ?>"
                            onclick="return confirm('Hapus layanan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php
elseif ($action === 'create'):
    ?>
    <h3>Tambah Layanan</h3>
    <form method="post">
        <input type="hidden" name="__action" value="create">
        <div class="mb-3"><label class="form-label">Nama Ruang</label><input name="nama_ruang" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Nama Layanan</label><input name="nama_layanan" class="form-control"
                required></div>
        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi"
                class="form-control"></textarea></div>
        <div class="mb-3"><label class="form-label">Harga</label><input name="harga" type="number" min="0"
                class="form-control" required></div>
        <button class="btn btn-primary">Simpan</button>
        <a class="btn btn-link" href="layanan.php?action=list">Batal</a>
    </form>
    <?php
elseif ($action === 'edit' && $id):
    $res = $mysqli->query("SELECT * FROM layanan WHERE layanan_id = $id LIMIT 1");
    if (!$res || $res->num_rows == 0) {
        flash('Layanan tidak ditemukan.');
        header('Location: layanan.php?action=list');
        exit;
    }
    $data = $res->fetch_assoc();
    ?>
    <h3>Edit Layanan</h3>
    <form method="post">
        <input type="hidden" name="__action" value="update">
        <input type="hidden" name="layanan_id" value="<?= (int) $data['layanan_id'] ?>">
        <div class="mb-3"><label class="form-label">Nama Ruang</label><input name="nama_ruang" class="form-control"
                value="<?= htmlspecialchars($data['nama_ruang']) ?>"></div>
        <div class="mb-3"><label class="form-label">Nama Layanan</label><input name="nama_layanan" class="form-control"
                value="<?= htmlspecialchars($data['nama_layanan']) ?>" required></div>
        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi"
                class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea></div>
        <div class="mb-3"><label class="form-label">Harga</label><input name="harga" type="number" min="0"
                class="form-control" value="<?= (int) $data['harga'] ?>" required></div>
        <button class="btn btn-primary">Simpan</button>
        <a class="btn btn-link" href="layanan.php?action=list">Batal</a>
    </form>
    <?php
else:
    echo '<div class="alert alert-warning">Aksi tidak dikenal.</div>';
endif;

include __DIR__ . '/../includes/footer.php';
?>