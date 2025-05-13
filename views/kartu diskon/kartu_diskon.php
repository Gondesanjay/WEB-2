<?php
// Database connection configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

// Establish database connection
$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$id = "";
$nama = "";
$deskripsi = "";
$persen_diskon = "";
$error = "";
$success = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's delete operation
    if (isset($_POST['delete'])) {
        $id = mysqli_real_escape_string($conn, $_POST['delete']);
        
        $delete_query = "DELETE FROM kartu_diskon WHERE id = '$id'";
        if (mysqli_query($conn, $delete_query)) {
            $success = "Kartu diskon berhasil dihapus";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } 
    // Check if it's edit operation (fetching data)
    else if (isset($_POST['edit'])) {
        $id = mysqli_real_escape_string($conn, $_POST['edit']);
        
        $select_query = "SELECT * FROM kartu_diskon WHERE id = '$id'";
        $result = mysqli_query($conn, $select_query);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $nama = $row['nama'];
            $deskripsi = $row['deskripsi'];
            $persen_diskon = $row['persen_diskon'];
        }
    } 
    // Save or update operation
    else {
        // Get form data
        $id = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : "";
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        $persen_diskon = mysqli_real_escape_string($conn, $_POST['persen_diskon']);
        
        // Validate inputs
        if (empty($nama)) {
            $error = "Nama kartu diskon harus diisi";
        } else if (!is_numeric($persen_diskon)) {
            $error = "Persentase diskon harus berupa angka";
        } else {
            // If ID exists, update; otherwise insert
            if (!empty($id)) {
                $query = "UPDATE kartu_diskon SET nama = '$nama', deskripsi = '$deskripsi', persen_diskon = '$persen_diskon' WHERE id = '$id'";
                $success_message = "Kartu diskon berhasil diperbarui";
            } else {
                $query = "INSERT INTO kartu_diskon (nama, deskripsi, persen_diskon) VALUES ('$nama', '$deskripsi', '$persen_diskon')";
                $success_message = "Kartu diskon berhasil ditambahkan";
            }
            
            if (mysqli_query($conn, $query)) {
                $success = $success_message;
                // Clear form fields after successful submission
                $id = "";
                $nama = "";
                $deskripsi = "";
                $persen_diskon = "";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch all kartu diskon
$select_all = "SELECT * FROM kartu_diskon ORDER BY id DESC";
$result = mysqli_query($conn, $select_all);
$kartu_diskon = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kartu Diskon - Koperasi Pegawai</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 1200px;
            margin-top: 30px;
        }
        .form-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .alert {
            padding: 10px 15px;
        }
    </style>

    <div class="container">
        <h2 class="mb-4">Manajemen Kartu Diskon</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <div class="form-container">
            <h4><?php echo empty($id) ? 'Tambah' : 'Edit'; ?> Kartu Diskon</h4>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Kartu Diskon</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo $deskripsi; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="persen_diskon" class="form-label">Persentase Diskon (%)</label>
                    <input type="number" class="form-control" id="persen_diskon" name="persen_diskon" value="<?php echo $persen_diskon; ?>" min="0" max="100" required>
                </div>
                
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary">
                        <?php echo empty($id) ? 'Simpan' : 'Update'; ?>
                    </button>
                    <a href="../index.php" class="btn btn-secondary mb" style="margin-left: 10px;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    <?php if (!empty($id)): ?>
                        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-secondary ms-2">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Kartu</th>
                        <th>Deskripsi</th>
                        <th>Persentase Diskon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($kartu_diskon) > 0): ?>
                        <?php foreach ($kartu_diskon as $kartu): ?>
                            <tr>
                                <td><?php echo $kartu['id']; ?></td>
                                <td><?php echo htmlspecialchars($kartu['nama']); ?></td>
                                <td><?php echo htmlspecialchars($kartu['deskripsi']); ?></td>
                                <td><?php echo $kartu['persen_diskon']; ?>%</td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <button type="submit" name="edit" value="<?php echo $kartu['id']; ?>" class="btn btn-sm btn-warning">Edit</button>
                                    </form>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kartu diskon ini?');">
                                        <button type="submit" name="delete" value="<?php echo $kartu['id']; ?>" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data kartu diskon</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>