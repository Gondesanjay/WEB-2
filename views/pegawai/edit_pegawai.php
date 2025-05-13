<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi sanitasi
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Ambil data pegawai berdasarkan ID
if (!isset($_GET['id'])) {
    header("Location: index.php?error=ID tidak ditemukan");
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM pegawai WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pegawai = $result->fetch_assoc();

if (!$pegawai) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit;
}

// Proses update jika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nip = sanitize($_POST['nip']);
    $nama = sanitize($_POST['nama']);
    $jenis_kelamin = sanitize($_POST['jenis_kelamin']);
    $jabatan = sanitize($_POST['jabatan']);

    $sql_update = "UPDATE pegawai SET nip=?, nama=?, jenis_kelamin=?, jabatan=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nip, $nama, $jenis_kelamin, $jabatan, $id);

    if ($stmt_update->execute()) {
        header("Location: list-pegawai.php?success=Data pegawai berhasil diperbarui");
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui data</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Pegawai</h2>

    <form method="post" action="">
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $pegawai['nip']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $pegawai['nama']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" name="jenis_kelamin" required>
                <option value="L" <?php if ($pegawai['jenis_kelamin'] === 'L') echo 'selected'; ?>>Laki-laki</option>
                <option value="P" <?php if ($pegawai['jenis_kelamin'] === 'P') echo 'selected'; ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo $pegawai['jabatan']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="list-pegawai.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
