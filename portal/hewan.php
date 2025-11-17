<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_login();
$u = current_user();

if ($u['peran'] !== 'customer') {
    flash('Akses ditolak.');
    header('Location: /Pet_Hotel/pages/dashboard.php');
    exit;
}
$customer_id = (int) $u['user_id'];

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sub = $_POST['__action'] ?? '';
    $nama = $mysqli->real_escape_string($_POST['nama_hewan']);
    $jenis = $mysqli->real_escape_string($_POST['jenis_hewan']);
    $ras = $mysqli->real_escape_string($_POST['ras']);
    $tgll = !empty($_POST['tanggal_lahir']) ? $mysqli->real_escape_string(date('Y-m-d', strtotime($_POST['tanggal_lahir']))) : null;
    $catatan = $mysqli->real_escape_string($_POST['catatan_pemilik']);

    if ($sub === 'create') {
        // customer_id diambil dari session, BUKAN dari form
        $sql = "INSERT INTO hewan (customer_id,nama_hewan,jenis_hewan,ras,tanggal_lahir,catatan_pemilik) 
                VALUES ($customer_id,'$nama','$jenis','$ras'," . ($tgll ? "'$tgll'" : "NULL") . ",'$catatan')";

        if ($mysqli->query($sql)) {
            flash('Hewan Anda telah ditambahkan.');
            header('Location: hewan.php?action=list');
            exit;
        } else
            $errors[] = 'Gagal menyimpan: ' . $mysqli->error;

    } elseif ($sub === 'update') {
        $hid = (int) $_POST['hewan_id'];

        // Pastikan update HANYA pada hewan milik customer yang login
        $sql = "UPDATE hewan SET nama_hewan='$nama', jenis_hewan='$jenis', ras='$ras', 
                tanggal_lahir=" . ($tgll ? "'$tgll'" : "NULL") . ", catatan_pemilik='$catatan' 
                WHERE hewan_id = $hid AND customer_id = $customer_id";

        if ($mysqli->query($sql)) {
            flash('Perubahan data hewan disimpan.');
            header('Location: hewan.php?action=list');
            exit;
        } else
            $errors[] = 'Gagal menyimpan: ' . $mysqli->error;
    }
}

if ($action === 'delete' && $id) {
    // Pastikan delete HANYA pada hewan milik customer yang login
    $mysqli->query("DELETE FROM hewan WHERE hewan_id = $id AND customer_id = $customer_id");
    flash('Data hewan dihapus.');
    header('Location: hewan.php?action=list');
    exit;
}

// Sertakan header.php
include __DIR__ . '/../includes/header.php';
$msg = get_flash();
if ($msg)
    echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
foreach ($errors as $e)
    echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';

if ($action === 'list'):
    // Tampilkan HANYA hewan milik customer yang login
    $q = $mysqli->query("SELECT h.* FROM hewan h WHERE h.customer_id = $customer_id ORDER BY h.hewan_id DESC");
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Hewan Saya</h3>
        <a class="btn btn-success" href="hewan.php?action=create">Tambah Hewan Baru</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Tgl Lahir</th>
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
    <h3>Tambah Data Hewan</h3>
    <p>Daftarkan hewan peliharaan Anda di sini.</p>
    <form method="post">
        <input type="hidden" name="__action" value="create">

        <div class="mb-3"><label class="form-label">Nama hewan</label><input name="nama_hewan" class="form-control"
                required></div>
        <div class="mb-3"><label class="form-label">Jenis hewan (mis: Kucing, Anjing)</label><input name="jenis_hewan"
                class="form-control"></div>
        <div class="mb-3"><label class="form-label">Ras (mis: Persia, Golden Retriever)</label><input name="ras"
                class="form-control"></div>
        <div class="mb-3"><label class="form-label">Tanggal lahir</label><input name="tanggal_lahir" type="date"
                class="form-control"></div>
        <div class="mb-3"><label class="form-label">Catatan (Alergi, kondisi khusus, dll)</label><textarea
                name="catatan_pemilik" class="form-control"></textarea></div>
        <button class="btn btn-primary">Simpan</button>
        <a class="btn btn-link" href="hewan.php?action=list">Batal</a>
    </form>
    <?php
elseif ($action === 'edit' && $id):
    // Pastikan mengambil HANYA data hewan milik customer yang login
    $res = $mysqli->query("SELECT * FROM hewan WHERE hewan_id = $id AND customer_id = $customer_id LIMIT 1");
    if (!$res || $res->num_rows == 0) {
        flash('Hewan tidak ditemukan.');
        header('Location: hewan.php?action=list');
        exit;
    }
    $data = $res->fetch_assoc();
    ?>
    <h3>Edit Data Hewan</h3>
    <form method="post">
        <input type="hidden" name="__action" value="update">
        <input type="hidden" name="hewan_id" value="<?= (int) $data['hewan_id'] ?>">

        <div class="mb-3"><label class="form-label">Nama hewan</label><input name="nama_hewan" class="form-control" required
                value="<?= htmlspecialchars($data['nama_hewan']) ?>"></div>
        <div class_="mb-3"><label class="form-label">Jenis hewan</label><input name="jenis_hewan" class="form-control"
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
?>