<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Inisialisasi variabel
$error = "";
$success = "";

// Proses Pembayaran
if (isset($_GET['bayar'])) {
    $id_pesanan = intval($_GET['bayar']);
    $query_update = "UPDATE pesanan SET status_bayar = 1 WHERE id = $id_pesanan";

    if (mysqli_query($conn, $query_update)) {
        $success = "Pesanan ID $id_pesanan telah berhasil dibayar.";
    } else {
        $error = "Gagal memproses pembayaran.";
    }
}

// Proses penghapusan pesanan
if (isset($_GET['hapus'])) {
    $hapus_id = intval($_GET['hapus']);

    // Ambil detail pesanan untuk update stok
    $query_detail = "SELECT produk_id, jumlah FROM detail_pesanan WHERE pesanan_id = $hapus_id";
    $result_detail = mysqli_query($conn, $query_detail);

    if (mysqli_num_rows($result_detail) > 0) {
        while ($detail = mysqli_fetch_assoc($result_detail)) {
            $produk_id = $detail['produk_id'];
            $jumlah_dipesan = $detail['jumlah'];

            // Kembalikan stok produk
            $query_update_stok = "UPDATE produk SET stok = stok + $jumlah_dipesan WHERE id = $produk_id";
            mysqli_query($conn, $query_update_stok);
        }

        // Hapus detail pesanan dan pesanan
        mysqli_query($conn, "DELETE FROM detail_pesanan WHERE pesanan_id = $hapus_id");
        mysqli_query($conn, "DELETE FROM pesanan WHERE id = $hapus_id");

        $success = "Pesanan berhasil dihapus.";
    } else {
        $error = "Data pesanan tidak ditemukan.";
    }
}

// Mengambil semua data pemesanan
$query_pemesanan = "SELECT p.id, p.tanggal, p.status_bayar, p2.nama as anggota_nama, 
                   GROUP_CONCAT(pr.nama SEPARATOR ', ') as produk_nama, 
                   SUM(dp.jumlah) as total_item,
                   SUM(pr.harga * dp.jumlah) as total_harga,
                   p.diskon
                   FROM pesanan p
                   JOIN anggota a ON p.anggota_id = a.id
                   JOIN pegawai p2 ON a.pegawai_id = p2.id
                   JOIN detail_pesanan dp ON p.id = dp.pesanan_id
                   JOIN produk pr ON dp.produk_id = pr.id
                   GROUP BY p.id
                   ORDER BY p.tanggal DESC";
$result_pemesanan = mysqli_query($conn, $query_pemesanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pembayaran - Koperasi Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4 text-center">Daftar Pembayaran Pemesanan</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Tabel Pemesanan -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0">Data Pemesanan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Anggota</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Diskon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if (mysqli_num_rows($result_pemesanan) > 0):
                            while ($pesanan = mysqli_fetch_assoc($result_pemesanan)):
                                $harga_sebelum_diskon = $pesanan['total_harga'];
                                $nilai_diskon = ($harga_sebelum_diskon * $pesanan['diskon']) / 100;
                                $harga_setelah_diskon = $harga_sebelum_diskon - $nilai_diskon;
                        ?>
                            <tr>
                                <td><?php echo $pesanan['id']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($pesanan['tanggal'])); ?></td>
                                <td><?php echo $pesanan['anggota_nama']; ?></td>
                                <td><?php echo $pesanan['produk_nama']; ?></td>
                                <td><?php echo $pesanan['total_item']; ?></td>
                                <td>
                                    <?php if ($pesanan['diskon'] > 0): ?>
                                        <del class="text-muted">Rp <?php echo number_format($harga_sebelum_diskon, 0, ',', '.'); ?></del><br>
                                        <strong>Rp <?php echo number_format($harga_setelah_diskon, 0, ',', '.'); ?></strong>
                                    <?php else: ?>
                                        Rp <?php echo number_format($harga_sebelum_diskon, 0, ',', '.'); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $pesanan['diskon'] > 0 ? $pesanan['diskon'] . '% (Rp ' . number_format($nilai_diskon, 0, ',', '.') . ')' : '-'; ?>
                                </td>
                                <td>
                                    <?php if ($pesanan['status_bayar'] == 1): ?>
                                        <span class="badge bg-success">Dibayar</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Belum Dibayar</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($pesanan['status_bayar'] == 0): ?>
                                        <a href="?bayar=<?php echo $pesanan['id']; ?>" class="btn btn-sm btn-primary" onclick="return confirm('Yakin ingin memproses pembayaran?')">Bayar</a>
                                    <?php endif; ?>
                                    <a href="?hapus=<?php echo $pesanan['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pemesanan</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="../index.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
