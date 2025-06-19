<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = mysqli_real_escape_string($koneksi, $_POST['nickname']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $cek = mysqli_query($koneksi, "SELECT id FROM author WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        mysqli_query($koneksi, "
            INSERT INTO author (nickname, email, password)
            VALUES ('$nickname', '$email', '$password')
        ");
        $_SESSION['user_id'] = mysqli_insert_id($koneksi);
        $_SESSION['nickname'] = $nickname;
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar - Bacainfo</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      font-family: 'Fredoka', sans-serif;
      margin: 0;
    }

    .background-wrapper {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }

    .background-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.6);
    }

    .register-container {
      max-width: 420px;
      margin: 100px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .register-header {
      text-align: center;
      margin-bottom: 25px;
    }

    .register-header h2 {
      font-weight: 600;
    }

    .form-control {
      border-radius: 8px;
    }

    .btn-success {
      border-radius: 8px;
      width: 100%;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
    }

    .background-blur {
      position: fixed;
      top: 0;
      left: 0;
      z-index: -1;
      width: 100%;
      height: 100%;
      background: url('img/bgkategori.jpg') no-repeat center center;
      background-size: cover;
      filter: blur(20px);
      transform: scale(1.1);
    }
  </style>
</head>
<body>
  <div class="background-wrapper">
    <img src="img/bgkategori.jpg" alt="Background Gambar">
  </div>

  <div class="register-container">
    <div class="register-header">
      <h2><i class="fas fa-user-plus"></i> Daftar Akun Bacainfo</h2>
    </div>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>"; ?>

    <form method="POST">
      <div class="form-group">
        <label>Nama Penulis</label>
        <input type="text" name="nickname" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success mt-2">Daftar</button>
    </form>

    <div class="login-link">
      <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
  </div>
</body>
</html>
