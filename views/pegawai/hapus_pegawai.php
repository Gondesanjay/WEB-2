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

// Pastikan ID ada
if (!isset($_GET['id'])) {
    header("Location: list-pegawai.php?error=ID tidak ditemukan");
    exit;
}

$id = intval($_GET['id']);

// Hapus data
$sql = "DELETE FROM pegawai WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: list-pegawai.php?success=Pegawai berhasil dihapus");
} else {
    header("Location: list-pegawai.php?error=Gagal menghapus pegawai");
}
?>
