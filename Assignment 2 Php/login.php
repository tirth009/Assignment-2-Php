<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Invalid credentials</div>";
    }
}
?>
<h2>Login</h2>
<form method="post">
  <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
  <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
  <button class="btn btn-primary">Login</button>
</form>
<?php require_once 'includes/footer.php'; ?>
