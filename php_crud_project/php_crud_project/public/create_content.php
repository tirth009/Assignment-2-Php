<?php
require 'config.php';
if(!isset($_SESSION['user_id'])){ header('Location: index.php'); exit; }
include 'header.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    if(!$title) $errors[] = 'Title required.';
    if(!$body) $errors[] = 'Body required.';
    $img = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
        $f = $_FILES['image'];
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $img = uniqid('ct_').'.'.$ext;
        move_uploaded_file($f['tmp_name'], 'uploads/'.$img);
    }
    if(empty($errors)){
        $ins = $pdo->prepare('INSERT INTO content (title,body,image,created_by) VALUES (?,?,?,?)');
        $ins->execute([$title,$body,$img,$_SESSION['user_id']]);
        header('Location: about.php');
        exit;
    }
}
?>

<h1>Create Content</h1>
<?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title"></div>
  <div class="mb-3"><label class="form-label">Body</label><textarea class="form-control" name="body" rows="6"></textarea></div>
  <div class="mb-3"><label class="form-label">Image (optional)</label><input class="form-control" name="image" type="file" accept="image/*"></div>
  <button class="btn btn-primary">Create</button>
</form>

<?php include 'footer.php'; ?>
