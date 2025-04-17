<?php
// edit_barang.php
include 'db.php';

// Ambil data kategori untuk dropdown
$queryKategori = "SELECT * FROM kategori";
$resultKategori = mysqli_query($koneksi, $queryKategori);

// Cek apakah `id` tersedia
if (!isset($_GET['id'])) {
    header("Location: index.php?pesan=ID barang tidak ditemukan");
    exit;
}

$id = $_GET['id'];

// Ambil data barang berdasarkan id
$query = "SELECT * FROM barang WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: index.php?pesan=Data barang tidak ditemukan");
    exit;
}

$pesan = "";

if (isset($_POST['update'])) {
    $nama_barang   = trim($_POST['nama_barang']);
    $kategori_id   = $_POST['kategori_id'];
    $jumlah_stok   = $_POST['jumlah_stok'];
    $harga_barang  = $_POST['harga_barang'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // Validasi sederhana
    if ($nama_barang == "" || $jumlah_stok == "" || $harga_barang == "") {
        $pesan = "Harap isi semua kolom yang diperlukan!";
    } elseif (!is_numeric($jumlah_stok) || !is_numeric($harga_barang)) {
        $pesan = "Stok dan Harga harus berupa angka!";
    } else {
        // Update ke database
        $updateQuery = "UPDATE barang 
                        SET nama_barang='$nama_barang', 
                            kategori_id='$kategori_id', 
                            jumlah_stok='$jumlah_stok', 
                            harga_barang='$harga_barang', 
                            tanggal_masuk='$tanggal_masuk'
                        WHERE id='$id'";
        $update = mysqli_query($koneksi, $updateQuery);

        if ($update) {
            header("Location: index.php?pesan=Barang berhasil diupdate");
            exit;
        } else {
            $pesan = "Gagal mengupdate barang: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Barang</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">| RandiBarang</a>
  </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Edit Barang</h1>

    <?php if ($pesan != ""): ?>
      <div class="alert alert-danger">
        <?php echo $pesan; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label>Nama Barang</label>
        <input type="text" class="form-control" name="nama_barang" 
               value="<?php echo $data['nama_barang']; ?>" required>
      </div>
      <div class="mb-3">
        <label>Kategori</label>
        <!-- Kembalikan pointer resultKategori ke awal -->
        <?php 
          mysqli_data_seek($resultKategori, 0); 
        ?>
        <select name="kategori_id" class="form-select" required>
          <option value="">-- Pilih Kategori --</option>
          <?php while($rowCat = mysqli_fetch_assoc($resultKategori)): ?>
            <option value="<?php echo $rowCat['id']; ?>"
              <?php echo ($rowCat['id'] == $data['kategori_id']) ? 'selected' : ''; ?>>
              <?php echo $rowCat['nama_kategori']; ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label>Jumlah Stok</label>
        <input type="number" class="form-control" name="jumlah_stok" 
               value="<?php echo $data['jumlah_stok']; ?>" required>
      </div>
      <div class="mb-3">
        <label>Harga Barang</label>
        <input type="number" step="0.01" class="form-control" name="harga_barang" 
               value="<?php echo $data['harga_barang']; ?>" required>
      </div>
      <div class="mb-3">
        <label>Tanggal Masuk</label>
        <input type="date" class="form-control" name="tanggal_masuk" 
               value="<?php echo $data['tanggal_masuk']; ?>" required>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
