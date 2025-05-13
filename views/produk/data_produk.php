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
    $kode = htmlspecialchars($_POST['kode']);
    $nama = htmlspecialchars($_POST['nama']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $harga = htmlspecialchars($_POST['harga']);
    $stok = htmlspecialchars($_POST['stok']);
    $jenis_produk_id = htmlspecialchars($_POST['jenis_produk_id']);

    $query = "INSERT INTO produk (kode, nama, deskripsi, harga, stok, jenis_produk_id) 
              VALUES ('$kode', '$nama', '$deskripsi', '$harga', '$stok', '$jenis_produk_id')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data produk berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Proses Edit Data
if (isset($_POST['edit'])) {
    $id = htmlspecialchars($_POST['id']);
    $kode = htmlspecialchars($_POST['kode']);
    $nama = htmlspecialchars($_POST['nama']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $harga = htmlspecialchars($_POST['harga']);
    $stok = htmlspecialchars($_POST['stok']);
    $jenis_produk_id = htmlspecialchars($_POST['jenis_produk_id']);

    $query = "UPDATE produk SET 
              kode = '$kode',
              nama = '$nama',
              deskripsi = '$deskripsi',
              harga = '$harga',
              stok = '$stok',
              jenis_produk_id = '$jenis_produk_id'
              WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data produk berhasil diperbarui');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = htmlspecialchars($_GET['hapus']);
    
    $query = "DELETE FROM produk WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data produk berhasil dihapus');</script>";
        echo "<script>window.location = 'data_produk.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Fungsi untuk mendapatkan data jenis produk
function getJenisProduk($koneksi) {
    $query = "SELECT * FROM jenis_produk";
    $result = mysqli_query($koneksi, $query);
    $jenisArr = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $jenisArr[$row['id']] = $row['nama'];
    }
    
    return $jenisArr;
}

$jenisProduk = getJenisProduk($koneksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Koperasi Pegawai</title>
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
        
        <h2>Data Produk</h2>
        <p>Kelola data produk koperasi pegawai</p>
        <div class="container">
            <a href="../index.php" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali
            </a>
        
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </button>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Jenis Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT p.*, jp.nama as jenis_nama 
                             FROM produk p 
                             LEFT JOIN jenis_produk jp ON p.jenis_produk_id = jp.id 
                             ORDER BY p.id DESC";
                    $result = mysqli_query($koneksi, $query);
                    $no = 1;
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['kode']; ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['deskripsi']; ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><?= $row['stok']; ?></td>
                        <td><?= $row['jenis_nama']; ?></td>
                        <td class="action-buttons">
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id']; ?>">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <a href="data_produk.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit<?= $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Data Produk</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label for="kode" class="form-label">Kode Produk</label>
                                            <input type="text" class="form-control" id="kode" name="kode" value="<?= $row['kode']; ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">Nama Produk</label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?= $row['nama']; ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?= $row['deskripsi']; ?></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="harga" class="form-label">Harga</label>
                                            <input type="number" class="form-control" id="harga" name="harga" value="<?= $row['harga']; ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="stok" class="form-label">Stok</label>
                                            <input type="number" class="form-control" id="stok" name="stok" value="<?= $row['stok']; ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="jenis_produk_id" class="form-label">Jenis Produk</label>
                                            <select class="form-select" id="jenis_produk_id" name="jenis_produk_id" required>
                                                <option value="">Pilih Jenis Produk</option>
                                                <?php foreach ($jenisProduk as $id => $nama) : ?>
                                                    <option value="<?= $id; ?>" <?= ($row['jenis_produk_id'] == $id) ? 'selected' : ''; ?>><?= $nama; ?></option>
                                                <?php endforeach; ?>
                                            </select>
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
                        <td colspan="8" class="text-center">Tidak ada data produk</td>
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
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
               
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Produk</label>
                            <input type="text" class="form-control" id="kode" name="kode" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jenis_produk_id" class="form-label">Jenis Produk</label>
                            <select class="form-select" id="jenis_produk_id" name="jenis_produk_id" required>
                                <option value="">Pilih Jenis Produk</option>
                                <?php foreach ($jenisProduk as $id => $nama) : ?>
                                    <option value="<?= $id; ?>"><?= $nama; ?></option>
                                <?php endforeach; ?>
                            </select>
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