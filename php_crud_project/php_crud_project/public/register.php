<?php
require 'config.php';
include 'header.php';

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if(!$name) $errors[] = 'Name is required.';
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if(strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if($password !== $confirm) $errors[] = 'Password confirmation does not match.';

    // Check duplicate email
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if($stmt->fetch()) $errors[] = 'Email already registered.';

    // Handle avatar upload
    $avatar_name = null;
    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE){
        $f = $_FILES['avatar'];
        if($f['error'] !== UPLOAD_ERR_OK) $errors[] = 'Upload error.';
        $allowed = ['image/jpeg','image/png','image/gif'];
        if(!in_array($f['type'], $allowed)) $errors[] = 'Only JPG/PNG/GIF allowed.';
        if($f['size'] > 2*1024*1024) $errors[] = 'Max 2MB file size.';
        if(empty($errors)){
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $avatar_name = uniqid('av_') . '.' . $ext;
            if(!is_dir('uploads')) mkdir('uploads', 0755, true);
            move_uploaded_file($f['tmp_name'], 'uploads/'.$avatar_name);
        }
    }

    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $pdo->prepare('INSERT INTO users (name,email,password,avatar) VALUES (?,?,?,?)');
        $ins->execute([$name,$email,$hash,$avatar_name]);
        echo '<div class="alert alert-success">Registered successfully. You can login from the form in the header.</div>';
    }
}
?>

<h1>Register</h1>
<?php if($errors): ?>
  <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="mb-5">
  <div class="mb-3">
    <label class="form-label">Name</label>
    <input class="form-control" name="name" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input class="form-control" name="email" type="email" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input class="form-control" name="password" type="password" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input class="form-control" name="confirm" type="password" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Avatar (optional)</label>
    <input class="form-control" name="avatar" type="file" accept="image/*">
  </div>
  <button class="btn btn-primary">Register</button>
</form>

<?php include 'footer.php'; ?>
