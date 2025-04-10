<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TikShow</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .logo {
      font-size: 28px;
      font-weight: bold;
      margin-left: 30px;
      color: #e50914;
      text-shadow: 2px 2px 10px #e50914;
    }

    .navbar-nav {
      display: flex;
      list-style: none;
    }

    .navbar-nav .nav-tabs {
      margin-right: 15px;
    }

    .navbar-nav .nav-link {
      text-decoration: none;
      color: white;
      padding: 10px 15px;
      border-radius: 5px;
      transition: 0.3s ease-in-out;
      font-weight: bold;
    }

    .navbar-nav .nav-link:hover {
      background-color: #6a0dad;
      box-shadow: 0px 0px 15px #6a0dad;
    }

    header {
      background: linear-gradient(to right, rgb(15, 16, 16), rgb(14, 10, 22));
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .logo-text {
      font-size: 24px;
      font-weight: 800;
      color: red;
      text-shadow: 1px 1px 4px black;
      margin-right: 40px;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 30px;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
    }

    nav ul li a:hover {
      text-decoration: underline;
    }

    .back-button {
      background-color: white;
      color: rgb(179, 0, 255);
      border: none;
      padding: 6px 14px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .back-button:hover {
      background-color: #eee;
      color: rgb(242, 246, 250);
    }

    .left-section {
      display: flex;
      align-items: center;
    }

    .right-section {
      display: flex;
      align-items: center;
    }

    main {
      padding-top: 80px;
    }
  </style>
</head>
<body>

<header class="main-header">
  <div class="logo">TIKSHOW</div>
  <nav class="navbar-expand-lg">
    <div class="container">
      <ul class="navbar-nav">
        <li class="nav nav-tabs"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav nav-tabs"><a class="nav-link" href="about_us.php">About Us</a></li>
        <li class="nav nav-tabs"><a class="nav-link" href="movies.php">Movies</a></li>
        <li class="nav nav-tabs"><a class="nav-link" href="theaters.php">Theaters</a></li>
        <li class="nav nav-tabs"><a class="nav-link" href="offers.php">Offers</a></li>
        <li class="nav nav-tabs"><a class="nav-link" href="profile.php">My Account</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav nav-tabs"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav nav-tabs"><a class="nav-link" href="login.html">Login</a></li>
        <?php endif; ?>
        
      </ul>
    </div>
  </nav>
</header>

<main>
<!-- Main content will go here -->
</main>

</body>
</html>
