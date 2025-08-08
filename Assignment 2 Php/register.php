<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($username && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hash]);
        echo "<div class='alert alert-success'>Registration successful. <a href='login.php'>Login</a></div>";
    } else {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    }
}
?>
<h2>Register</h2>
<form method="post">
  <input type="text" name="username" placeholder="Username" class="form-control mb-2" required>
  <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
  <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
  <button class="btn btn-primary">Register</button>
</form>
<?php require_once 'includes/footer.php'; ?>
