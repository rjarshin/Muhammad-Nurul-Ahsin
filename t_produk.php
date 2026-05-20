<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
?>

<?php
include "koneksi.php";

// AUTO GENERATE KODE PRODUK
$auto = mysqli_query($conn, "SELECT max(product_code) as max_code FROM produk");
$hasil = mysqli_fetch_array($auto);
$code = $hasil['max_code'];

if ($code == NULL) {
    $urutan = 0;
} else {
    $urutan = (int) substr($code, 1, 3);
}

$urutan++;
$huruf = "P";
$kd_produk = $huruf . sprintf("%03s", $urutan);

// PROSES SIMPAN
if (isset($_POST['simpan'])) {

    $nm_produk = $_POST['nm_produk'];
    $stok = $_POST['stok'];
    $min_stok = $_POST['min_stok'];
    $harga = $_POST['harga'];
    $id_kategori = $_POST['id_kategori'];

    // UPLOAD GAMBAR
    $imgfile = $_FILES['gambar']['name'];
    $tmp_file = $_FILES['gambar']['tmp_name'];
    $extension = strtolower(pathinfo($imgfile, PATHINFO_EXTENSION));

    $dir = "produk_img/";
    $allowed_extensions = array("jpg", "jpeg", "png", "webp");

// CEK GAMBAR KOSONG ATAU TIDAK
if ($imgfile == "") {
    $imgnewfile = ""; // kalau tidak upload gambar
} else {

        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Format tidak valid.');</script>";
            exit;
        }

        $imgnewfile = md5(time() . $imgfile) . "." . $extension;
        move_uploaded_file($tmp_file, $dir . $imgnewfile);
    }

    // INSERT DATA (dipindah ke luar)
    $query = mysqli_query($conn, "INSERT INTO produk 
    (category_id, product_code, product_name, stock, min_stock, price, gambar)
    VALUES 
    ('$id_kategori', '$kd_produk', '$nm_produk', '$stok', '$min_stok', '$harga', '$imgnewfile')");
        // 🔥 DEBUG ERROR
        if (!$query) {
            die("Error: " . mysqli_error($conn));
        }
    if ($query) {
        echo "<script>alert('Produk berhasil ditambahkan!');</script>";
        header("refresh:0; url=produk.php");
    } else {
        echo "<script>alert('Gagal menambahkan produk!');</script>";
        header("refresh:0; url=produk.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Data Produk - indy</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

   <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">Indy</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">
    <li class="nav-item dropdown pe-3">
      <a
        class="nav-link nav-profile d-flex align-items-center pe-0"
        href="#"
        data-bs-toggle="dropdown">
        <img
          src="assets/img/profile-img.jpg"
          alt="Profile"
          class="rounded-circle" /> </a><ul
        class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; ?></h6>
          <span><?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'Role'; ?></span>
        </li>
        <li>
          <hr class="dropdown-divider" />
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>
      </ul>
      </li>
    </ul>
</nav>  

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link collapsed" href="index.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="kategori_produk.php">
                    <i class="bi bi-person"></i>
                    <span>Kategori Produk</span>
                </a>
            </li><!-- End Profile Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="produk.php">
                    <i class="bi bi-question-circle"></i>
                    <span>Data_Produk</span>
                </a>
            </li><!-- End F.A.Q Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="laporan.php">
                    <i class="bi bi-envelope"></i>
                    <span>Laporan</span>
                </a>
            </li><!-- End Contact Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="users.php">
                    <i class="bi bi-card-list"></i>
                    <span>Manajemen User</span>
                </a>
            </li><!-- End Register Page Nav -->
        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Data Produk</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item">Data Produk</li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Tambah Produk</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" method="POST" enctype="multipart/form-data">

                            <div class="col-12">
                                <label class="form-label">Kode Produk</label>
                                <input type="text" class="form-control" value="<?php echo $kd_produk; ?>" readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" name="nm_produk" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stok" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Minimal Stok</label>
                                <input type="number" class="form-control" name="min_stok" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control" name="harga" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" name="id_kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php
                                include "koneksi.php";
                                $query = mysqli_query($conn, "SELECT * FROM categories");
                                while ($kat = mysqli_fetch_array($query)) {
                                    echo "<option value='$kat[id]'>$kat[category_name]</option>";
                                }
                                ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Gambar Produk</label>
                                <input type="file" class="form-control" name="gambar" accept="image/*">
                            </div>

                            <div class="text-center">
                                <a href="produk.php" class="btn btn-warning" style="color:black;">Kembali</a>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-success" name="simpan">Simpan</button>
                            </div>

                            </form><!-- Vertical Form -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>indy</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="">Muhammad Nurul Ahsin</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>