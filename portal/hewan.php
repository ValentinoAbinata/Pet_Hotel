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
include __DIR__ . '/../includes/header.php';?>
<link rel="stylesheet" href="/PET_HOTEL/style/hewan.css">
<div class="hewan-container">
    <?php
    $msg = get_flash();
    if ($msg)
        echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
    foreach ($errors as $e)
        echo '<div class="alert alert-danger">' . htmlspecialchars($e) . '</div>';

    if ($action === 'list'):
        // Tampilkan HANYA hewan milik customer yang login
        $q = $mysqli->query("SELECT h.* FROM hewan h WHERE h.customer_id = $customer_id ORDER BY h.hewan_id DESC");
        ?>
        <div class="action-header">
            <h3>🐾 Hewan Saya</h3>
            <a class="btn btn-success" href="hewan.php?action=create">
                ➕ Tambah Hewan Baru
            </a>
        </div>
        
        <?php if ($q->num_rows > 0): ?>
            <div class="table-container">
                <table class="table">
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
                                <td data-label="ID"><strong>#<?= $r['hewan_id'] ?></strong></td>
                                <td data-label="Nama"><?= htmlspecialchars($r['nama_hewan']) ?></td>
                                <td data-label="Jenis"><?= htmlspecialchars($r['jenis_hewan']) ?></td>
                                <td data-label="Ras"><?= htmlspecialchars($r['ras']) ?></td>
                                <td data-label="Tgl Lahir"><?= $r['tanggal_lahir'] ? date('d/m/Y', strtotime($r['tanggal_lahir'])) : '-' ?></td>
                                <td data-label="Aksi">
                                    <div class="action-buttons">
                                        <a class="btn btn-sm btn-primary" href="hewan.php?action=edit&id=<?= $r['hewan_id'] ?>">
                                            ✏️ Edit
                                        </a>
                                        <a class="btn btn-sm btn-danger" href="hewan.php?action=delete&id=<?= $r['hewan_id'] ?>"
                                            onclick="return confirm('Hapus hewan ini?')">
                                            🗑️ Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="icon">🐕</div>
                <h5>Belum ada hewan terdaftar</h5>
                <p class="text-muted">Mulai daftarkan hewan peliharaan Anda</p>
                <a href="hewan.php?action=create" class="btn btn-success">Tambah Hewan Pertama</a>
            </div>
        <?php endif; ?>
        
    <?php elseif ($action === 'create'): ?>
        <div class="form-container">
            <h3>Tambah Data Hewan</h3>
            <p>Daftarkan hewan peliharaan Anda di sini.</p>
            <form method="post" id="hewanForm">
                <input type="hidden" name="__action" value="create">

                <div class="mb-3">
                    <label class="form-label">Nama hewan</label>
                    <input name="nama_hewan" class="form-control" required placeholder="Masukkan nama hewan">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis hewan</label>
                    <input name="jenis_hewan" class="form-control" placeholder="Contoh: Kucing, Anjing, Kelinci">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ras</label>
                    <input name="ras" class="form-control" placeholder="Contoh: Persia, Golden Retriever">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal lahir</label>
                    <input name="tanggal_lahir" type="date" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan khusus</label>
                    <textarea name="catatan_pemilik" class="form-control" rows="4" 
                            placeholder="Alergi, kondisi kesehatan khusus, kebiasaan, dll."></textarea>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-success">💾 Simpan Data</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='hewan.php?action=list'">✕ Batal</button>
                </div>
            </form>
        </div>

    <?php elseif ($action === 'edit' && $id):
        // Pastikan mengambil HANYA data hewan milik customer yang login
        $res = $mysqli->query("SELECT * FROM hewan WHERE hewan_id = $id AND customer_id = $customer_id LIMIT 1");
        if (!$res || $res->num_rows == 0) {
            flash('Hewan tidak ditemukan.');
            header('Location: hewan.php?action=list');
            exit;
        }
        $data = $res->fetch_assoc();
        ?>
        <div class="form-container">
            <h3>✏️ Edit Data Hewan</h3>
            <form method="post">
                <input type="hidden" name="__action" value="update">
                <input type="hidden" name="hewan_id" value="<?= (int) $data['hewan_id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Nama hewan</label>
                    <input name="nama_hewan" class="form-control" required value="<?= htmlspecialchars($data['nama_hewan']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis hewan</label>
                    <input name="jenis_hewan" class="form-control" value="<?= htmlspecialchars($data['jenis_hewan']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ras</label>
                    <input name="ras" class="form-control" value="<?= htmlspecialchars($data['ras']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal lahir</label>
                    <input name="tanggal_lahir" type="date" class="form-control" value="<?= $data['tanggal_lahir'] ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan pemilik</label>
                    <textarea name="catatan_pemilik" class="form-control" rows="4"><?= htmlspecialchars($data['catatan_pemilik']) ?></textarea>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-success">💾 Simpan Perubahan</button>
                    <a class="btn btn-link" href="hewan.php?action=list">✕ Batal</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">⚠️ Aksi tidak dikenal.</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>