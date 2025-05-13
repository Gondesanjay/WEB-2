<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Proses Tambah Data
if (isset($_POST['tambah'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    $query = "INSERT INTO jenis_produk (nama, deskripsi) VALUES ('$nama', '$deskripsi')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data jenis produk berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Proses Edit Data
if (isset($_POST['edit'])) {
    $id = htmlspecialchars($_POST['id']);
    $nama = htmlspecialchars($_POST['nama']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    $query = "UPDATE jenis_produk SET nama = '$nama', deskripsi = '$deskripsi' WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data jenis produk berhasil diperbarui');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = htmlspecialchars($_GET['hapus']);
    
    // Cek apakah jenis produk digunakan di tabel produk
    $check_query = "SELECT COUNT(*) as total FROM produk WHERE jenis_produk_id = '$id'";
    $check_result = mysqli_query($koneksi, $check_query);
    $check_data = mysqli_fetch_assoc($check_result);
    
    if ($check_data['total'] > 0) {
        echo "<script>alert('Jenis produk ini tidak dapat dihapus karena masih digunakan oleh " . $check_data['total'] . " produk');</script>";
    } else {
        $query = "DELETE FROM jenis_produk WHERE id = '$id'";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Data jenis produk berhasil dihapus');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
        }
    }
    
    echo "<script>window.location = 'jenis_produk.php';</script>";
}

// Hitung jumlah produk untuk setiap jenis
function hitungJumlahProduk($koneksi, $jenis_id) {
    $query = "SELECT COUNT(*) as total FROM produk WHERE jenis_produk_id = '$jenis_id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jenis Produk - Koperasi Pegawai</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .container {
            max-width: 1200px;
            margin-top: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .action-buttons {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <h2>Data Jenis Produk</h2>
        <p>Kelola kategori/jenis produk koperasi pegawai</p>
        
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Jenis Produk
        </button>
        <a href="../index.php" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Jenis</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM jenis_produk ORDER BY id DESC";
                    $result = mysqli_query($koneksi, $query);
                    $no = 1;
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $jumlah_produk = hitungJumlahProduk($koneksi, $row['id']);
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['deskripsi']; ?></td>
                        <td><?= $jumlah_produk; ?></td>
                        <td class="action-buttons">
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <a href="jenis_produk.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jenis produk ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                            <a href="detail_produk.php?jenis=<?= $row['id']; ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-box-seam"></i> Lihat Produk
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Jenis Produk</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama Jenis</label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?= $row['nama']; ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= $row['deskripsi']; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data jenis produk</td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Jenis Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Jenis</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>