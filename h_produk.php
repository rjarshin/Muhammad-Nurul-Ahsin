<?php
include "koneksi.php";

$id = $_GET['id'];

// ambil gambar
$get = mysqli_query($conn, "SELECT gambar FROM produk WHERE id='$id'");
$data = mysqli_fetch_array($get);

$gambar = $data['gambar'];

// hapus file gambar
if ($gambar != "" && file_exists("produk_img/" . $gambar)) {
    unlink("produk_img/" . $gambar);
}

// hapus database
$hapus = mysqli_query($conn, "DELETE FROM produk WHERE id='$id'");

if ($hapus) {

    echo "<script>alert('Data berhasil dihapus!');</script>";
    header("refresh:0, produk.php");

} else {

    echo "<script>alert('Data gagal dihapus!');</script>";
    header("refresh:0, produk.php");

}
?>