<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php?pesan=ID barang tidak ditemukan");
    exit;
}

$id = $_GET['id'];

// Query hapus
$query = "DELETE FROM barang WHERE id='$id'";
$delete = mysqli_query($koneksi, $query);

if ($delete) {
    header("Location: index.php?pesan=Barang berhasil dihapus");
} else {
    header("Location: index.php?pesan=Gagal menghapus barang");
}
exit;
?>
