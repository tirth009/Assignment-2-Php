<?php
require 'config.php';
if(!isset($_SESSION['user_id'])){ header('Location: index.php'); exit; }
include 'header.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM content WHERE id = ?');
$stmt->execute([$id]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$c){ echo '<div class="alert alert-danger">Content not found.</div>'; include 'footer.php'; exit; }

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $img = $c['image'];
    if(isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
        $f = $_FILES['image'];
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $img = uniqid('ct_').'.'.$ext;
        move_uploaded_file($f['tmp_name'], 'uploads/'.$img);
    }
    if(empty($errors)){
        $pdo->prepare('UPDATE content SET title=?, body=?, image=? WHERE id=?')
            ->execute([$title,$body,$img,$id]);
        echo '<div class="alert alert-success">Updated.</div>';
        $stmt->execute([$id]); $c = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<h1>Edit Content</h1>
<?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" value="<?php echo htmlspecialchars($c['title']); ?>"></div>
  <div class="mb-3"><label class="form-label">Body</label><textarea class="form-control" name="body" rows="6"><?php echo htmlspecialchars($c['body']); ?></textarea></div>
  <div class="mb-3"><label class="form-label">Image</label><input class="form-control" name="image" type="file" accept="image/*"></div>
  <button class="btn btn-primary">Save</button>
</form>

<?php include 'footer.php'; ?>
