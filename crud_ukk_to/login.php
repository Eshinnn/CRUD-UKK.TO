<?php
session_start();
include 'db.php';
if (isset($_SESSION['user_id'])) {
    header('Location: index.php'); exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Query user berdasarkan username
    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $res = mysqli_query($koneksi, $sql);
    if ($res && mysqli_num_rows($res) === 1) {
        $user = mysqli_fetch_assoc($res);
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];

            // Redirect ke halaman utama
            header('Location: index.php');
            exit;
        }
    }
    $error = 'Username atau password salah.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | RandiBarang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="card p-4 shadow-sm" style="width: 350px;">
      <h3 class="mb-4 text-center">Login</h3>
      <?php if ($error): ?>
        <div class="alert alert-danger p-2 small"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="POST" action="">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
/**
 * JANGAN LUPA:
 * 1) Buat tabel `users` dengan kolom `id` (INT AUTO_INCREMENT),
 *    `username` (VARCHAR), `password` (VARCHAR) yang menyimpan hash.
 *    Contoh menyimpan password:
 *    $hash = password_hash('rahasia123', PASSWORD_DEFAULT);
 *
 * 2) Buat file logout.php:
 *    <?php
 *    session_start();
 *    session_unset();
 *    session_destroy();
 *    header('Location: login.php');
 *    exit;
 *    ?>
 *
 * 3) Di setiap berkas seperti index.php, sebelum menampilkan konten, tambahkan:
 *    session_start();
 *    if (!isset($_SESSION['user_id'])) {
 *        header('Location: login.php');
 *        exit;
 *    }
 */
?>
