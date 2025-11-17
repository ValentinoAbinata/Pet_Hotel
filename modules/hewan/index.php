<?php
include_once '../../includes/functions.php';
if (!isLoggedIn() || !hasRole('admin')) {
    redirect('../../login.php');
}
include_once '../../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Hewan - Pet Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../../includes/sidebar-admin.php'; ?>
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Data Hewan</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahHewanModal">Tambah Hewan</button>
        </div>

        <?php
        // Handle CRUD operations
        if ($_POST && isset($_POST['action'])) {
            if ($_POST['action'] == 'tambah') {
                $customer_id = sanitize($_POST['customer_id']);
                $nama_hewan = sanitize($_POST['nama_hewan']);
                $jenis_hewan = sanitize($_POST['jenis_hewan']);
                $ras = sanitize($_POST['ras']);
                $tanggal_lahir = sanitize($_POST['tanggal_lahir']);
                $catatan_pemilik = sanitize($_POST['catatan_pemilik']);
                
                $query = "INSERT INTO hewan (customer_id, nama_hewan, jenis_hewan, ras, tanggal_lahir, catatan_pemilik) 
                         VALUES ('$customer_id', '$nama_hewan', '$jenis_hewan', '$ras', '$tanggal_lahir', '$catatan_pemilik')";
                mysqli_query($conn, $query);
            }
        }
        ?>

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Hewan</th>
                        <th>Jenis</th>
                        <th>Ras</th>
                        <th>Pemilik</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT h.*, u.nama_lengkap as nama_pemilik 
                             FROM hewan h 
                             JOIN users u ON h.customer_id = u.user_id";
                    $result = mysqli_query($conn, $query);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$row['hewan_id']}</td>
                            <td>{$row['nama_hewan']}</td>
                            <td>{$row['jenis_hewan']}</td>
                            <td>{$row['ras']}</td>
                            <td>{$row['nama_pemilik']}</td>
                            <td>
                                <button class='btn btn-sm btn-warning'>Edit</button>
                                <button class='btn btn-sm btn-danger'>Hapus</button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal Tambah Hewan -->
    <div class="modal fade" id="tambahHewanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="action" value="tambah">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Hewan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pemilik</label>
                            <select name="customer_id" class="form-control" required>
                                <option value="">Pilih Pemilik</option>
                                <?php
                                $customers = mysqli_query($conn, "SELECT user_id, nama_lengkap FROM users WHERE peran='customer'");
                                while ($customer = mysqli_fetch_assoc($customers)) {
                                    echo "<option value='{$customer['user_id']}'>{$customer['nama_lengkap']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Hewan</label>
                            <input type="text" name="nama_hewan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Hewan</label>
                            <input type="text" name="jenis_hewan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ras</label>
                            <input type="text" name="ras" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Pemilik</label>
                            <textarea name="catatan_pemilik" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>