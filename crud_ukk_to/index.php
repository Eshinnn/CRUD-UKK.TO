<?php
include 'db.php';

// --- Pagination Setup ---
$limit  = 5;
$page   = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- Fetch Categories for Filter ---
$queryKategori  = "SELECT * FROM kategori";
$resultKategori = mysqli_query($koneksi, $queryKategori);

// --- Build WHERE Clause for Search/Filter ---
$whereClause = [];
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
    $whereClause[] = "nama_barang LIKE '%$search%'";
}
if (isset($_GET['kategori']) && $_GET['kategori'] !== "") {
    $kategori_id = (int)$_GET['kategori'];
    $whereClause[] = "kategori_id = $kategori_id";
}
$sqlWhere = !empty($whereClause) ? ' WHERE ' . implode(' AND ', $whereClause) : '';

// --- Count Total Rows for Pagination ---
$countSql  = "SELECT COUNT(*) AS total
              FROM barang
              JOIN kategori ON barang.kategori_id = kategori.id"
             . $sqlWhere;
$countRes  = mysqli_query($koneksi, $countSql);
$totalRow  = mysqli_fetch_assoc($countRes)['total'];
$totalPage = ceil($totalRow / $limit);

// --- Fetch Paginated Data ---
$queryBarang  = "SELECT barang.*, kategori.nama_kategori
                 FROM barang
                 JOIN kategori ON barang.kategori_id = kategori.id"
                . " $sqlWhere
                   ORDER BY barang.id DESC
                   LIMIT $limit OFFSET $offset";
$resultBarang = mysqli_query($koneksi, $queryBarang);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>List Barang | RandiBarang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <!-- Sidebar Toggle for Mobile -->
  <button class="btn btn-outline-secondary d-lg-none m-2" 
          data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
    <i class="bi bi-list"></i>
  </button>

  <!-- Offcanvas (Mobile) -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
      <nav class="navbar navbar-dark bg-dark flex-column p-3">
        <a href="index.php" class="d-flex align-items-center mb-3 text-white text-decoration-none">
          <span class="fs-4">RandiBarang</span>
        </a>
        <hr class="text-secondary">
        <ul class="nav nav-pills flex-column mb-auto">
          <li class="nav-item">
            <a href="index.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">
              <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
          </li>
          <li>
            <a href="#masterSubmenuMobile" class="nav-link text-white" data-bs-toggle="collapse">
              <i class="bi bi-archive me-2"></i> Master Data
            </a>
            <div class="collapse ps-3" id="masterSubmenuMobile">
              <ul class="btn-toggle-nav list-unstyled fw-normal mb-1">
                <li><a href="kategori.php" class="link-white d-block <?= basename($_SERVER['PHP_SELF'])=='kategori.php'?'active':'' ?>">Kategori</a></li>
              </ul>
            </div>
          </li>
          <li>
            <a href="export.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF'])=='export.php'?'active':'' ?>">
              <i class="bi bi-download me-2"></i> Export Data CSV
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>

  <div class="d-flex">
    <!-- Sidebar (Desktop) -->
    <div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 bg-dark" style="width: 250px; height: 100vh;">
      <a href="index.php" class="d-flex align-items-center mb-3 text-white text-decoration-none">
        <span class="fs-4">RandiBarang </span>
      </a>
      <hr class="text-secondary">
      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
          <a href="index.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
          </a>
        </li>
        <li>
          <a href="#masterSubmenu" class="nav-link text-white" data-bs-toggle="collapse">
            <i class="bi bi-archive me-2"></i> Master Data
          </a>
          <div class="collapse ps-3" id="masterSubmenu">
            <ul class="btn-toggle-nav list-unstyled fw-normal mb-1">
              <li><a href="kategori.php" class="link-white d-block <?= basename($_SERVER['PHP_SELF'])=='kategori.php'?'active':'' ?>">Kategori</a></li>
            </ul>
          </div>
        </li>
        <li>
          <a href="export.php" class="nav-link text-white <?= basename($_SERVER['PHP_SELF'])=='export.php'?'active':'' ?>">
            <i class="bi bi-download me-2"></i>  Export Data CSV
          </a>
        </li>
      </ul>
      <hr class="text-secondary">
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
           id="userDropdown" data-bs-toggle="dropdown">
          <img src="https://via.placeholder.com/32" alt="User" class="rounded-circle me-2">
          <strong>Admin</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="userDropdown">
          <li><a class="dropdown-item" href="#">Profile</a></li>
          <li><a class="dropdown-item" href="logout.php">
      <i class="bi bi-box-arrow-right me-1"></i> Logout
      </a></li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <div class="container mt-4">
        <h1 class="mb-4">List Barang</h1>

        <!-- Notification -->
        <?php if (isset($_GET['pesan'])): ?>
          <div class="alert alert-info">
            <?= htmlspecialchars($_GET['pesan']); ?>
          </div>
        <?php endif; ?>

        <!-- Search & Filter Form -->
        <form class="row g-3 mb-3" method="GET" action="">
          <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Cari nama barang"
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
          </div>
          <div class="col-auto">
            <select name="kategori" class="form-select">
              <option value="">-- Filter Kategori --</option>
              <?php mysqli_data_seek($resultKategori, 0);
              while ($rowCat = mysqli_fetch_assoc($resultKategori)): ?>
                <option value="<?= $rowCat['id']; ?>"
                  <?= (isset($_GET['kategori']) && $_GET['kategori'] == $rowCat['id']) ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($rowCat['nama_kategori']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Cari / Filter</button>
          </div>
        </form>

        <!-- Action Buttons -->
        <div class="mb-3">
          <a href="tambah_barang.php" class="btn btn-success">+ Tambah Barang</a>
          <a href="hapus_semua.php" class="btn btn-danger"
             onclick="return confirm('Apakah Anda yakin ingin menghapus SEMUA barang?');">
            Reset Data
          </a>
         
        </div>

        <!-- Data Table -->
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th>Stok</th>
              <th>Harga</th>
              <th>Tanggal Masuk</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = $offset + 1;
            while ($row = mysqli_fetch_assoc($resultBarang)): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= htmlspecialchars($row['nama_barang']); ?></td>
              <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
              <td><?= $row['jumlah_stok']; ?></td>
              <td>Rp <?= number_format($row['harga_barang'], 0, ',', '.'); ?></td>
              <td><?= htmlspecialchars($row['tanggal_masuk']); ?></td>
              <td>
              <a href="edit_barang.php?id=<?= $row['id']; ?>" class="btn btn-outline-success btn-sm">Edit</a>
              <a href="hapus_barang.php?id=<?= $row['id']; ?>" class="btn btn-outline-danger btn-sm">Hapus</a>

              </td>
            </tr>
            <?php endwhile; ?>
          </tbody> #
        </table>

        <!-- Pagination -->
        <nav>
          <ul class="pagination">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">&laquo;</a>
            </li>
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $totalPage ? 'disabled' : '' ?>">
              <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">&raquo;</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
