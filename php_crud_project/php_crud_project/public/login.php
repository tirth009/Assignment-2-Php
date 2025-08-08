<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
    } else {
        // set a simple flash (not persistent)
        echo '<div class="container"><div class="alert alert-danger">Invalid credentials.</div></div>';
    }
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
