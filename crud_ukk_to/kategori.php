<?php
include 'db.php';

$query = "SELECT * FROM kategori ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Kategori</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Inventori Barang</a>
  </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Daftar Kategori</h1>

    <?php if (isset($_GET['pesan'])): ?>
      <div class="alert alert-info">
        <?php echo $_GET['pesan']; ?>
      </div>
    <?php endif; ?>

    <a href="tambah_kategori.php" class="btn btn-success mb-3">+ Tambah Kategori</a>
    <a href="index.php" class="btn btn-secondary mb-3">kembali</a>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;
        while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td><?php echo $row['nama_kategori']; ?></td>
          <td>
  <a href="edit_kategori.php?id=<?php echo $row['id']; ?>" 
     class="btn btn-sm"
     style="border: 1px solid green; background-color: transparent; color: green;">
     Edit
  </a>

  <a href="hapus_kategori.php?id=<?php echo $row['id']; ?>" 
     onclick="return confirm('Yakin mau dihapus?')" 
     class="btn btn-sm"
     style="border: 1px solid red; background-color: transparent; color: red;">
     Hapus
  </a>
</td>

        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
