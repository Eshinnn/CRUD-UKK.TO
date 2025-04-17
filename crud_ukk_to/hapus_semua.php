<?php
// hapus_semua.php
include 'db.php';

// Hapus semua baris dari tabel barang
mysqli_query($koneksi, "DELETE FROM barang");

// Redirect kembali ke index dengan notifikasi
header("Location: index.php?pesan=Semua data barang berhasil dihapus");
exit;
?>
