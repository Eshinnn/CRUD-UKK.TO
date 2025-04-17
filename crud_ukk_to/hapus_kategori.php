<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: kategori.php?pesan=ID kategori tidak ditemukan");
    exit;
}

$id = $_GET['id'];

// Hapus kategori
$query = "DELETE FROM kategori WHERE id='$id'";
$delete = mysqli_query($koneksi, $query);

if ($delete) {
    header("Location: kategori.php?pesan=Kategori berhasil dihapus");
} else {
    header("Location: kategori.php?pesan=Gagal menghapus kategori");
}
exit;
?>
