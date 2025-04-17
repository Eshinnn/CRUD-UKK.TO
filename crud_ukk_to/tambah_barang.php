<?php
include 'db.php';

// Ambil data kategori untuk dropdown
$queryKategori = "SELECT * FROM kategori";
$resultKategori = mysqli_query($koneksi, $queryKategori);

// Inisialisasi notifikasi masuk
$pesan = "";

if (isset($_POST['submit'])) {
    $nama_barang   = trim($_POST['nama_barang']);
    $kategori_id   = $_POST['kategori_id'];
    $jumlah_stok   = $_POST['jumlah_stok'];
    $harga_barang  = $_POST['harga_barang'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // Validasi wajib isi
    if ($nama_barang == "" || $jumlah_stok == "" || $harga_barang == "") {
        $pesan = "Harap isi semua kolom yang diperlukan!";
    } elseif (!is_numeric($jumlah_stok) || !is_numeric($harga_barang)) {
        $pesan = "Stok dan Harga harus berupa angka!";
    } else {
        // Insert ke DB
        $query = "INSERT INTO barang (nama_barang, kategori_id, jumlah_stok, harga_barang, tanggal_masuk)
                  VALUES ('$nama_barang', '$kategori_id', '$jumlah_stok', '$harga_barang', '$tanggal_masuk')";
        $insert = mysqli_query($koneksi, $query);

        if ($insert) {
            // klo Berhasil
            header("Location: index.php?pesan=Barang berhasil ditambahkan");
            exit;
        } else {
            // klo Gagal
            $pesan = "Gagal menambah barang: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Barang</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">| RandiBarang</a>
  </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Tambah Barang</h1>

    <?php if ($pesan != ""): ?>
      <div class="alert alert-warning">
        <?php echo $pesan; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label>Nama Barang</label>
        <input type="text" class="form-control" name="nama_barang" required>
      </div>
      <div class="mb-3">
        <label>Kategori</label>
        <select name="kategori_id" class="form-select" required>
          <option value="">-- Pilih Kategori --</option>
          <?php while($rowCat = mysqli_fetch_assoc($resultKategori)): ?>
            <option value="<?php echo $rowCat['id']; ?>">
              <?php echo $rowCat['nama_kategori']; ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-3">
        <label>Jumlah Stok</label>
        <input type="number" class="form-control" name="jumlah_stok" required>
      </div>
      <div class="mb-3">
        <label>Harga Barang</label>
        <input type="number" step="0.01" class="form-control" name="harga_barang" required>
      </div>
      <div class="mb-3">
        <label>Tanggal Masuk</label>
        <input type="date" class="form-control" name="tanggal_masuk" required>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
