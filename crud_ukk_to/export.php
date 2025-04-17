<?php
include 'db.php';

// Ambil filter/search dari query string
$whereClause = [];
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $s = mysqli_real_escape_string($koneksi, $_GET['search']);
    $whereClause[] = "nama_barang LIKE '%$s%'";
}
if (isset($_GET['kategori']) && $_GET['kategori'] !== "") {
    $k = (int)$_GET['kategori'];
    $whereClause[] = "kategori_id = $k";
}
$sqlWhere = !empty($whereClause) ? " WHERE " . implode(" AND ", $whereClause) : "";

// Query semuanya tanpa LIMIT
$sql = "SELECT barang.id, nama_barang, nama_kategori, jumlah_stok, harga_barang, tanggal_masuk
        FROM barang 
        JOIN kategori ON barang.kategori_id = kategori.id
        $sqlWhere
        ORDER BY barang.id DESC";
$res = mysqli_query($koneksi, $sql);

// Header CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_barang.csv');

// Output CSV
$out = fopen('php://output', 'w');
// Kolom header
fputcsv($out, ['ID','Nama Barang','Kategori','Stok','Harga','Tanggal Masuk']);
// Data rows
while($row = mysqli_fetch_assoc($res)) {
    // Jika mau formatting harga:
    $row['harga_barang'] = number_format($row['harga_barang'], 0, ',', '.');
    fputcsv($out, $row);
}
fclose($out);
exit;
