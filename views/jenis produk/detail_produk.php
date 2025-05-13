<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

$koneksi = mysqli_connect($host, $username, $password, $database);
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil semua jenis produk untuk dropdown filter
$query_jenis = "SELECT * FROM jenis_produk ORDER BY nama ASC";
$result_jenis = mysqli_query($koneksi, $query_jenis);

// Filter produk berdasarkan jenis jika dipilih
$filter_jenis = isset($_GET['jenis']) ? intval($_GET['jenis']) : 0;

if ($filter_jenis > 0) {
    $query_produk = "SELECT p.*, j.nama AS jenis_nama 
                     FROM produk p
                     INNER JOIN jenis_produk j ON p.jenis_produk_id = j.id
                     WHERE p.jenis_produk_id = $filter_jenis 
                     ORDER BY p.id DESC";
} else {
    $query_produk = "SELECT p.*, j.nama AS jenis_nama 
                     FROM produk p
                     INNER JOIN jenis_produk j ON p.jenis_produk_id = j.id
                     ORDER BY p.id DESC";
}

$result_produk = mysqli_query($koneksi, $query_produk);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lihat Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h3>Daftar Produk Koperasi</h3>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="jenis" class="form-label">Filter Berdasarkan Jenis Produk</label>
            <select name="jenis" id="jenis" class="form-select" onchange="this.form.submit()">
                <option value="0">-- Semua Jenis --</option>
                <?php while ($jenis = mysqli_fetch_assoc($result_jenis)) : ?>
                    <option value="<?= $jenis['id']; ?>" <?= ($filter_jenis == $jenis['id']) ? 'selected' : ''; ?>>
                        <?= $jenis['nama']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jenis Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_produk) > 0): ?>
                    <?php $no = 1; while ($produk = mysqli_fetch_assoc($result_produk)) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $produk['nama']; ?></td>
                            <td><?= $produk['jenis_nama']; ?></td>
                            <td><?= $produk['deskripsi']; ?></td>
                            <td>Rp<?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                            <td><?= $produk['stok']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada produk ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="jenis_produk.php" class="btn btn-secondary mt-3">← Kembali ke Jenis Produk</a>
</div>
</body>
</html>
