<?php
// tambah_kategori.php
include 'db.php'; // Pastikan isinya sama dengan step 1 (koneksi)

$pesan = "";

// Proses menambah kategori
if (isset($_POST['submit'])) {
    $nama_kategori = trim($_POST['nama_kategori']);

    if ($nama_kategori == "") {
        $pesan = "Nama kategori tidak boleh kosong!";
    } else {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
        $insert = mysqli_query($koneksi, $query);

        if ($insert) {
            // Jika berhasil, redirect ke halaman daftar kategori atau tampilkan pesan
            header("Location: kategori.php?pesan=Kategori berhasil ditambahkan");
            exit;
        } else {
            // Jika error, tampilkan pesan error
            $pesan = "Gagal menambah kategori: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Kategori</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">| RandiBarang</a>
  </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Tambah Kategori</h1>

    <?php if ($pesan != ""): ?>
      <div class="alert alert-warning">
        <?php echo $pesan; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" id="nama_kategori" class="form-control" name="nama_kategori" required>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
      <a href="kategori.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
