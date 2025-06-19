<?php
session_start();
include 'koneksi.php';

$keyword = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$query = "SELECT * FROM article WHERE title LIKE '%$keyword%' ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Semua Artikel - Bacainfo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="emm.css?v=2">
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      scroll-behavior: smooth;
    }
    .background-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: fixed;
      top: 0;
      left: 0;
      z-index: -2;
    }
    .overlay {
      background: rgba(255, 255, 255, 0.06);
      backdrop-filter: blur(10px);
      min-height: 100vh;
      padding-top: 90px;
      position: relative;
      z-index: 1;
    }
    .fixed-navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background-color: white;
      z-index: 1000;
      padding: 10px 20px;
      border-bottom: 1px solid #ddd;
    }
    .card-article {
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      background: rgba(255,255,255,0.8);
    }
    .card-article:hover {
      transform: translateY(-3px);
    }
    .card-img-top {
      height: 150px;
      object-fit: cover;
    }
    .searchbar {
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
<div class="background-wrapper">
  <img src="img/bgkategori.jpg" alt="Background">
</div>
<div class="overlay">
  <div class="fixed-navbar d-flex justify-content-between align-items-center px-4" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); height: 60px; z-index: 1000;">
    <div class="font-weight-bold" style="font-size: 22px; color: #333;">Bacainfo</div>
    <div class="d-flex flex-wrap justify-content-center align-items-center">
      <?php
      $kategori_nav = mysqli_query($koneksi, "SELECT * FROM category ORDER BY nama ASC");
      while ($k = mysqli_fetch_assoc($kategori_nav)) {
        echo '<a href="kategori.php?kategori=' . urlencode($k['nama']) . '" class="nav-link text-dark px-2">' . htmlspecialchars($k['nama']) . '</a>';
      }
      ?>
    </div>
    <div class="d-flex align-items-center">
      <?php if (isset($_SESSION['nickname'])): ?>
        <span class="badge badge-pill badge-light d-flex align-items-center mr-2" style="font-weight: 500; font-size: 14px;">
          <i class="bi bi-person-circle mr-1" style="font-size: 16px;"></i> 
          <?= htmlspecialchars($_SESSION['nickname']); ?>
        </span>
      <?php endif; ?>
      <div class="dropdown">
        <button class="btn btn-sm shadow-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
          style="background-color:rgb(184, 119, 204); border: 1px solid #ccc; border-radius: 50%; font-size: 20px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
          &#8942;
        </button>
        <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="dropdownMenuButton">
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a class="dropdown-item d-flex align-items-center" href="admin_dashboard.php"><i class="bi bi-tools mr-2"></i> Admin Panel</a>
          <?php endif; ?>
          <a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-house-door-fill mr-2"></i> Dashboard</a>
          <a class="dropdown-item d-flex align-items-center" href="kategori.php"><i class="bi bi-grid-fill mr-2"></i> Kategori</a>
          <?php if (isset($_SESSION['nickname'])): ?>
            <a class="dropdown-item d-flex align-items-center" href="artikel.php"><i class="bi bi-journal-text mr-2"></i> Artikel</a>
            <a class="dropdown-item d-flex align-items-center" href="tulis_artikel.php"><i class="bi bi-pencil-square mr-2"></i> Mulai Menulis</a>
            <a class="dropdown-item d-flex align-items-center" href="logout.php"><i class="bi bi-box-arrow-right mr-2"></i> Log Out</a>
          <?php else: ?>
            <a class="dropdown-item d-flex align-items-center" href="login.php"><i class="bi bi-box-arrow-in-right mr-2"></i> Login</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="container py-5 mt-5">
    <h2 class="mb-4 text-center">Semua Artikel</h2>
    <form method="GET" class="mb-4">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari artikel..." value="<?php echo htmlspecialchars($keyword); ?>">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit">Cari</button>
        </div>
      </div>
    </form>

    <div class="row">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($data = mysqli_fetch_assoc($result)) : ?>
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="card card-article h-100">
              <img src="img/<?php echo htmlspecialchars($data['picture']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($data['title']); ?>">
              <div class="card-body">
                <h6 class="card-title">
                  <a href="berita.php?id=<?php echo $data['id']; ?>" class="text-dark" style="text-decoration: none;">
                    <?php echo htmlspecialchars($data['title']); ?>
                  </a>
                </h6>
                <small class="text-muted"><?php echo date('d M Y', strtotime($data['date'])); ?></small>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p class="text-muted">Tidak ada artikel ditemukan.</p>
        </div>
      <?php endif; ?>
    </div>

    <footer class="text-center mt-5 mb-3 text-muted">
      &copy; <?= date('Y') ?> Bacainfo. All rights reserved.
    </footer>
  </div>
</div>
</body>
</html>