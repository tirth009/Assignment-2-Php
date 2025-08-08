<?php
require 'config.php';
if(!isset($_SESSION['user_id'])){ header('Location: index.php'); exit; }
include 'header.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$user){ echo '<div class="alert alert-danger">User not found.</div>'; include 'footer.php'; exit; }

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if(!$name) $errors[] = 'Name required.';
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    // Check email uniqueness
    $s = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
    $s->execute([$email,$id]);
    if($s->fetch()) $errors[] = 'Email already used by another account.';

    // Avatar
    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE){
        $f = $_FILES['avatar'];
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $avatar_name = uniqid('av_') . '.' . $ext;
        move_uploaded_file($f['tmp_name'], 'uploads/'.$avatar_name);
    } else {
        $avatar_name = $user['avatar'];
    }

    if(empty($errors)){
        $pdo->prepare('UPDATE users SET name=?, email=?, avatar=? WHERE id=?')
            ->execute([$name,$email,$avatar_name,$id]);
        echo '<div class="alert alert-success">Updated.</div>';
        // refresh data
        $stmt->execute([$id]); $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<h1>Edit User</h1>
<?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"></div>
  <div class="mb-3"><label class="form-label">Email</label><input class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></div>
  <div class="mb-3"><label class="form-label">Avatar</label><input class="form-control" name="avatar" type="file"></div>
  <button class="btn btn-primary">Save</button>
</form>

<?php include 'footer.php'; ?>
