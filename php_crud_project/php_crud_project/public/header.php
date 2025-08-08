<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$logged_in = isset($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP CRUD Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">MySite</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">Content</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php if($logged_in): ?>
        <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex">
        <?php if(!$logged_in): ?>
        <form class="d-flex" method="post" action="login.php">
          <input class="form-control me-2" type="email" name="email" placeholder="Email" required>
          <input class="form-control me-2" type="password" name="password" placeholder="Password" required>
          <button class="btn btn-outline-success" type="submit">Login</button>
        </form>
        <?php else: ?>
        <div class="d-flex align-items-center">
          <span class="me-2">Hi, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
          <a class="btn btn-outline-danger" href="logout.php">Logout</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main class="container">
