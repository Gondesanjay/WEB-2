<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_koperasi";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk sanitasi input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $nip = sanitize($_POST['nip']);
    $nama = sanitize($_POST['nama']);
    $jenis_kelamin = sanitize($_POST['jenis_kelamin']);
    $jabatan = sanitize($_POST['jabatan']);

    $sql = "UPDATE pegawai SET nip = ?, nama = ?, jenis_kelamin = ?, jabatan = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nip, $nama, $jenis_kelamin, $jabatan, $id);

    if ($stmt->execute()) {
        header("Location: list-pegawai.php?success=Pegawai berhasil diperbarui");
    } else {
        header("Location: list-pegawai.php?error=Gagal memperbarui pegawai");
    }
} else {
    header("Location: list-pegawai.php?error=Akses tidak sah");
}
?>
