<?php

session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

echo "Selamat datang, " . htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mobile Shopee</title>

  <!-- Bootstrap CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <!-- Owl-carousel CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha256-UhQQ4fxEeABh4JrcmAJ1+16id/1dnlOEVCFOxDef9Lw=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha256-kksNxjDRxd/5+jGurZUJd1sdR2v+ClrCl3svESBaJqw=" crossorigin="anonymous" />

  <!-- font awesome icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo+Thambi+2&family=Raleway&family=Rubik&display=swap" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <!-- start #header -->
  <header id="header">
    <div class="strip d-flex justify-content-between px-4 py-1 bg-light">
      <p class="font-rale font-size-12 text-black-50 m-0">Jordan Calderon 430-985 Eleifend St. Duluth Washington 92611 (427) 930-5255</p>
      <div class="font-rale font-size-14">
        <a href="#" class="px-3 border-right border-left text-dark">Login</a>
        <a href="#" class="px-3 border-right text-dark">Whishlist (0)</a>
      </div>
    </div>

    <!-- Primary Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark color-second-bg">
      <a class="navbar-brand" href="#">Sneak & Style</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav m-auto font-rubik">
          <li class="nav-item active">
            <a class="nav-link" href="#">On Sale</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="dashboardToko.php">Toko Saya</a>
          </li>
          <li>
            <form method="GET" action="search.php" class="search-form" style="display:flex; align-items:center;">
              <input type="text" name="search" placeholder="Cari produk atau toko..." style="padding:6px 10px; font-size:14px;" />
              <button type="submit" style="padding:6px 12px; margin-left:8px; cursor:pointer;">Cari</button>
            </form>
          </li>
        </ul>
        <form action="#" class="font-size-14 font-rale">
          <a href="#" class="py-2 rounded-pill color-primary-bg">
            <span class="font-size-16 px-2 text-white"><i class="fas fa-shopping-cart"></i></span>
            <span class="px-3 py-2 rounded-pill text-dark bg-light">0</span>
          </a>
        </form>
      </div>
    </nav>
    <!-- !Primary Navigation -->

  </header>
  <!--Start Header-->

  <!-- start #main-site -->
  <main id="main-site">

    <!-- Owl-carousel -->
    <section id="banner-area">
      <div class="owl-carousel owl-theme">
        <div class="item">
          <img src="./assets/Banner1.png" alt="Banner1">
        </div>
        <div class="item">
          <img src="./assets/Banner2.png" alt="Banner2">
        </div>
        <div class="item">
          <img src="./assets/Banner3.png" alt="Banner3">
        </div>
      </div>
    </section>
    <!-- !Owl-carousel -->

    <!-- Tampilkan Produk Berdasarkan Nama Toko -->
    <section id="top-sale">
      <div class="container py-5">
        <?php
        $conn = new mysqli("localhost", "root", "", "ecomm");

        $toko_result = $conn->query("SELECT id, nama_toko FROM stores");

        while ($store = $toko_result->fetch_assoc()) {
          $store_id = $store['id'];
          $nama_toko = $store['nama_toko'];

          echo "<h4 class='font-rubik font-size-20'>$nama_toko</h4><hr>";
          echo '<div class="owl-carousel owl-theme">';

          $produk_result = $conn->query("SELECT * FROM produk WHERE store_id = $store_id");

          while ($row = $produk_result->fetch_assoc()) {
            $img = base64_encode($row['gambar']);
            $tipe = $row['tipe_gambar'];
            $stok = $row['stock'];
        ?>
            <div class="item py-2">
              <div class="product font-rale">
                <div class="d-flex flex-column">
                  <a href="#"><img src="data:<?php echo $tipe; ?>;base64,<?php echo $img; ?>" alt="<?php echo $row['nama']; ?>" class="img-fluid"></a>
                  <div class="text-center">
                    <h6><?php echo $row['nama']; ?></h6>
                    <div class="rating text-warning font-size-12">
                      <?php for ($i = 0; $i < 5; $i++) {
                        echo '<span><i class="' . ($i < $row['rating'] ? 'fas' : 'far') . ' fa-star"></i></span>';
                      } ?>
                    </div>
                    <div class="price py-2">
                      <span>IDR<?php echo $row['harga']; ?></span>
                    </div>
                    <div class="stock py-1 text-secondary">
                      Stok: <?php echo $stok; ?>
                    </div>

                    <?php if ($stok >= 1) { ?>
                      <!-- Tombol aktif jika stok mencukupi -->
                      <form action="pembayaran.php" method="post">
                        <input type="hidden" name="produk_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="nama" value="<?php echo $row['nama']; ?>">
                        <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
                        <button type="submit" class="btn btn-warning font-size-12">Add to Cart</button>
                      </form>
                    <?php } else { ?>
                      <!-- Tombol nonaktif jika stok habis -->
                      <button class="btn btn-secondary font-size-12" disabled>Stok Habis</button>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
        <?php
          }

          echo '</div><br>';
        }
        ?>
      </div>
    </section>

    <!-- !Top Sale per Toko -->

    <!-- Special Price -->
    <section id="special-price">
      <div class="container">
        <h4 class="font-rubik font-size-20">Special Price</h4>

        <!-- Filter Buttons -->
        <div id="filters" class="button-group text-right font-baloo font-size-16">
          <button class="btn is-checked" data-filter="*">Semua Toko</button>

          <?php
          include 'koneksi.php';
          $queryToko = mysqli_query($conn, "SELECT DISTINCT nama_toko FROM stores");
          while ($toko = mysqli_fetch_assoc($queryToko)) {
            $namaToko = $toko['nama_toko'];
            echo "<button class='btn' data-filter='." . strtolower(str_replace(' ', '-', $namaToko)) . "'>$namaToko</button>";
          }
          ?>
        </div>

        <!-- Produk Grid -->
        <div class="grid">
          <?php
          $queryProduk = mysqli_query($conn, "
    SELECT produk.*, stores.nama_toko 
    FROM produk 
    JOIN stores ON produk.store_id = stores.id
  ");

          while ($produk = mysqli_fetch_assoc($queryProduk)) {
            $namaToko = strtolower(str_replace(' ', '-', $produk['nama_toko']));

            echo "
      <div class='grid-item border $namaToko'>
        <div class='item py-2' style='width: 200px;'>
          <div class='product font-rale'>
            <img src='tampilGambar.php?id={$produk['id']}' class='img-fluid' alt='{$produk['nama']}'>
            <h6>{$produk['nama']}</h6>
            <div class='price py-2'>
              <span>Rp " . number_format($produk['harga'], 0, ',', '.') . "</span>
            </div>
          </div>
        </div>
      </div>
      ";
          }
          ?>
        </div>
      </div>
      </div>
    </section>

    <!-- !Special Price -->

    <!-- Banner Ads  -->
    <section id="banner_adds">
      <div class="container py-5 text-center">
        <img src="./assets/1.png" alt="banner1" class="img-fluid">
        <img src="./assets/2.png" alt="banner1" class="img-fluid">
      </div>
    </section>
    <!-- !Banner Ads  -->
    <!-- Blogs -->
    <section id="blogs">
      <div class="container py-4">
        <h4 class="font-rubik font-size-20">Latest Blogs</h4>
        <hr>

        <div class="owl-carousel owl-theme">
          <div class="item">
            <div class="card border-0 font-rale mr-5" style="width: 18rem;">
              <h5 class="card-title font-size-16">Upcoming Mobiles</h5>
              <img src="../assets/blog/blog1.jpg" alt="cart image" class="card-img-top">
              <p class="card-text font-size-14 text-black-50 py-1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis non iste sequi cupiditate tempora iure. Velit accusamus saepe harum sed.</p>
              <a href="#" class="color-second text-left">Go somewhere</a>
            </div>
          </div>
          <div class="item">
            <div class="card border-0 font-rale mr-5" style="width: 18rem;">
              <h5 class="card-title font-size-16">Upcoming Mobiles</h5>
              <img src="../assets/blog/blog2.jpg" alt="cart image" class="card-img-top">
              <p class="card-text font-size-14 text-black-50 py-1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis non iste sequi cupiditate tempora iure. Velit accusamus saepe harum sed.</p>
              <a href="#" class="color-second text-left">Go somewhere</a>
            </div>
          </div>
          <div class="item">
            <div class="card border-0 font-rale mr-5" style="width: 18rem;">
              <h5 class="card-title font-size-16">Upcoming Mobiles</h5>
              <img src="../assets/blog/blog3.jpg" alt="cart image" class="card-img-top">
              <p class="card-text font-size-14 text-black-50 py-1">Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis non iste sequi cupiditate tempora iure. Velit accusamus saepe harum sed.</p>
              <a href="#" class="color-second text-left">Go somewhere</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- !Blogs -->

  </main>
  <!-- !start #main-site -->



  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <!-- Owl Carousel Js file -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha256-pTxD+DSzIwmwhOqTFN+DB+nHjO4iAsbgfyFq5K5bcE0=" crossorigin="anonymous"></script>

  <!--  isotope plugin cdn  -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js" integrity="sha256-CBrpuqrMhXwcLLUd5tvQ4euBHCdh7wGlDfNz8vbu/iI=" crossorigin="anonymous"></script>

  <!-- Custom Javascript -->
  <script src="index.js"></script>
</body>

</html>