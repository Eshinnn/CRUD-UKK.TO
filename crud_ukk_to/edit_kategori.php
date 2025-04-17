<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: kategori.php?pesan=ID kategori tidak ditemukan");
    exit;
}

$id = $_GET['id'];

// Ambil data kategori
$query = "SELECT * FROM kategori WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: kategori.php?pesan=Data kategori tidak ditemukan");
    exit;
}

$pesan = "";

if (isset($_POST['update'])) {
    $nama_kategori = trim($_POST['nama_kategori']);

    if ($nama_kategori == "") {
        $pesan = "Nama kategori tidak boleh kosong!";
    } else {
        $updateQuery = "UPDATE kategori SET nama_kategori='$nama_kategori' WHERE id='$id'";
        $update = mysqli_query($koneksi, $updateQuery);

        if ($update) {
            header("Location: kategori.php?pesan=Kategori berhasil diupdate");
            exit;
        } else {
            $pesan = "Gagal mengupdate kategori: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Kategori</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Inventori Barang</a>
  </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Edit Kategori</h1>

    <?php if ($pesan != ""): ?>
      <div class="alert alert-danger">
        <?php echo $pesan; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label>Nama Kategori</label>
        <input type="text" class="form-control" name="nama_kategori" 
               value="<?php echo $data['nama_kategori']; ?>" required>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="kategori.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
