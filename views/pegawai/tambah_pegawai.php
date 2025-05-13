<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk sanitasi input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nip = sanitize($_POST["nip"]);
    $nama = sanitize($_POST["nama"]);
    $jenis_kelamin = sanitize($_POST["jenis_kelamin"]);
    $jabatan = sanitize($_POST["jabatan"]);

    // Query tanpa memasukkan kolom 'id'
    $sql = "INSERT INTO pegawai (nip, nama, jenis_kelamin, jabatan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nip, $nama, $jenis_kelamin, $jabatan);

    if ($stmt->execute()) {
        // Redirect ke halaman list dengan pesan sukses
        header("Location: list-pegawai.php?success=Pegawai berhasil ditambahkan");
    } else {
        // Redirect dengan pesan error
        header("Location: list-pegawai.php?error=Gagal menambahkan pegawai");
    }
    exit;
}
?>

<!-- Form Tambah Pegawai -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Tambah Pegawai</h2>
    <form method="post" action="tambah_pegawai.php">
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" name="nip" id="nip" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" id="nama" required>
        </div>
        <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="">Pilih</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" name="jabatan" id="jabatan" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="list-pegawai.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
